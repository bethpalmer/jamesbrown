<?php
namespace DiviFramework\Hub;

use DiviFramework\UpdateChecker\PluginLicense;
use Pimple\Container as PimpleContainer;
use Plugin_Upgrader;

/**
 * DI Container.
 */
class Container extends PimpleContainer {
	public static $instance;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initObjects();
	}

	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Container;
		}

		return self::$instance;
	}

	/**
	 * Define dependancies.
	 */
	public function initObjects() {
		$this['provider'] = function ($container) {
			return 'Divi Framework';
		};

		$this['provider_logo'] = function ($container) {
			return $this['plugin_url'] . '/resources/images/df-logo.png';
		};

		$this['provider_base_url'] = function ($container) {
			return 'https://www.diviframework.com';
		};

		$this['provider_my_account_uri'] = function ($container) {
			return '/my-account/';
		};

		$this['dashboard_slug'] = function ($container) {
			return 'diviframework-hub';
		};

		$this['dashboard_page'] = function ($container) {
			return admin_url('/admin.php?page=' . $container['dashboard_slug']);
		};

		$this['cron'] = function ($container) {
			return new Cron($container, 'df-token');
		};

		$this['user_account'] = function ($container) {
			return new UserAccount($container, 'df-token');
		};

		$this['admin_dashboard'] = function ($container) {
			return new Admin\Dashboard($container);
		};

		$this['custom_posts'] = function ($container) {
			return new CustomPosts($container);
		};

		$this['activation'] = function ($container) {
			return new Activation($container);
		};

		$this['shortcodes'] = function ($container) {
			return new Shortcodes($container);
		};

		$this['admin'] = function ($container) {
			return new Admin($container);
		};

		$this['menu'] = function ($container) {
			return new Menu($container);
		};

		$this['license'] = function ($container) {
			return new PluginLicense($container, 'https://www.diviframework.com');
		};

		$this['plugin_upgrader'] = function ($container) {
			if (!class_exists('\WP_Upgrader')) {
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			}
			return new Plugin_Upgrader(new UpgraderSkin($container));
		};

	}

	/**
	 * Start the plugin
	 */
	public function run() {
		if (is_admin()) {
			// init menu.
			add_action('admin_menu', array($this['menu'], 'adminMenu'));

			// check if authentication has happened. If not let the user know.
			$container = $this;
			if (!$this['user_account']->authenticated()) {
				add_action('admin_notices', function () use ($container) {
					$class = 'notice notice-error is-dismissible';
					$message = sprintf('<a href="%s">Login </a> with your %s credentials to ensure updates are being run.', $container['dashboard_page'], $container['provider']);

					printf('<div class="%1$s"><p>%2$s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', $class, $message);
				});
			}

			add_action('init', array($this, 'do_output_buffer_for_download'));

		} //endif

		$this['license']->init();

		add_action('init', function () {
			if (!wp_next_scheduled('refresh_jwt_token')) {
				wp_schedule_event(time(), 'every_six_days', 'refresh_jwt_token');
			}
		});

		add_action('refresh_jwt_token', array($this['user_account'], 'refreshToken'));
		add_filter('cron_schedules', array($this['cron'], 'cron_schedules'));

	} //end

	// This hack is used to prevent "header already sent error"
	public function do_output_buffer_for_download() {
		if (isset($_GET['page']) && ($_GET['page'] == $this['dashboard_slug'])) {
			ob_start();
		}
	}

}
