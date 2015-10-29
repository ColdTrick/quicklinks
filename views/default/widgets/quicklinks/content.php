<?php
/**
 * show a number of quicklinks in a widget
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$owner = $widget->getOwnerEntity();
if (!($owner instanceof ElggUser)) {
	$owner = elgg_get_logged_in_user_entity();
}

if (empty($owner)) {
	echo elgg_view('output/longtext', ['value' => elgg_echo('loggedinrequired')]);
	return;
}

echo elgg_view('quicklinks/list', [
	'owner' => $owner,
	'limit' => $num_display
]);
