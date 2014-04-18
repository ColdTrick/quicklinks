<?php
/**
 * Delete a QuickLinks item
 */

$guid = (int) get_input("guid");

if (!empty($guid)) {
	$entity = get_entity($guid);
	
	if (!empty($entity) && elgg_instanceof($entity, "object", QUICKLINKS_SUBTYPE)) {
		if ($entity->canEdit()) {
			if ($entity->delete()) {
				system_message(elgg_echo("quicklinks:action:delete:success"));
			} else {
				register_error(elgg_echo("quicklinks:action:delete:error"));
			}
		} else {
			register_error(elgg_echo("quicklinks:action:delete:error"));
		}
	} else {
		register_error(elgg_echo("quicklinks:action:delete:error"));
	}
} else {
	register_error(elgg_echo("error:missing_data"));
}

forward(REFERER);