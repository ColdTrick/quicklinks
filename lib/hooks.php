<?php
/**
 * All plugin hook callback functions are bundled here
 */

/**
 * Prepares the quicklinks menu items to optionally hide some items
 *
 * @param string         $hook        'prepare'
 * @param string         $type        'menu:quiclinks'
 * @param ElggMenuItem[] $returnvalue the current menu items
 * @param array          $params      provided params
 *
 * @return ElggMenuItem[]
 */
function quicklinks_prepare_quicklinks_menu_hook($hook, $type, $returnvalue, $params) {
	
	if (empty($params) || !is_array($params)) {
		return $returnvalue;
	}
	
	if (empty($returnvalue) || !is_array($returnvalue)) {
		return $returnvalue;
	}
		
	$display_limit = (int) elgg_extract('display_limit', $params, 0);
	if ($display_limit > 0 && elgg_in_context('widgets')) {
		
		if (!isset($returnvalue['default'])) {
			return $returnvalue;
		}
		
		foreach ($returnvalue['default'] as $index => $item) {
			if ($index >= $display_limit) {
				$item->addItemClass('hidden');
			}
		}
	}
	
	return $returnvalue;
}

/**
 * QuickLinks are not likeable
 *
 * @param string $hook        the name of the hook
 * @param string $type        the type of the hook
 * @param bool   $returnvalue current return value
 * @param array  $params      supplied params
 *
 * @return void|false
 */
function quicklinks_permissions_check_annotate_hook($hook, $type, $returnvalue, $params) {
	
	if (empty($params) || !is_array($params)) {
		return;
	}
	
	$entity = elgg_extract('entity', $params);
	if (!($entity instanceof QuickLink)) {
		return;
	}
	
	$annotation_name = elgg_extract('annotation_name', $params);
	if ($annotation_name !== 'likes') {
		return;
	}
	
	return false;
}
