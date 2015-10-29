<?php

// set class handler to default Elgg handling
if (get_subtype_id('object', QuickLink::SUBTYPE)) {
	update_subtype('object', QuickLink::SUBTYPE);
}
