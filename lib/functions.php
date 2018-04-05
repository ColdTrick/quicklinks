<?php
/**
 * All helper functions are bundled here
 */

/**
 * Check if the user has already linked this entity
 *
 * @param int $entity_guid the guid of the entity to check
 * @param int $user_guid   the user to check for (default: current user)
 *
 * @return bool
 */
function quicklinks_check_relationship($entity_guid, $user_guid = 0) {
	static $cache;
	
	$entity_guid = sanitise_int($entity_guid, false);
	if (empty($entity_guid)) {
		return false;
	}
	
	$user_guid = sanitise_int($user_guid, false);
	if (empty($user_guid)) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	
	if (empty($user_guid)) {
		return false;
	}
	
	if (!is_array($cache)) {
		$cache = [];
	}
	
	if (!isset($cache[$user_guid])) {
		$cache[$user_guid] = elgg_get_entities([
			'relationship' => \QuickLink::RELATIONSHIP,
			'relationship_guid' => $user_guid,
			'limit' => false,
			'callback' => function($row) {
				return (int) $row->guid;
			},
		]);
	}
	
	return in_array($entity_guid, $cache[$user_guid]);
}

/**
 * Check if a quicklink exists for a given URL
 *
 * @param string $url the url to check
 * @param bool   $return_entity return the quicklink item (if found), default: false
 *
 * @return bool|QuickLink
 */
function quicklinks_check_url($url, $return_entity = false) {
	
	if (empty($url)) {
		return false;
	}
	
	$return_entity = (bool) $return_entity;
	
	$url = elgg_normalize_url($url);
	$url = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
	
	$dbprefix = elgg_get_config('dbprefix');
	
	$options = [
		'type' => 'object',
		'subtype' => QuickLink::SUBTYPE,
		'owner_guid' => elgg_get_logged_in_user_guid(),
		'limit' => 1,
		'joins' => ["JOIN {$dbprefix}objects_entity oe ON e.guid = oe.guid"],
		'wheres' => ["oe.description = '{$url}'"],
	];
	
	if (!$return_entity) {
		$options['count'] = true;
		return (bool) elgg_get_entities($options);
	}
	
	$entities = elgg_get_entities($options);
	if (empty($entities)) {
		return false;
	}
	
	return $entities[0];
}

/**
 * Get the list of supported type/subtypes for quicklinks
 *
 * @return array
 */
function quicklinks_get_supported_types() {
	
	// default to registered entity types
	$supported_types = get_registered_entity_types();
	
	// blacklist some type/subtypes
	$blacklist = [
		'object' => [
			'discussion_reply',
			'comment',
			'thewire',
		],
	];
	foreach ($blacklist as $black_type => $black_subtypes) {
		if (!isset($supported_types[$black_type])) {
			continue;
		}
		
		if (!is_array($black_subtypes)) {
			continue;
		}
		
		foreach ($black_subtypes as $black_subtype) {
			$index = array_search($black_subtype, $supported_types[$black_type]);
			if ($index === false) {
				continue;
			}
			
			unset($supported_types[$black_type][$index]);
		}
	}
	
	// allow others to change the list
	return elgg_trigger_plugin_hook('type_subtypes', 'quicklinks', $supported_types, $supported_types);
}
