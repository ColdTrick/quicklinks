<?php

class QuickLink extends ElggObject {
	
	const SUBTYPE = 'quicklink';
	
	/**
	 * (non-PHPdoc)
	 * @see ElggObject::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see ElggEntity::getURL()
	 */
	public function getURL() {
		return elgg_normalize_url($this->description);
	}
	
	
}
