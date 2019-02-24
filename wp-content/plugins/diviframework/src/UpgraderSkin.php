<?php

namespace DiviFramework\Hub;

use WP_Upgrader_Skin;

/**
 * A Upgrader Skin for WordPress that only generates plain-text
 *
 * @package wp-cli
 */
class UpgraderSkin extends WP_Upgrader_Skin {

	public $api;

	protected $container;

	public function __construct($container) {
		$this->container = $container;
		parent::__construct();
	}

	public function header() {}
	public function footer() {}
	public function bulk_header() {}
	public function bulk_footer() {}

	public function error($error) {
		if (!$error) {
			return;
		}

		if (is_string($error) && isset($this->upgrader->strings[$error])) {
			$error = $this->upgrader->strings[$error];
		}

		$this->container['admin']->show_notice($error, 'error');
	}

	public function feedback($string) {
		if (isset($this->upgrader->strings[$string])) {
			$string = $this->upgrader->strings[$string];
		}

		if (strpos($string, '%') !== false) {
			$args = func_get_args();
			$args = array_splice($args, 1);
			if (!empty($args)) {
				$string = vsprintf($string, $args);
			}
		}

		if (empty($string)) {
			return;
		}

		$string = str_replace('&#8230;', '...', strip_tags($string));
		$string = html_entity_decode($string, ENT_QUOTES, get_bloginfo('charset'));

		$this->container['admin']->show_notice($string, 'info');
	}
}
