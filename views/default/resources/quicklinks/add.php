<?php
/**
 * add a new QuickLink
 */

use Elgg\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser) {
	throw new EntityNotFoundException(elgg_echo('pageownerunavailable', [elgg_get_page_owner_guid()]));
}

// build page elements
$title_text = elgg_echo('quicklinks:add:title');

$content = elgg_view_form('quicklinks/edit', [], ['container' => $page_owner]);

// draw page
if (elgg_is_xhr()) {
	// light version for lightbox
	echo elgg_view_module('inline', $title_text, $content);
	return;
}

// full page
echo elgg_view_page($title_text, [
	'content' => $content,
]);
