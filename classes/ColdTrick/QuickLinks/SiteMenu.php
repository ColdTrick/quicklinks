<?php

namespace ColdTrick\QuickLinks;

use Elgg\Menu\MenuItems;

/**
 * Add menu items to the site menu
 */
class SiteMenu {
	
	/**
	 * Registers QuickLinks menu items
	 *
	 * @param \Elgg\Event $event 'register', 'menu:site'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		if (!elgg_get_plugin_setting('add_to_site_menu', 'quicklinks') || !elgg_is_logged_in()) {
			return null;
		}
		
		$items = elgg()->menus->getUnpreparedMenu('quicklinks')->getItems();
		if (empty($items)) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'quicklinks',
			'icon' => 'star-regular',
			'text' => elgg_echo('quicklinks'),
			'href' => false,
		]);
		
		$highest_prio = 0;
		foreach ($items as $item) {
			$item->setParentName('quicklinks');
			
			$result[] = $item;
			$highest_prio = $item->getPriority() > $highest_prio ? $item->getPriority() : $highest_prio;
		}
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'add',
			'icon' => 'plus',
			'text' => elgg_echo('quicklinks:add'),
			'href' => elgg_generate_url('add:object:quicklink', ['guid' => elgg_get_logged_in_user_guid()]),
			'class' => 'elgg-lightbox',
			'parent_name' => 'quicklinks',
			'priority' => $highest_prio + 1,
		]);
		
		return $result;
	}
}
