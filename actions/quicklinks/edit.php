<?php
/**
 * Save/edit a quicklink items
 *
 * @todo support edit
 * @todo sticky form
 */

$title = get_input('title');
$url = get_input('url');
$container_guid = (int) get_input('container_guid');

if (empty($url)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$entity = new \QuickLink();
$entity->container_guid = $container_guid;

$entity->title = $title;
$entity->description = $url;

if (!$entity->save()) {
	return elgg_error_response(elgg_echo('save:fail'));
}

add_entity_relationship(elgg_get_logged_in_user_guid(), \QuickLink::RELATIONSHIP, $entity->guid);

return elgg_ok_response(elgg_echo('save:success'));
