<?php

require_once(__DIR__ . '/lib/functions.php');

return [
	'bootstrap' => \ColdTrick\QuickLinks\Bootstrap::class,
	'entities' => [
		[
			'type' => 'object',
			'subtype' => 'quicklink',
			'class' => QuickLink::class,
		],
	],
	
	'routes' => [
		'add:object:quicklink' => [
			'path' => '/quicklinks/add/{guid?}',
			'resource' => 'quicklinks/add',
			'middleware' => [
				\Elgg\Router\Middleware\Gatekeeper::class,
			],
		],
	],
	'widgets' => [
		'quicklinks' => [
			'context' => ['index', 'dashboard'],
		],
	],
		
	'actions' => [
		'quicklinks/toggle' => [],
		'quicklinks/edit' => [],
		'quicklinks/delete' => [],
		'quicklinks/reorder' => [],
	],
];
