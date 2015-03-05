<?php
/**
 * CSS extended to the Elgg site CSS
 */
?>
#quicklinks-add-lightbox-wrapper {
	width: 500px;
	overflow: hidden;
}

.elgg-menu-quicklinks .elgg-discoverable {
	position: absolute;
	right: 5px;
}

.elgg-menu-quicklinks li {
	background: #FFF;
}

.elgg-widget-instance-quicklinks:hover .elgg-menu-quicklinks > li.hidden {
	display: block;
}

.elgg-menu-item-quicklinks.hidden,
.elgg-menu-item-quicklinks-remove.hidden {
	display: none;
}