<?php
/**
 * Delete a QuickLinks item
 */

$guid = (int) get_input('guid');
$url = get_input('url');

if (empty($guid) && empty($url)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = false;
if (!empty($guid)) {
	$entity = get_entity($guid);
} else {
	$url = htmlspecialchars_decode($url, ENT_QUOTES);
	
	$entity = quicklinks_check_url($url, true);
}

if (!$entity instanceof \QuickLink || !$entity->canDelete()) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('entity:delete:fail', [elgg_echo('item:object:quicklink')]));
}

return elgg_ok_response('', elgg_echo('entity:delete:success', [elgg_echo('item:object:quicklink')]));
