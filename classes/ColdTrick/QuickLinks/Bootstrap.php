<?php

namespace ColdTrick\QuickLinks;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
					
		// register plugin hooks
		elgg_register_plugin_hook_handler('register', 'menu:site', '\ColdTrick\QuickLinks\SiteMenu::register');
		elgg_register_plugin_hook_handler('register', 'menu:entity', '\ColdTrick\QuickLinks\EntityMenu::register');
		elgg_register_plugin_hook_handler('register', 'menu:quicklinks', '\ColdTrick\QuickLinks\QuickLinksMenu::register');
	}
}
