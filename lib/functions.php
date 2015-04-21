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
		$cache = array();
	}
	
	if (!isset($cache[$user_guid])) {
		
		$options = array(
			"relationship" => QUICKLINKS_RELATIONSHIP,
			"relationship_guid" => $user_guid,
			"limit" => false,
			"callback" => "quicklinks_row_to_guid"
		);
		
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
 * Configure the correct URL for our own custom subtype
 *
 * @param ElggEntity $entity the entity to get the url for
 *
 * @return string
 */
function quicklinks_url_handler($entity) {
	$result = "";
	
	if (!empty($entity) && elgg_instanceof($entity, "object", QUICKLINKS_SUBTYPE)) {
		$result = $entity->description;
	}

	return $result;
}