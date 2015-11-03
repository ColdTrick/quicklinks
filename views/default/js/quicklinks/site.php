<?php
/**
 * Javascript appended to the global Elgg JS
 */

?>
//<script>
elgg.provide("elgg.quicklinks");

elgg.quicklinks.sortable_init = function() {
	
	$(".elgg-menu-quicklinks").sortable({
		containment: "parent",
		handle: ".elgg-icon-cursor-drag-arrow",
		update: function() {
			elgg.action('quicklinks/reorder?' + $(this).sortable('serialize', {
				attribute: "class",
				key: "guids[]",
				expression: "elgg-menu-item-(.+) clearfix elgg-discover elgg-border-plain pas mbs"
			}));
		}
	});
};

elgg.quicklinks.init = function() {
	
	elgg.ui.registerTogglableMenuItems("quicklinks", "quicklinks-remove");
	
	elgg.quicklinks.sortable_init();
};

elgg.register_hook_handler("init", "system", elgg.quicklinks.init);
