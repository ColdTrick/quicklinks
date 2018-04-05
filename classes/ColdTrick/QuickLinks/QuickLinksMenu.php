<?php

namespace ColdTrick\QuickLinks;

use Elgg\Database\Clauses\OrderByClause;

class QuickLinksMenu {
	
	/**
	 * Registers QuickLinks menu items
	 *
	 * @param \Elgg\Hook $hook hook
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function register(\Elgg\Hook $hook) {
		$owner = $hook->getParam('owner', elgg_get_logged_in_user_entity());
		if (!$owner instanceof \ElggUser) {
			return;
		}
		
		$result = $hook->getValue();
		
		$entities = elgg_get_entities([
			'type_subtype_pairs' => self::getTypeSubtypePairs(),
			'limit' => $hook->getParam('limit', false),
			'relationship' => \QuickLink::RELATIONSHIP,
			'relationship_guid' => $owner->guid,
			'order_by' => new OrderByClause('r.time_created', 'DESC'),
		]);
		
		$configured_priorities = $owner->getPrivateSetting('quicklinks_order');
		if ($configured_priorities) {
			$configured_priorities = json_decode($configured_priorities);
		}
		
		foreach ($entities as $entity) {
			$priority = $entity->time_created;
			
			if (!empty($configured_priorities) && is_array($configured_priorities)) {
				$priority = array_search($entity->guid, $configured_priorities);
			}
			
// 			$delete_options = [
// 				'text' => elgg_view_icon('delete'),
// 				'href' => "action/quicklinks/toggle?guid={$entity->getGUID()}",
// 				'is_action' => true,
// 			];
// 			if ($entity instanceof QuickLink) {
// 				$delete_options['href'] = "action/quicklinks/delete?guid={$entity->getGUID()}";
// 			}

			$result[] = \ElggMenuItem::factory([
				'name' => $entity->guid,
				'text' => $entity->getDisplayName(),
				'href' => $entity->getURL(),
// 				'icon' => 'cursor-drag-arrow',
				'icon_alt' => 'delete',
				'priority' => $priority,
			]);
		}
		
		if ($owner->guid == elgg_get_logged_in_user_guid()) {
			$result[] = \ElggMenuItem::factory([
				'name' => 'add',
				'section' => 'more',
				'icon' => 'plus',
				'text' => elgg_echo('quicklinks:add'),
				'href' => elgg_generate_url('add:object:quicklink', ['guid' => elgg_get_logged_in_user_guid()]),
				'class' => 'elgg-lightbox',
			]);
		}
		
		return $result;
	}
	
	/**
	 * Returns type_subtype pairs for use in elgg_get_entities when fetching quicklink related entities
	 *
	 * @return []
	 */
	protected static function getTypeSubtypePairs() {
		$type_subtypes = quicklinks_get_supported_types();
		if (!is_array($type_subtypes)) {
			$type_subtypes = [];
		}
		
		// add quicklinks subtype
		if (!isset($type_subtypes['object'])) {
			$type_subtypes['object'] = [];
		}
		if (!in_array(\QuickLink::SUBTYPE, $type_subtypes['object'])) {
			$type_subtypes['object'][] = \QuickLink::SUBTYPE;
		}
		
		return $type_subtypes;
	}
}
