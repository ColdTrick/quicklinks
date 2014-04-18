<?php

$owner = elgg_extract("owner", $vars, elgg_get_logged_in_user_entity());
$limit = (int) elgg_extract("limit", $vars, 10);
$pagination = (bool) elgg_extract("pagination", $vars, true);

if (empty($owner) || !elgg_instanceof($owner, "user")) {
	return;
}

if ($limit < 1) {
	$limit = 10;
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
	"limit" => $limit,
	"relationship" => QUICKLINKS_RELATIONSHIP,
	"relationship_guid" => $owner->getGUID(),
	"order_by" => "r.time_created DESC",
	"pagination" => $pagination
);

$content = elgg_list_entities_from_relationship($options);
if (empty($content)) {
	$content = elgg_view("output/longtext", array("value" => elgg_echo("notfound")));
}

echo $content;
