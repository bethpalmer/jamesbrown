<?php

namespace DiviFramework\Hub;

/**
 * Class defines custom post types.
 */
class CustomPosts {

	protected $container;

	public function __construct($container) {
		$this->container = $container;
	}

	// register your custom post and taxonomy here.
	public function register() {
		$postsDir = $this->container['plugin_dir'] . '/post-types';
		$taxonomiesDir = $this->container['plugin_dir'] . '/taxonomies';

		$this->includeFiles($postsDir);
		$this->includeFiles($taxonomiesDir);
	}

	/**
	 * Include files from the directory.
	 */
	public function includeFiles($dir) {
		if (is_dir($dir)) {
			foreach (glob($dir . "/*.php") as $file) {
				include_once $file;
			}
		}
	}
}
