<?php

namespace ColdTrick\QuickLinks\Menus;

use Elgg\Menu\MenuItems;
use Elgg\Menu\MenuSection;
use Elgg\Menu\PreparedMenu;

/**
 * Add menu items to the site menu
 */
class Site {
	
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
	
	/**
	 * Fix the ordering of quicklink child menu items
	 *
	 * @param \Elgg\Event $event 'prepare', 'menu:site'
	 *
	 * @return null|PreparedMenu
	 */
	public static function prepareQuickLinksOrdering(\Elgg\Event $event): ?PreparedMenu {
		if (!elgg_get_plugin_setting('add_to_site_menu', 'quicklinks') || !elgg_is_logged_in()) {
			return null;
		}
		
		if ($event->getParam('sort_by') === 'priority') {
			return null;
		}
		
		/* @var $menu PreparedMenu */
		$menu = $event->getValue();
		
		/* @var $items MenuSection */
		foreach ($menu as $section => $items) {
			if (!$items->has('quicklinks')) {
				continue;
			}
			
			/* @var $quicklinks \ElggMenuItem */
			$quicklinks = $items->get('quicklinks');
			$quicklinks->sortChildren('\ElggMenuBuilder::compareByPriority');
			
			break;
		}
		
		return $menu;
	}
}
