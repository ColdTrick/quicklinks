<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'name' => 'QuickLinks',
		'version' => '8.0.1',
	],
	'settings' => [
		'add_to_site_menu' => 0,
	],
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'quicklink',
			'class' => \QuickLink::class,
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
			'opensearch' => [
				'ColdTrick\QuickLinks\Plugins\OpenSearch::exportCounter' => [],
			],
		],
		'prepare' => [
			'menu:site' => [
				'ColdTrick\QuickLinks\Menus\Site::prepareQuickLinksOrdering' => [],
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
			'quicklinks/site.css' => [],
		],
	],
	'widgets' => [
		'quicklinks' => [
			'context' => ['index', 'dashboard'],
		],
	],
];
