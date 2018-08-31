<?php

namespace ColdTrick\QuickLinks;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {

	/**
	 * {@inheritdoc}
	 */
	public function init() {
		elgg_extend_view('elgg.css', 'quicklinks.css');
		
		// register plugin hooks
		$hooks = $this->elgg()->hooks;
		$hooks->registerHandler('export:counters', 'elasticsearch', __NAMESPACE__ . '\Elasticsearch::exportCounter');
		$hooks->registerHandler('register', 'menu:site', __NAMESPACE__ . '\SiteMenu::register');
		$hooks->registerHandler('register', 'menu:entity', __NAMESPACE__ . '\EntityMenu::register');
		$hooks->registerHandler('register', 'menu:quicklinks', __NAMESPACE__ . '\QuickLinksMenu::register');
	}
}
