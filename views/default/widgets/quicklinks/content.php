<?php
/**
 * show a number of quicklinks in a widget
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

$owner = $widget->getOwnerEntity();
if (!$owner instanceof \ElggUser) {
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
	$menu = elgg_echo('notfound');
}

echo $menu;

if ($owner->guid === elgg_get_logged_in_user_guid()) {
	echo elgg_view('page/components/list/widget_more', [
		'widget_more' => elgg_view('output/url', [
			'icon' => 'plus',
			'text' => elgg_echo('quicklinks:add'),
			'href' => elgg_generate_url('add:object:quicklink', [
				'guid' => elgg_get_logged_in_user_guid(),
			]),
			'class' => 'elgg-lightbox',
		]),
	]);
}
