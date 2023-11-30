<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'version' => '7.0.2',
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
			'menu:entity' => [
				'ColdTrick\QuickLinks\Menus\Entity::register' => [],
			],
			'menu:quicklinks' => [
				'ColdTrick\QuickLinks\Menus\QuickLinks::register' => [],
			],
			'menu:site' => [
				'ColdTrick\QuickLinks\Menus\Site::register' => [],
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
