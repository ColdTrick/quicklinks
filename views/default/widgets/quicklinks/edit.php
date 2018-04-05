<?php
/**
 * some settings for the QuickLinks widget
 */

echo elgg_view('object/widget/edit/num_display', [
	'entity' => elgg_extract('entity', $vars),
	'default' => 5,
	'max' => 10,
]);
