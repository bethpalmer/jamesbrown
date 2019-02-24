<?php

namespace DiviFramework\Hub;

/**
 * WordPress Menu Hook class.
 */
class Menu {
	protected $container;

	public function __construct($container) {
		$this->container = $container;
	}

	/**
	 * Register admin menu
	 */
	public function adminMenu() {
		add_menu_page($this->container['provider'], $this->container['provider'], 'manage_options', $this->container['dashboard_slug'], array($this->container['admin_dashboard'], 'view'), $this->container['provider_logo'], 2);
	}
}
