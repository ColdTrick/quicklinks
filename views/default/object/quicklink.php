<?php
/**
 * Entity view for a quicklink
 *
 * @uses $vars["entity"]
 */

$entity = elgg_extract("entity", $vars);
$owner = $entity->getOwnerEntity();

$owner_icon = elgg_view_entity_icon($owner, "tiny");

// entity menu
$entity_menu = "";
if (!elgg_in_context("widgets")) {
	$entity_menu = elgg_view_menu("entity", array(
		"entity" => $entity,
		"handler" => "quicklinks",
		"sort_by" => "priority",
		"class" => "elgg-menu-hz",
	));
}

// subtitle
$owner_link = elgg_view("output/url", array(
	"href" => "quicklinks/owner/" . $owner->username,
	"text" => $owner->name,
	"is_trusted" => true,
));
$author_text = elgg_echo("byline", array($owner_link));
$date = elgg_view_friendly_time($entity->time_created);

$subtitle = $author_text . " " . $date;

// title
$title_text = $entity->description;
if (!empty($entity->title)) {
	$title_text = $entity->title;
}

$url = $entity->getURL();
$url_options = array(
	"text" => $title_text,
	"href" => $url
);
if (!stristr($url, elgg_get_site_url())) {
	$url_options["target"] = "_blank";
}

$title = elgg_view("output/url", $url_options);

$params = array(
	"entity" => $entity,
	"title" => $title,
	"metadata" => $entity_menu,
	"subtitle" => $subtitle,
);
$params = $params + $vars;
$summary = elgg_view("object/elements/summary", $params);

echo elgg_view_image_block($owner_icon, $summary);
