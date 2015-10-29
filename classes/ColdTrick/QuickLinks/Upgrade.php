<?php

namespace ColdTrick\QuickLinks;

class Upgrade {
	
	/**
	 * Make sure the class handler for QuickLink is correct
	 *
	 * @param string $event  the name of the event
	 * @param string $type   the type of the event
	 * @param mixed  $object misc params
	 *
	 * @return void
	 */
	public static function setClassHandler($event, $type, $object) {
		
		// set correct class handler for QuickLink
		if (get_subtype_id('object', \QuickLink::SUBTYPE)) {
			update_subtype('object', \QuickLink::SUBTYPE, 'QuickLink');
		} else {
			add_subtype('object', \QuickLink::SUBTYPE, 'QuickLink');
		}
	}
}
