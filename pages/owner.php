<?php
/**
 * Show the quicklinks of a user
 */

$page_owner = elgg_get_page_owner_entity();
if (empty($page_owner) || !elgg_instanceof($page_owner, "user")) {
	register_error(elgg_echo("pageownerunavailable", array(elgg_get_page_owner_guid())));
	forward(REFERER);
}

// breadcrumb
elgg_push_breadcrumb(elgg_echo("quicklinks"));
elgg_push_breadcrumb($page_owner->name);

// title button
elgg_register_title_button();

// build page elements
$title_text = elgg_echo("quicklinks:owner:title", array($page_owner->name));

$content = elgg_view("quicklinks/list", array("owner" => $page_owner));

// build page
$page_data = elgg_view_layout("content", array(
	"title" => $title_text,
	"content" => $content,
	"filter" => ""
));

// draw page
echo elgg_view_page($title_text, $page_data);
