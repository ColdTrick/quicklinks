<?php
/**
 * Add/edit a quicklink
 *
 * @todo support edit
 * @todo sticky form
 */

$container = elgg_extract('container', $vars);
if (!$container instanceof \ElggEntity) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'container_guid',
	'value' => $container->guid,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('title'),
	'name' => 'title',
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('quicklinks:edit:url'),
	'name' => 'url',
	'required' => true,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);
