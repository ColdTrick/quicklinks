<?php
/**
 * toggle if you wish to have a quicklink to the provided entity
 */

$guid = (int) get_input("guid");
$user_guid = elgg_get_logged_in_user_guid();

if (!empty($guid)) {
	if (check_entity_relationship($user_guid, QUICKLINKS_RELATIONSHIP, $guid)) {
		if (remove_entity_relationship($user_guid, QUICKLINKS_RELATIONSHIP, $guid)) {
			system_message(elgg_echo("quicklinks:action:toggle:success:remove"));
		} else {
			register_error(elgg_echo("save:fail"));
		}
	} else {
		if (add_entity_relationship($user_guid, QUICKLINKS_RELATIONSHIP, $guid)) {
			system_message(elgg_echo("quicklinks:action:toggle:success:add"));
		} else {
			register_error(elgg_echo("save:fail"));
		}
	}
} else {
	register_error(elgg_echo("error:missing_data"));
}

forward(REFERER);
