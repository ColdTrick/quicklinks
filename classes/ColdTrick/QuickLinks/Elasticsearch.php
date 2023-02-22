<?php

namespace ColdTrick\QuickLinks;

/**
 * Export information to Elasticsearch or OpenSearch
 */
class Elasticsearch {
	
	/**
	 * Export QuickLinks count
	 *
	 * @param \Elgg\Event $event 'export:counters', 'elasticsearch'|'opensearch'
	 *
	 * @return null|array
	 */
	public static function exportCounter(\Elgg\Event $event): ?array {
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return null;
		}
		
		$return = $event->getValue();
		
		$return['quicklinks'] = elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity) {
			return elgg_count_entities([
				'relationship' => \QuickLink::RELATIONSHIP,
				'relationship_guid' => $entity->guid,
				'inverse_relationship' => true,
			]);
		});
		
		return $return;
	}
}
