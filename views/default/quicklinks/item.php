<?php
$entity = $vars["entity"];

$delete_options = array(
	"text" => elgg_view_icon("delete"),
	"href" => "action/quicklinks/toggle?guid=" . $entity->getGUID(),
	"is_action" => true
);
if (elgg_instanceof($entity, "object", QUICKLINKS_SUBTYPE)) {
	$delete_options["href"] = "action/quicklinks/delete?guid=" . $entity->getGUID();
}

$move_options = array(
	"text" => elgg_view_icon("cursor-drag-arrow"),
	"title" => elgg_echo("quicklinks:move"),
	"href" => "#",
);

$content = "<div class='elgg-discoverable float-alt hidden'>";

$content .= elgg_view("output/url", $move_options);
$content .= elgg_view("output/url", $delete_options);
$content .= "</div>";

$content .= elgg_view("output/url", array("text" => $entity->title, "href" => $entity->getURL()));

echo $content;
