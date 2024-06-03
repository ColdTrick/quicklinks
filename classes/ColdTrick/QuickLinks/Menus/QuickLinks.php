<?php

namespace ColdTrick\QuickLinks\Menus;

use Elgg\Database\Clauses\OrderByClause;
use Elgg\Menu\MenuItems;

/**
 * Add menu items to the quicklinks menu
 */
class QuickLinks {
	
	/**
	 * Registers QuickLinks menu items
	 *
	 * @param \Elgg\Event $event 'regsiter', 'menu:quicklinks'
	 *
	 * @return null|MenuItems
	 */
	public static function register(\Elgg\Event $event): ?MenuItems {
		$owner = $event->getParam('owner', elgg_get_logged_in_user_entity());
		if (!$owner instanceof \ElggUser) {
			return null;
		}
		
		/* @var $result MenuItems */
		$result = $event->getValue();
		
		$entities = elgg_get_entities([
			'type_subtype_pairs' => self::getTypeSubtypePairs(),
			'limit' => false,
			'relationship' => \QuickLink::RELATIONSHIP,
			'relationship_guid' => $owner->guid,
			'order_by' => new OrderByClause('r.time_created', 'DESC'),
		]);
		
		$configured_priorities = $owner->quicklinks_order;
		if (!empty($configured_priorities)) {
			$configured_priorities = json_decode($configured_priorities, true);
		}
		
		$can_edit = $owner->canEdit();
		
		foreach ($entities as $entity) {
			$priority = $entity->time_created;
			
			if (!empty($configured_priorities) && is_array($configured_priorities)) {
				$priority = array_search($entity->guid, $configured_priorities);
			}
			
			$delete_action = elgg_generate_action_url('quicklinks/toggle', [
				'guid' => $entity->guid,
			]);
			
			if ($entity instanceof \QuickLink) {
				$delete_action = elgg_generate_action_url('quicklinks/delete', [
					'guid' => $entity->guid,
				]);
			}
			
			$result[] = \ElggMenuItem::factory([
				'name' => $entity->guid,
				'text' => $entity->getDisplayName() ?: elgg_echo('unknown'),
				'href' => $entity->getURL(),
				'icon_alt' => $can_edit ? 'delete' : null,
				'priority' => $priority,
				'deps' => ['quicklinks/quicklinks_menu'],
				'data-delete-action' => $delete_action,
			]);
		}
		
		return $result;
	}
	
	/**
	 * Returns type_subtype pairs for use in elgg_get_entities when fetching quicklink related entities
	 *
	 * @return array
	 */
	protected static function getTypeSubtypePairs(): array {
		$type_subtypes = quicklinks_get_supported_types();
		
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
