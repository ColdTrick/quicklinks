<?php
/**
 * Javascript appended to the global Elgg JS
 */

?>
//<script>
elgg.provide("elgg.quicklinks");

elgg.quicklinks.init = function() {
	$("li.elgg-menu-item-quicklinks > a").on("click", function(e) {
		e.preventDefault();

		$elm = $(this);
		
		elgg.action($(this).attr("href"), function(data) {
			if ($elm.find(".elgg-icon-star-empty").length) {
				$elm.find(".elgg-icon-star-empty").removeClass("elgg-icon-star-empty").addClass("elgg-icon-star-hover");
			} else {
				$elm.find(".elgg-icon-star-hover").removeClass("elgg-icon-star-hover").addClass("elgg-icon-star-empty");
			}
		});
	});

	$(".elgg-menu-quicklinks").sortable({ 
		containment: "parent",
		handle: ".elgg-icon-cursor-drag-arrow",
		update: function() {
			elgg.action('quicklinks/reorder?' + $(this).sortable('serialize', {attribute: "class", key: "guids[]", expression: "elgg-menu-item-(.+) clearfix elgg-discover elgg-border-plain pas mbs"}));
		}
	});
};

elgg.register_hook_handler("init", "system", elgg.quicklinks.init);