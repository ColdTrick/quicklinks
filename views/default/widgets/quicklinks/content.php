<?php
/**
 * show a number of quicklinks in a widget
 */

$widget = elgg_extract('entity', $vars);

$owner = $widget->getOwnerEntity();
if (!($owner instanceof ElggUser)) {
	$owner = elgg_get_logged_in_user_entity();
}

if (empty($owner)) {
	echo elgg_view('output/longtext', ['value' => elgg_echo('loggedinrequired')]);
	return;
}

$menu = elgg_view_menu('quicklinks', [
	'owner' => $owner,
]);

if (empty($menu)) {
	echo elgg_echo('notfound');
	return;
}

echo $menu;
