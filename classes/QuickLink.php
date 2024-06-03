<?php

/**
 * QuickLink entity class
 *
 * @property string $description URL of the quicklink
 */
class QuickLink extends \ElggObject {
	
	const SUBTYPE = 'quicklink';
	const RELATIONSHIP = 'quicklinks';
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$this->attributes['subtype'] = self::SUBTYPE;
		$this->attributes['access_id'] = ACCESS_PRIVATE;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getURL(): string {
		return elgg_normalize_url($this->description);
	}
}
