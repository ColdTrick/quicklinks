<?php
/**
 * Add/edit a quicklink
 *
 * @todo support edit
 * @todo sticky form
 */

$container = elgg_extract('container', $vars);
if (!($container instanceof ElggEntity)) {
	return;
}

echo '<div>';
echo elgg_format_element('label', ['for' => 'quicklinks-add-title'], elgg_echo('title') . '*');
echo elgg_view('input/text', [
	'name' => 'title',
	'id' => 'quicklinks-add-title',
	'required' => true,
]);
echo '</div>';

echo '<div>';
echo elgg_format_element('label', ['for' => 'quicklinks-add-url'], elgg_echo('quicklinks:edit:url') . '*');
echo elgg_view('input/url', [
	'name' => 'url',
	'id' => 'quicklinks-add-url',
	'required' => true,
]);
echo '</div>';

echo elgg_format_element('div', ['class' => 'elgg-subtext float-alt'], elgg_echo('quicklinks:edit:required'));

echo '<div class="elgg-foot">';
echo elgg_view('input/hidden', [
	'name' => 'container_guid',
	'value' => $container->getGUID(),
]);
echo elgg_view('input/submit', ['value' => elgg_echo('save')]);
echo '</div>';
