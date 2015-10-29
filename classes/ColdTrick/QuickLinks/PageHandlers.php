<?php

namespace ColdTrick\QuickLinks;

class PageHandlers {
	
	/**
	 * The page handler for /quicklinks
	 *
	 * @param array $page the url parts
	 *
	 * @return bool
	 */
	public static function quicklinks($page) {
		
		$include_file = '';
		$base_path = elgg_get_plugins_path() . 'quicklinks/';
		
		switch ($page[0]) {
			case 'add':
				$include_file = "{$base_path}pages/add.php";
				break;
		}
		
		if (empty($include_file)) {
			return false;
		}
		
		include($include_file);
		return true;
	}
}
