<?php
/**
 * All plugin hook callback functions are bundled here
 */

/**
 * Add menu items to the entity menu
 *
 * @param string         $hook        'register'
 * @param string         $type        'menu:entity'
 * @param ElggMenuItem[] $returnvalue the current menu items
 * @param array          $params      provided params
 *
 * @return ElggMenuItem[]
 */
function quicklinks_register_entity_menu_hook($hook, $type, $returnvalue, $params) {
	
	if (!empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);
		
		if (!empty($entity) && elgg_instanceof($entity)) {
			// add quicklink toggle to registered entity types
			$registered_entity_types = get_registered_entity_types($entity->getType());
			
			if (!empty($registered_entity_types) && is_array($registered_entity_types) && in_array($entity->getSubtype(), $registered_entity_types)) {
				$icon = elgg_view_icon("star-empty");
				if (check_entity_relationship(elgg_get_logged_in_user_guid(), QUICKLINKS_RELATIONSHIP, $entity->getGUID())) {
					$icon = elgg_view_icon("star-hover");
				}
				
				$returnvalue[] = ElggMenuItem::factory(array(
					"name" => "quicklinks",
					"text" => $icon,
					"href" => "action/quicklinks/toggle?guid=" . $entity->getGUID(),
					"title" => elgg_echo("quicklinks:menu:entity:title"),
					"is_action" => true
				));
			}
			
			// remove menu items from our own subtype
			// @todo support edit
			if (elgg_instanceof($entity, "object", QUICKLINKS_SUBTYPE)) {
				$allowed_items = array("delete");
				
				foreach ($returnvalue as $index => $menu_item) {
					if (!in_array($menu_item->getName(), $allowed_items)) {
						unset($returnvalue[$index]);
					}
				}
			}
		}
	}
	
	return $returnvalue;
}

/**
 * Edit menu items in the title menu
 *
 * @param string         $hook        'register'
 * @param string         $type        'menu:title'
 * @param ElggMenuItem[] $returnvalue the current menu items
 * @param array          $params      provided params
 *
 * @return ElggMenuItem[]
 */
function quicklinks_register_title_menu_hook($hook, $type, $returnvalue, $params) {
	
	if (elgg_in_context("quicklinks") && !empty($returnvalue) && is_array($returnvalue)) {
		
		foreach ($returnvalue as $menu_item) {
			if ($menu_item->getName() == "add") {
				elgg_load_js("lightbox");
				elgg_load_css("lightbox");
				
				$menu_item->addLinkClass("elgg-lightbox");
			}
		}
	}
	
	return $returnvalue;
}

/**
 * Add an url to a widget title (requires Widget Manager)
 *
 * @param string $hook        'entity:url'
 * @param string $type        'object'
 * @param string $returnvalue the current link (if any)
 * @param array  $params      provided params
 *
 * @return string
 */
function quicklinks_widget_url_handler($hook, $type, $returnvalue, $params) {
	
	if (empty($returnvalue) && !empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);
		
		if (!empty($entity) && elgg_instanceof($entity, "object", "widget")) {
			if ($entity->handler == "quicklinks") {
				$owner = $entity->getOwnerEntity();
				if (!elgg_instanceof($owner, "user")) {
					$owner = elgg_get_logged_in_user_entity();
				}
				
				if (!empty($owner)) {
					$returnvalue = "quicklinks/owner/" . $owner->username;
				}
			}
		}
	}
	
	return $returnvalue;
}

/**
 * Configure the correct URL for our own custom subtype
 *
 * @param string $hook        'entity:url'
 * @param string $type        'object'
 * @param string $returnvalue the current link (if any)
 * @param array  $params      provided params
 *
 * @return string
 */
function quicklinks_url_handler($hook, $type, $returnvalue, $params) {
	
	if (empty($returnvalue) && !empty($params) && is_array($params)) {
		$entity = elgg_extract("entity", $params);
		
		if (!empty($entity) && elgg_instanceof($entity, "object", QUICKLINKS_SUBTYPE)) {
			$returnvalue = $entity->description;
		}
	}
	
	return $returnvalue;
}
