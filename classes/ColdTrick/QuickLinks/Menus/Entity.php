<?php

namespace ColdTrick\QuickLinks\Menus;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the entity menu
 */
class Entity {

	/**
	 * Add menu items to the entity menu
	 *
	 * @param \Elgg\Event $event 'register', 'menu:entity'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!elgg_is_logged_in()) {
			return null;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return null;
		}
		
		if (!$entity->guid) {
			// can sometimes happen for temp entities (eg search results)
			return null;
		}
		
		// add QuickLink toggle to registered entity types
		if (!self::canLink($entity->getType(), $entity->getSubtype())) {
			// no registered entity types found
			return null;
		}
		
		$items = self::getToggleMenuItems(['entity' => $entity]);
		if (empty($items)) {
			return null;
		}
		
		/* @var $return MenuItems */
		$return = $event->getValue();
		
		foreach ($items as $item) {
			$return[] = $item;
		}
		
		return $return;
	}
	
	/**
	 * Check if a type/subtype can be added to quicklinks
	 *
	 * @param string $type    the type of the entity
	 * @param string $subtype the subtype of the entity
	 *
	 * @return bool
	 */
	public static function canLink(string $type, string $subtype): bool {
		$supported_types = quicklinks_get_supported_types();
		if (empty($supported_types)) {
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
	 *                      - entity => an ElggEntity to toggle
	 *                      - title => to create for a random name (url is required)
	 *                      - url => to create for a random url (title is suggested)
	 *
	 * @return null|\ElggMenuItem[]
	 */
	public static function getToggleMenuItems(array $params = []): ?array {
		if (empty($params)) {
			return null;
		}
		
		$items = [];
		
		$entity = elgg_extract('entity', $params);
		if ($entity instanceof \ElggEntity && $entity->guid) {
			// do it the easy way
			// is linked?
			$linked = quicklinks_check_relationship($entity->guid);
			
			// build menu items
			$items[] = \ElggMenuItem::factory([
				'name' => 'quicklinks',
				'icon' => 'star-regular',
				'text' => elgg_echo('quicklinks:add:entity'),
				'href' => elgg_generate_action_url('quicklinks/toggle', ['guid' => $entity->guid]),
				'title' => elgg_echo('quicklinks:menu:entity:title:add'),
				'item_class' => $linked ? 'hidden' : '',
				'data-toggle' => 'quicklinks-remove',
			]);
			
			$items[] = \ElggMenuItem::factory([
				'name' => 'quicklinks-remove',
				'icon' => 'star',
				'text' => elgg_echo('quicklinks:remove'),
				'href' => elgg_generate_action_url('quicklinks/toggle', ['guid' => $entity->guid]),
				'title' => elgg_echo('quicklinks:menu:entity:title:remove'),
				'item_class' => $linked ? '' : 'hidden',
				'data-toggle' => 'quicklinks',
			]);
			
			return $items;
		}
		
		$url = elgg_extract('url', $params);
		if (empty($url)) {
			return null;
		}
		
		$linked = quicklinks_check_url($url);
		
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks',
			'icon' => 'star-regular',
			'href' => elgg_generate_action_url('quicklinks/edit', [
				'title' => elgg_extract('title', $params),
				'url' => $url,
			]),
			'title' => elgg_echo('quicklinks:menu:entity:title:add'),
			'item_class' => $linked ? 'hidden' : '',
		]);
				
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks_remove',
			'icon' => 'star',
			'href' => elgg_generate_action_url('quicklinks/delete', ['url' => $url]),
			'title' => elgg_echo('quicklinks:menu:entity:title:remove'),
			'item_class' => $linked ? '' : 'hidden',
		]);
		
		return $items;
	}
}
