<?php
/**
 * Plugin Name:     DiviFramework
 * Plugin URI:      https://www.diviframework.com
 * Description:     Plugin to manage your DiviFramework account, content distribution and plugin/theme updates.
 * Author:          Divi Framework
 * Author URI:      https://www.diviframework.com
 * Text Domain:     diviframework
 * Domain Path:     /languages
 * Version:         1.1.1
 *
 * @package
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('DIVI_FRAMEWORK_HUB_VERSION', '1.1.1');
define('DIVI_FRAMEWORK_HUB_DIR', __DIR__);
define('DIVI_FRAMEWORK_HUB_URL', plugins_url('/' . basename(__DIR__)));

require_once DIVI_FRAMEWORK_HUB_DIR . '/vendor/autoload.php';

$container = \DiviFramework\Hub\Container::getInstance();
$container['plugin_name'] = 'DiviFramework';
$container['plugin_version'] = DIVI_FRAMEWORK_HUB_VERSION;
$container['plugin_file'] = __FILE__;
$container['plugin_dir'] = DIVI_FRAMEWORK_HUB_DIR;
$container['plugin_url'] = DIVI_FRAMEWORK_HUB_URL;
$container['plugin_slug'] = 'diviframework-hub';

// activation hook.
register_activation_hook(__FILE__, array($container['activation'], 'install'));

$container->run();
