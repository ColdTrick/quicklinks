<?php

// set correct class handler for QuickLink
if (get_subtype_id('object', QuickLink::SUBTYPE)) {
	update_subtype('object', QuickLink::SUBTYPE, 'QuickLink');
} else {
	add_subtype('object', QuickLink::SUBTYPE, 'QuickLink');
}
