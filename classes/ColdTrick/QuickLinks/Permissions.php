<?php

namespace ColdTrick\QuickLinks;

class Permissions {
	
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
	public static function annotateLike($hook, $type, $returnvalue, $params) {
		
		if (empty($params) || !is_array($params)) {
			return;
		}
		
		$entity = elgg_extract('entity', $params);
		if (!($entity instanceof \QuickLink)) {
			return;
		}
		
		$annotation_name = elgg_extract('annotation_name', $params);
		if ($annotation_name !== 'likes') {
			return;
		}
		
		return false;
	}
}