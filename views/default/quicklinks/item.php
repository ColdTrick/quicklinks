<?php

$entity = elgg_extract('entity', $vars);
if (!($entity instanceof ElggEntity)) {
	return;
}

$delete_options = [
	'text' => elgg_view_icon('delete'),
	'href' => "action/quicklinks/toggle?guid={$entity->getGUID()}",
	'is_action' => true,
];
if ($entity instanceof QuickLink) {
	$delete_options['href'] = "action/quicklinks/delete?guid={$entity->getGUID()}";
}

$move_options = [
	'text' => elgg_view_icon('cursor-drag-arrow'),
	'title' => elgg_echo('quicklinks:move'),
	'href' => '#',
];

$text = null;
if ($entity->title) {
	$text = $entity->title;
} elseif ($entity->name) {
	$text = $entity->name;
}

$content = '<div class="elgg-discoverable float-alt hidden">';
$content .= elgg_view('output/url', $move_options);
$content .= elgg_view('output/url', $delete_options);
$content .= '</div>';

$content .= elgg_view('output/url', [
	'text' => $text,
	'href' => $entity->getURL(),
]);

echo $content;
