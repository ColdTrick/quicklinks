<?php

namespace ColdTrick\QuickLinks;

class Elasticsearch {
	
	/**
	 * Export quicklinks count
	 *
	 * @param \Elgg\Hook $hook 'export:counters', 'elasticsearch'|'opensearch'
	 *
	 * @return void|array
	 */
	public static function exportCounter(\Elgg\Hook $hook) {
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$return = $hook->getValue();
		
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
