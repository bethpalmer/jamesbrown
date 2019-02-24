<?php
/**
 * Plugin Name:     Instagram Feed Divi Module
 * Plugin URI:      https://www.diviframework.com
 * Description:     Divi Module for Instagram Feed.
 * Author:          Divi Framework
 * Author URI:      https://www.diviframework.com
 * Text Domain:     df-instagram-feed-module
 * Domain Path:     /languages
 * Version:         1.2.3
 *
 * @package
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('DF_INSTAGRAM_FEED_MODULE_VERSION', '1.2.3');
define('DF_INSTAGRAM_FEED_MODULE_DIR', __DIR__);
define('DF_INSTAGRAM_FEED_MODULE_URL', plugins_url('/' . basename(__DIR__)));

require_once DF_INSTAGRAM_FEED_MODULE_DIR . '/vendor/autoload.php';

$container = new \DF\InstagramFeed\Container;
$container['plugin_name'] = 'Instagram Feed Divi Module';
$container['plugin_version'] = DF_INSTAGRAM_FEED_MODULE_VERSION;
$container['plugin_file'] = __FILE__;
$container['plugin_dir'] = DF_INSTAGRAM_FEED_MODULE_DIR;
$container['plugin_url'] = DF_INSTAGRAM_FEED_MODULE_URL;
$container['plugin_slug'] = 'df-instagram-feed-module';

// activation hook.
register_activation_hook(__FILE__, array($container['activation'], 'install'));

$container->run();
