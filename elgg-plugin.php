<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'version' => '6.0',
	],
	'settings' => [
		'add_to_site_menu' => 0,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'quicklink',
			'class' => QuickLink::class,
			'capabilities' => [
				'commentable' => false,
			],
		],
	],
	'routes' => [
		'add:object:quicklink' => [
			'path' => '/quicklinks/add/{guid}',
			'resource' => 'quicklinks/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
				\Elgg\Router\Middleware\UserPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'actions' => [
		'quicklinks/toggle' => [],
		'quicklinks/edit' => [],
		'quicklinks/delete' => [],
		'quicklinks/reorder' => [],
	],
	'events' => [
		'export:counters' => [
			'elasticsearch' => [
				'ColdTrick\QuickLinks\Elasticsearch::exportCounter' => [],
			],
			'opensearch' => [
				'ColdTrick\QuickLinks\Elasticsearch::exportCounter' => [],
			],
		],
		'register' => [
			'menu:site' => [
				'ColdTrick\QuickLinks\SiteMenu::register' => [],
			],
			'menu:entity' => [
				'ColdTrick\QuickLinks\EntityMenu::register' => [],
			],
			'menu:quicklinks' => [
				'ColdTrick\QuickLinks\QuickLinksMenu::register' => [],
			],
		],
	],
	'view_extensions' => [
		'elgg.css' => [
			'quicklinks.css' => [],
		],
	],
	'widgets' => [
		'quicklinks' => [
			'context' => ['index', 'dashboard'],
		],
	],
];
