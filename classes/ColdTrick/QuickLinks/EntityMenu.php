<?php

namespace ColdTrick\QuickLinks;

class EntityMenu {
	
	/**
	 * Add menu items to the entity menu
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue the current return value
	 * @param array           $params      supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerEntity($hook, $type, $returnvalue, $params) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		if (empty($params) || !is_array($params)) {
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \ElggEntity)) {
			return;
		}
		
		if (!$entity->getGUID()) {
			// can sometimes happen for temp entities (eg search results)
			return;
		}
		
		// add quicklink toggle to registered entity types
		$registered_entity_types = get_registered_entity_types($entity->getType());
		
		$black_listed_entity_subtypes = [
			'discussion_reply',
			'comment',
			'thewire',
		];
		
		if (in_array($entity->getSubtype(), $black_listed_entity_subtypes)) {
			// black listed
			return;
		}
		
		if (empty($registered_entity_types) || !is_array($registered_entity_types)) {
			// no registered entity types found
			return;
		}
		
		if (!in_array($entity->getSubtype(), $registered_entity_types)) {
			// not a valid entity type
			return;
		}
		
		$items = quicklinks_get_toggle_menu_items(['entity' => $entity]);
		if (empty($items)) {
			return;
		}
		
		foreach ($items as $item) {
// 			$returnvalue[] = $item;
		}
		
		$items = quicklinks_get_toggle_menu_items(['title' => $entity->title, 'url' => $entity->getURL()]);
		if (empty($items)) {
			return;
		}
		
		foreach ($items as $item) {
			$returnvalue[] = $item;
		}
		
		return $returnvalue;
	}
	
	/**
	 * Cleanup menu items from the entity menu of a quicklink item
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue the current return value
	 * @param array           $params      supplied params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerQuickLinkCleanup($hook, $type, $returnvalue, $params) {
		
		if (empty($params) || !is_array($params)) {
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \QuickLink)) {
			// not a quicklinks item
			return;
		}
		
		// TODO: support edit
		$allowed_items = [
			'delete',
		];
		foreach ($returnvalue as $index => $menu_item) {
			if (in_array($menu_item->getName(), $allowed_items)) {
				continue;
			}
			
			unset($returnvalue[$index]);
		}
		
		return $returnvalue;
	}
}