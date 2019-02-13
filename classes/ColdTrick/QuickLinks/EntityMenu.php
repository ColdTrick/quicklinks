<?php

namespace ColdTrick\QuickLinks;

class EntityMenu {

	/**
	 *Add menu items to the entity menu
	 *
	 * @param \Elgg\Hook $hook hook
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		
		if (!elgg_is_logged_in()) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity) {
			return;
		}
		
		if (!$entity->guid) {
			// can sometimes happen for temp entities (eg search results)
			return;
		}
		
		// add quicklink toggle to registered entity types
		if (!self::canLink($entity->getType(), $entity->getSubtype())) {
			// no registered entity types found
			return;
		}
		
		$items = self::getToggleMenuItems(['entity' => $entity]);
		if (empty($items)) {
			return;
		}
		
		$returnvalue = $hook->getValue();
		
		foreach ($items as $item) {
			$returnvalue[] = $item;
		}
		
		return $returnvalue;
	}
	
	/**
	 * Check if a type/subtype can be added to quicklinks
	 *
	 * @param string $type    the type of the entity
	 * @param string $subtype the subtype of the entity
	 *
	 * @see is_registered_entity_type()
	 *
	 * @return bool
	 */
	public static function canLink($type, $subtype = null) {
		
		$supported_types = quicklinks_get_supported_types();
		if (empty($supported_types) || !is_array($supported_types)) {
			return false;
		}
		
		// is type registered
		$type = strtolower($type);
		if (!isset($supported_types[$type])) {
			return false;
		}
		
		// check (optional) subtype
		if (!empty($subtype) && !in_array($subtype, $supported_types[$type])) {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Get menu items to toggle quicklink
	 *
	 * @param array $params supplied params, supports
	 *  - entity => an ElggEntity to toggle
	 *  - title => to create for a random name (url is required)
	 *  - url => to create for a random url (title is suggested)
	 *
	 * @return false|ElggMenuItem
	 */
	public static function getToggleMenuItems($params = []) {
		
		if (empty($params) || !is_array($params)) {
			return false;
		}
		
		$items = [];
		
		$entity = elgg_extract('entity', $params);
		if (($entity instanceof \ElggEntity) && $entity->guid) {
			// do it the easy way
			// is linked?
			$linked = quicklinks_check_relationship($entity->guid);
			
			// build menu items
			$items[] = \ElggMenuItem::factory([
				'name' => 'quicklinks',
				'text' => elgg_echo('quicklinks:add:entity'),
				'icon' => 'star-empty',
				'href' => elgg_generate_action_url('quicklinks/toggle', ['guid' => $entity->guid]),
				'title' => elgg_echo('quicklinks:menu:entity:title'),
				'item_class' => $linked ? 'hidden' : '',
				'data-toggle' => 'quicklinks-remove',
			]);
			
			$items[] = \ElggMenuItem::factory([
				'name' => 'quicklinks-remove',
				'icon' => 'star-hover',
				'text' => elgg_echo('quicklinks:remove'),
				'href' => elgg_generate_action_url('quicklinks/toggle', ['guid' => $entity->guid]),
				'title' => elgg_echo('quicklinks:menu:entity:title'),
				'item_class' => $linked ? '' : 'hidden',
				'data-toggle' => 'quicklinks',
			]);
			
			return $items;
		}
		
		$url = elgg_extract('url', $params);
		if (empty($url)) {
			return false;
		}
		
		$linked = quicklinks_check_url($url);
		
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks',
			'icon' => 'star-empty',
			'href' => elgg_generate_action_url('quicklinks/edit', [
				'title' => elgg_extract('title', $params),
				'url' => $url,
			]),
			'title' => elgg_echo('quicklinks:menu:entity:title'),
			'item_class' => $linked ? 'hidden' : '',
		]);
				
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks_remove',
			'icon' => 'star-hover',
			'href' => elgg_generate_action_url('quicklinks/delete', ['url' => $url]),
			'title' => elgg_echo('quicklinks:menu:entity:title'),
			'item_class' => $linked ? '' : 'hidden',
		]);
		
		return $items;
	}
}
