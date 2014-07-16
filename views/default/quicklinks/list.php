<?php

$owner = elgg_extract("owner", $vars, elgg_get_logged_in_user_entity());
$limit = elgg_extract("limit", $vars, false);

if (empty($owner) || !elgg_instanceof($owner, "user")) {
	return;
}

$type_subtypes = get_registered_entity_types();
if (!is_array($type_subtypes)) {
	$type_subtypes = array();
}

// add quicklinks subtype
if (!isset($type_subtypes["object"])) {
	$type_subtypes["object"] = array();
}
if (!in_array(QUICKLINKS_SUBTYPE, $type_subtypes["object"])) {
	$type_subtypes["object"][] = QUICKLINKS_SUBTYPE;
}

$options = array(
	"type_subtype_pairs" => $type_subtypes,
	"limit" => false,
	"relationship" => QUICKLINKS_RELATIONSHIP,
	"relationship_guid" => $owner->getGUID(),
	"order_by" => "r.time_created DESC"
);
// @todo fix default time_created by adding an extra field in the select because of relationship created instead of time_created of related entity
elgg_push_context("quicklinks");
$configured_priorities = $owner->getPrivateSetting("quicklinks_order");
if ($configured_priorities) {
	$configured_priorities = json_decode($configured_priorities);
}
$entities = new ElggBatch("elgg_get_entities_from_relationship", $options);
if ($entities) {
	foreach ($entities as $index => $entity) {
		$priority = false;
		
		if (!empty($configured_priorities) && is_array($configured_priorities)) {
			$priority = array_search($entity->guid, $configured_priorities);
		}
		
		if ($priority === false) {
			$priority = $entity->time_created;
		}
		elgg_register_menu_item("quicklinks", ElggMenuItem::factory(array(
			"name" => $entity->guid,
			"text" => elgg_view("quicklinks/item", array("entity" => $entity)),
			"href" => false,
			"priority" => $priority,
			"item_class" => "clearfix elgg-discover elgg-border-plain pas mbs"
		)));
	}
	
	$content = elgg_view_menu("quicklinks", array("sort_by" => "priority", "display_limit" => $limit));
} else {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound")));
}

echo $content;
elgg_pop_context();

elgg_load_js("lightbox");
elgg_load_css("lightbox");

$class = "alliander-theme-quicklinks-item";
if (elgg_get_context() == "widgets") {
	$class = "elgg-widget-more";
}
echo "<div class='" . $class . "'>";
echo elgg_view("output/url", array(
		"text" => elgg_echo("quicklinks:add"),
		"href" => "quicklinks/add/" . elgg_get_logged_in_user_entity()->guid,
		"class" => "elgg-lightbox"
));
echo "</div>";