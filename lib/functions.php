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
		
		$options = [
			'relationship' => QUICKLINKS_RELATIONSHIP,
			'relationship_guid' => $user_guid,
			'limit' => false,
			'callback' => 'quicklinks_row_to_guid'
		];
		
		$cache[$user_guid] = elgg_get_entities_from_relationship($options);
	}
	
	return in_array($entity_guid, $cache[$user_guid]);
}

/**
 * Return the guid of the row
 *
 * @param stdClass $row the MySQL row
 *
 * @return int
 */
function quicklinks_row_to_guid($row) {
	return (int) $row->guid;
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
 * Get menu items to toggle quicklink
 *
 * @param array $params supplied params, supports
 *  - entity => an ElggEntity to toggle
 *  - title => to create for a random name (url is required)
 *  - url => to create for a random url (title is suggested)
 *
 * @return false|ElggMenuItem
 */
function quicklinks_get_toggle_menu_items($params = []) {
	
	if (empty($params) || !is_array($params)) {
		return false;
	}
	
	$items = [];
	
	$entity = elgg_extract('entity', $params);
	if (($entity instanceof ElggEntity) && $entity->getGUID()) {
		// do it the easy way
		// is linked?
		$linked = quicklinks_check_relationship($entity->getGUID());
		
		// build menu items
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks',
			'text' => elgg_view_icon('star-empty'),
			'href' => "action/quicklinks/toggle?guid={$entity->getGUID()}",
			'title' => elgg_echo('quicklinks:menu:entity:title'),
			'is_action' => true,
			'item_class' => $linked ? 'hidden' : '',
		]);
		$items[] = \ElggMenuItem::factory([
			'name' => 'quicklinks_remove',
			'text' => elgg_view_icon('star-hover'),
			'href' => "action/quicklinks/toggle?guid={$entity->getGUID()}",
			'title' => elgg_echo('quicklinks:menu:entity:title'),
			'is_action' => true,
			'item_class' => $linked ? '' : 'hidden',
		]);
		
		return $items;
	}
	
	$url = elgg_extract('url', $params);
	if (empty($url)) {
		return false;
	}
	
	$linked = quicklinks_check_url($url);
	
	// not linked
	$add_href = elgg_http_add_url_query_elements('action/quicklinks/edit', [
		'title' => elgg_extract('title', $params),
		'url' => $url,
	]);
	
	$items[] = \ElggMenuItem::factory([
		'name' => 'quicklinks',
		'text' => elgg_view_icon('star-empty'),
		'href' => $add_href,
		'title' => elgg_echo('quicklinks:menu:entity:title'),
		'is_action' => true,
		'item_class' => $linked ? 'hidden' : '',
	]);
	
	// linked
	$remove_href = elgg_http_add_url_query_elements('action/quicklinks/delete', [
		'url' => $url,
	]);
	
	$items[] = \ElggMenuItem::factory(array(
		'name' => 'quicklinks_remove',
		'text' => elgg_view_icon('star-hover'),
		'href' => $remove_href,
		'title' => elgg_echo('quicklinks:menu:entity:title'),
		'is_action' => true,
		'item_class' => $linked ? '' : 'hidden',
	));
	
	return $items;
}

/**
 * Check if a type/subtype can be added to quicklinks
 *
 * @param string $type    the type of the entity
 * @param string $subtype the subtype of the entity
 *
 * @see is_registered_entity_type()
 *
 * @return bool
 */
function quicklinks_can_link($type, $subtype = null) {
	
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
	$supported_types = elgg_trigger_plugin_hook('type_subtypes', 'quicklinks', $supported_types, $supported_types);
	
	if (empty($supported_types) || !is_array($supported_types)) {
		return false;
	}
	
	// is type registered
	$type = strtolower($type);
	if (!isset($supported_types[$type])) {
		return false;
	}
	
	// check (optional) subtype
	if (!empty($subtype) && !in_array($subtype, $supported_types[$type])) {
		return false;
	}
	
	return true;
}
