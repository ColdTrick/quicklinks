<?php

$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
$ordered_guids = get_input('guids', '');

$container = get_entity($container_guid);
if (!empty($container)) {
	$container->setPrivateSetting('quicklinks_order', json_encode($ordered_guids));
}
