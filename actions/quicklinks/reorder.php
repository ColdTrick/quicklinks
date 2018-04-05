<?php

$container_guid = (int) get_input('container_guid', elgg_get_logged_in_user_guid());
$ordered_guids = get_input('guids', '');

if (get_entity($container_guid)) {
	set_private_setting($container_guid, 'quicklinks_order', json_encode($ordered_guids));
}
