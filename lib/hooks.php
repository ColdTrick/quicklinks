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
			
			$black_listed_entity_subtypes = array("discussion_reply", "comment");

			if (!in_array($entity->getSubtype(), $black_listed_entity_subtypes) && !empty($registered_entity_types) && is_array($registered_entity_types) && in_array($entity->getSubtype(), $registered_entity_types)) {
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
 * Prepares the quicklinks menu items to optionally hide some items
 *
 * @param string         $hook        'prepare'
 * @param string         $type        'menu:quiclinks'
 * @param ElggMenuItem[] $returnvalue the current menu items
 * @param array          $params      provided params
 *
 * @return ElggMenuItem[]
 */
function quicklinks_prepare_quicklinks_menu_hook($hook, $type, $returnvalue, $params) {
	
	if (!empty($params) && is_array($params)) {
		$display_limit = (int) elgg_extract("display_limit", $params, 0);
		if ($display_limit > 0 && elgg_in_context("widgets")) {
			foreach($returnvalue["default"] as $index => $item) {
				if ($index >= $display_limit) {
					$item->addItemClass("hidden");
				}
			}
		}
		$entity = elgg_extract("entity", $params);
		
		
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
