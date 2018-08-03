define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	$('.elgg-menu-site .elgg-menu-item-quicklinks > .elgg-child-menu, .elgg-menu-quicklinks').sortable({
		containment: 'parent',
		items: 'li:not(.elgg-menu-item-add)',
		update: function() {
			elgg.action('quicklinks/reorder?' + $(this).sortable('serialize', {
				attribute: 'class',
				key: 'guids[]',
				expression: /.*elgg-menu-item-(\d+).*/
			}));
		}
	});
});
