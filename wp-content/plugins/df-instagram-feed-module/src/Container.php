<?php
namespace DF\InstagramFeed;

use DiviFramework\UpdateChecker\PluginLicense;
use Pimple\Container as PimpleContainer;

/**
 * DI Container.
 */
class Container extends PimpleContainer {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initObjects();
	}

	/**
	 * Define dependancies.
	 */
	public function initObjects() {

		$this['activation'] = function ($container) {
			return new Activation($container);
		};

		$this['divi_modules'] = function ($container) {
			return new DiviModules($container);
		};

		$this['plugins'] = function ($container) {
			return new Plugins($container);
		};

		$this['themes'] = function ($container) {
			return new Themes($container);
		};

		$this['license'] = function ($container) {
			return new PluginLicense($container, 'https://www.diviframework.com');
		};

		//remove old licensing code.

	}

	/**
	 * Start the plugin
	 */
	public function run() {
		$this['license']->init(); // license init in plugin run.

		// divi module register.
		add_action('et_builder_ready', array($this['divi_modules'], 'register'), 1);

		// check for plugin dependancies.
		add_action('plugins_loaded', array($this['plugins'], 'checkDependancies'));
		add_action('plugins_loaded', array($this['themes'], 'checkDependancies'));
		add_action('admin_head', array($this, 'flushLocalStorage'));
	}

	/**
	 * Flush local storage items.
	 *
	 * @return [type] [description]
	 */
	public function flushLocalStorage() {
		echo "<script>" .
			"localStorage.removeItem('et_pb_templates_et_pb_df_custom_twitter_feed');" .
			"</script>";
	}
}
