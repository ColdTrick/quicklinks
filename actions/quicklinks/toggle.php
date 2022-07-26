<?php
/**
 * toggle if you wish to have a quicklink to the provided entity
 */

$guid = (int) get_input('guid');
$user = elgg_get_logged_in_user_entity();

if (empty($guid) || empty($user)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($user->hasRelationship($guid, \QuickLink::RELATIONSHIP)) {
	if (!$user->removeRelationship($guid, \QuickLink::RELATIONSHIP)) {
		return elgg_error_response(elgg_echo('save:fail'));
	}
	
	return elgg_ok_response('', elgg_echo('quicklinks:action:toggle:success:remove'));
}

// add the quicklink
if (!$user->addRelationship($guid, \QuickLink::RELATIONSHIP)) {
	return elgg_error_response(elgg_echo('save:fail'));
}

return elgg_ok_response('', elgg_echo('quicklinks:action:toggle:success:add'));
