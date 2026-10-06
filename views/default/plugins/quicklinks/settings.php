<?php

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('quicklinks:settings:add_to_site_menu'),
	'name' => 'params[add_to_site_menu]',
	'value' => $plugin->add_to_site_menu,
]);
