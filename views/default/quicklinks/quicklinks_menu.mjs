import 'jquery';
import 'jquery-ui';
import Ajax from 'elgg/Ajax';

$('#quicklinks, .elgg-menu-site .elgg-menu-item-quicklinks > .elgg-child-menu, .elgg-menu-quicklinks').sortable({
	containment: 'parent',
	items: 'li:not(.elgg-menu-item-add)',
	update: function() {
		var ajax = new Ajax();
		ajax.action('quicklinks/reorder?' + $(this).sortable('serialize', {
			attribute: 'class',
			key: 'guids[]',
			expression: /.*elgg-menu-item-(\d+).*/
		}));
	}
});

$(document).on('click', '#quicklinks .elgg-icon-delete, .elgg-menu-site .elgg-menu-item-quicklinks > .elgg-child-menu .elgg-icon-delete, .elgg-menu-quicklinks .elgg-icon-delete', function(event) {
	event.preventDefault();
	
	var $menu_item = $(this).closest('li');
	var $anchor = $menu_item.find('> a');
	$menu_item.hide();
	
	var ajax = new Ajax();
	ajax.action($anchor.data().deleteAction, {
		success: function() {
			$menu_item.remove();
		},
		error: function() {
			$menu_item.show();
		}
	});
});
