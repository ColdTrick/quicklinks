<?php

/**
 * QuickLink entity class
 */
class QuickLink extends ElggObject {
	
	const SUBTYPE = 'quicklink';

	const RELATIONSHIP = 'quicklinks';
	
	/**
	 * {@inheritDoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getURL(): string {
		return elgg_normalize_url($this->description);
	}
}
