<?php

namespace DiviFramework\Hub;

/**
 * Activation class.
 */
class Activation {

	protected $container;

	public function __construct($container) {
		$this->container = $container;
	}

	/**
	 * Plugin activation.
	 */
	public function install() {
		if (!wp_next_scheduled('refresh_jwt_token')) {
			wp_schedule_event(time(), 'every_six_days', 'refresh_jwt_token');
		}

		$this->container['license']->init();
		//Custom Post Types
		$this->container['custom_posts']->register();
		flush_rewrite_rules();
	}
}
