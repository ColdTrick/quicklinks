<?php

$owner = elgg_extract('owner', $vars, elgg_get_logged_in_user_entity());
$limit = elgg_extract('limit', $vars, false);

if (!($owner instanceof ElggUser)) {
	return;
}

$type_subtypes = get_registered_entity_types();
if (!is_array($type_subtypes)) {
	$type_subtypes = array();
}

// add quicklinks subtype
if (!isset($type_subtypes['object'])) {
	$type_subtypes['object'] = array();
}
if (!in_array(QuickLink::SUBTYPE, $type_subtypes['object'])) {
	$type_subtypes['object'][] = QuickLink::SUBTYPE;
}

// loop though to prevent groups/users from being found because of a bug in Elgg
foreach ($type_subtypes as $type => $subtypes) {
	if (empty($subtypes)) {
		$type_subtypes[$type] = false;
	}
}

$options = array(
	'type_subtype_pairs' => $type_subtypes,
	'limit' => false,
	'relationship' => QUICKLINKS_RELATIONSHIP,
	'relationship_guid' => $owner->getGUID(),
	'order_by' => 'r.time_created DESC'
);
// @todo fix default time_created by adding an extra field in the select because of relationship created instead of time_created of related entity
elgg_push_context('quicklinks');
$configured_priorities = $owner->getPrivateSetting('quicklinks_order');
if ($configured_priorities) {
	$configured_priorities = json_decode($configured_priorities);
}
$entities = new ElggBatch('elgg_get_entities_from_relationship', $options);
foreach ($entities as $index => $entity) {
	$priority = false;
	
	if (!empty($configured_priorities) && is_array($configured_priorities)) {
		$priority = array_search($entity->guid, $configured_priorities);
	}
	
	if ($priority === false) {
		$priority = $entity->time_created;
	}
	elgg_register_menu_item('quicklinks', ElggMenuItem::factory([
		'name' => $entity->getGUID(),
		'text' => elgg_view('quicklinks/item', ['entity' => $entity]),
		'href' => false,
		'priority' => $priority,
		'item_class' => 'clearfix elgg-discover elgg-border-plain pas mbs'
	]));
}

$content = elgg_view_menu('quicklinks', [
	'sort_by' => 'priority',
	'display_limit' => $limit
]);
if (empty($content) && elgg_in_context('widgets')) {
	$content = elgg_echo('notfound');
}

echo $content;
elgg_pop_context();

if (elgg_is_logged_in()) {
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	
	$class = 'alliander-theme-quicklinks-item';
	if (elgg_get_context() == 'widgets') {
		$class = 'elgg-widget-more';
	}
	echo "<div class='{$class}'>";
	echo elgg_view('output/url', [
		'text' => elgg_echo('quicklinks:add'),
		'href' => 'quicklinks/add/' . elgg_get_logged_in_user_guid(),
		'class' => 'elgg-lightbox',
	]);
	echo '</div>';
}
