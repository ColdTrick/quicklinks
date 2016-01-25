<?php

namespace ColdTrick\QuickLinks;

class QuickLinksMenu {
	
	/**
	 * Prepares the quicklinks menu items to optionally hide some items
	 *
	 * @param string          $hook        the name of the hook
	 * @param string          $type        the type of the hook
	 * @param \ElggMenuItem[] $returnvalue the current menu items
	 * @param array           $params      provided params
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function prepare($hook, $type, $returnvalue, $params) {
		
		if (empty($params) || !is_array($params)) {
			return;
		}
		
		if (empty($returnvalue) || !is_array($returnvalue)) {
			return;
		}
		
		$display_limit = (int) elgg_extract('display_limit', $params, 0);
		if ($display_limit > 0 && elgg_in_context('widgets')) {
		
			if (!isset($returnvalue['default'])) {
				return;
			}
		
			foreach ($returnvalue['default'] as $index => $item) {
				if ($index >= $display_limit) {
					$item->addItemClass('hidden');
				}
			}
		}
		
		return $returnvalue;
	}
}