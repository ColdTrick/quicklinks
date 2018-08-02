<?php

namespace ColdTrick\QuickLinks;

class SiteMenu {
	
	/**
	 * Registers QuickLinks menu items
	 *
	 * @param \Elgg\Hook $hook hook
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		if (!elgg_get_plugin_setting('add_to_site_menu', 'quicklinks') || !elgg_is_logged_in()) {
			return;
		}
		
		$items = elgg()->menus->getUnpreparedMenu('quicklinks')->getItems();
		if (empty($items)) {
			return;
		}
		
		$result = $hook->getValue();
		
		$result[] = \ElggMenuItem::factory([
			'name' => 'quicklinks',
			'icon' => 'star-empty',
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
