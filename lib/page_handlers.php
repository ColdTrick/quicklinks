<?php
/**
 * All page handler functions are bundled here
 */

/**
 * The page handler for /quicklinks
 *
 * @param array $page the url parts
 *
 * @return bool
 */
function quicklink_page_handler($page) {
	$result = false;
	$include_file = "";
	
	switch ($page[0]) {
		case "owner":
			$include_file = dirname(dirname(__FILE__)) . "/pages/owner.php";
			break;
		case "add":
			$include_file = dirname(dirname(__FILE__)) . "/pages/add.php";
			break;
	}
	
	if (!empty($include_file)) {
		include($include_file);
		$result = true;
	}
	
	return $result;
}