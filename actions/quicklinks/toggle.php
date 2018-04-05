<?php
/**
 * toggle if you wish to have a quicklink to the provided entity
 */

$guid = (int) get_input('guid');
$user_guid = elgg_get_logged_in_user_guid();

if (empty($guid) || empty($user_guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (check_entity_relationship($user_guid, \QuickLink::RELATIONSHIP, $guid)) {
	if (!remove_entity_relationship($user_guid, \QuickLink::RELATIONSHIP, $guid)) {
		return elgg_error_response(elgg_echo('save:fail'));
	}
	
	return elgg_ok_response('', elgg_echo('quicklinks:action:toggle:success:remove'));
}

// add the quicklink
if (!add_entity_relationship($user_guid, \QuickLink::RELATIONSHIP, $guid)) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('quicklinks:action:toggle:success:add'));
