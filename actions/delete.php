<?php
/**
 * Delete a QuickLinks item
 */

$guid = (int) get_input('guid');
$url = get_input('url');

if (empty($guid) && empty($url)) {
	register_error(elgg_echo('error:missing_data'));
	forward(REFERER);
}

$entity = false;
if (!empty($guid)) {
	$entity = get_entity($guid);
} else {
	$url = htmlspecialchars_decode($url, ENT_QUOTES);
	
	$entity = quicklinks_check_url($url, true);
}

if (!($entity instanceof QuickLink) || !$entity->canEdit()) {
	register_error(elgg_echo('quicklinks:action:delete:error'));
	forward(REFERER);
}

if ($entity->delete()) {
	system_message(elgg_echo('quicklinks:action:delete:success'));
} else {
	register_error(elgg_echo('quicklinks:action:delete:error'));
}

forward(REFERER);