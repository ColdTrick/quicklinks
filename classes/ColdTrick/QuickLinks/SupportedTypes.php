<?php

namespace ColdTrick\QuickLinks;

class SupportedTypes {
	
	/**
	 * Some plugins unregister page/page_top as searchable, so they can't be added by quicklinks
	 * This fixes that problem
	 *
	 * @param string $hook         the name of the hook
	 * @param string $type         the type of the hook
	 * @param array  $return_value current return value
	 * @param array  $params       supplied params
	 *
	 * @return void|array
	 */
	public static function fixPages($hook, $type, $return_value, $params) {
		
		if (!is_array($return_value)) {
			return;
		}
		
		if (!isset($return_value['object'])) {
			return;
		}
		
		if (in_array('page', $return_value['object']) && !in_array('page_top', $return_value['object'])) {
			$return_value['object'][] = 'page_top';
		}
		
		if (in_array('page_top', $return_value['object']) && !in_array('page', $return_value['object'])) {
			$return_value['object'][] = 'page';
		}
		
		return $return_value;
	}
}
