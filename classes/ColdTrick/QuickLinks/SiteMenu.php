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
		if (!elgg_get_plugin_setting('add_to_site_menu', 'quicklinks')) {
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
		
		foreach ($items as $item) {
			$item->setParentName('quicklinks');
			
			$result[] = $item;
		}
		
		return $result;
	}
}
