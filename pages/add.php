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

// breadcrumb
elgg_push_breadcrumb(elgg_echo('quicklinks'));
elgg_push_breadcrumb($page_owner->name, "quicklinks/owner/{$page_owner->username}");
elgg_push_breadcrumb(elgg_echo('add'));

// build page elements
$title_text = elgg_echo('quicklinks:add:title');

$content = elgg_view_form('quicklinks/edit', [], ['container' => $page_owner]);

if (elgg_is_xhr()) {
	echo '<div id="quicklinks-add-lightbox-wrapper">';
	echo elgg_view_title($title_text);
	echo $content;
	echo '</div>';
} else {
	// build page
	$page_data = elgg_view_layout('content', [
		'title' => $title_text,
		'content' => $content,
		'filter' => ''
	]);
	
	// draw page
	echo elgg_view_page($title_text, $page_data);
}
