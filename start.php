<?php
/**
 * Main plugin file
 */

define("QUICKLINKS_RELATIONSHIP", "quicklinks");
define("QUICKLINKS_SUBTYPE", "quicklink");

require_once(dirname(__FILE__) . "/lib/functions.php");
require_once(dirname(__FILE__) . "/lib/hooks.php");
require_once(dirname(__FILE__) . "/lib/page_handlers.php");

// register default Elgg events
elgg_register_event_handler("init", "system", "quicklinks_init");

/**
 * This function gets called on the 'init' 'system' event
 *
 * @return void
 */
function quicklinks_init() {
	// extend JS/CSS
	elgg_extend_view("js/elgg", "js/quicklinks/site");
	elgg_extend_view("css/elgg", "css/quicklinks/site");
	
	// register page handler for nice urls
	elgg_register_page_handler("quicklinks", "quicklink_page_handler");
	
	// register widget
	elgg_register_widget_type("quicklinks", elgg_echo("quicklinks:widget:title"), elgg_echo("quicklinks:widget:description"), "index,dashboard");
	
	// register plugin hooks
	elgg_register_plugin_hook_handler("register", "menu:entity", "quicklinks_register_entity_menu_hook");
	elgg_register_plugin_hook_handler("prepare", "menu:quicklinks", "quicklinks_prepare_quicklinks_menu_hook");
	elgg_register_plugin_hook_handler("entity:url", "object", "quicklinks_url_handler");
	elgg_register_plugin_hook_handler("permissions_check:annotate", "object", "quicklinks_permissions_check_annotate_hook");
	
	// register actions
	elgg_register_action("quicklinks/toggle", dirname(__FILE__) . "/actions/toggle.php");
	elgg_register_action("quicklinks/edit", dirname(__FILE__) . "/actions/edit.php");
	elgg_register_action("quicklinks/delete", dirname(__FILE__) . "/actions/delete.php");
	elgg_register_action("quicklinks/reorder", dirname(__FILE__) . "/actions/reorder.php");
}
