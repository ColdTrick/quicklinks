<?php
/**
 * show a number of quicklinks in a widget
 */

$widget = elgg_extract("entity", $vars);

$num_display = (int) $widget->num_display;
if ($num_display < 1) {
	$num_display = 5;
}

$owner = $widget->getOwnerEntity();
if (!elgg_instanceof($owner, "user")) {
	$owner = elgg_get_logged_in_user_entity();
}

if (!empty($owner)) {
	echo elgg_view("quicklinks/list", array("owner" => $owner, "limit" => $num_display, "pagination" => false));
} else {
	echo elgg_view("output/longtext", array("value" => elgg_echo("loggedinrequired")));
}
