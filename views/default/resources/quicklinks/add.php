<?php
/**
 * add a new QuickLink
 */

elgg_gatekeeper();

$page_owner = elgg_get_page_owner_entity();
if (!($page_owner instanceof ElggUser)) {
	register_error(elgg_echo('pageownerunavailable', [elgg_get_page_owner_guid()]));
	forward(REFERER);
}

// build page elements
$title_text = elgg_echo('quicklinks:add:title');

$content = elgg_view_form('quicklinks/edit', [], ['container' => $page_owner]);

if (elgg_is_xhr()) {
	echo elgg_view_module('inline', $title_text, $content);
} else {
	// build page
	$page_data = elgg_view_layout('content', [
		'title' => $title_text,
		'content' => $content,
		'filter' => '',
	]);
	
	// draw page
	echo elgg_view_page($title_text, $page_data);
}
