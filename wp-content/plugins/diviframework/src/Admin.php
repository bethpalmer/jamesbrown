<?php

namespace DiviFramework\Hub;

/**
 * Admin related functionality.
 */
class Admin
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function admin_css()
    {
        echo sprintf('<link rel="stylesheet" href="%s">', $this->container['plugin_url'] . '/resources/css/pure-min.css');
    }

    public function show_notice($message, $type = 'error')
    {
        add_action('admin_notices', function () use ($message, $type) {
            $class = "notice notice-{$type} is-dismissible";

            printf('<div class="%1$s"><p>%2$s</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>', $class, $message);
        });
    }

    public function upgrade_plugin($plugin, $url)
    {
        $upgrader = $this->container['plugin_upgrader'];

        // update plugin transient with new url
        $current = get_site_transient('update_plugins');
        $plugin_transient = array(
            'package' => $url,
        );
        $plugin_transient = json_decode(json_encode($plugin_transient));
        $current->response[$plugin] = $plugin_transient;
        set_site_transient('update_plugins', $current);

        remove_filter('upgrader_pre_install', array($upgrader, 'deactivate_plugin_before_upgrade'));
        $result = $upgrader->upgrade($plugin, array());

        // clear transient and let it build up using core code.
        delete_site_transient('update_plugins');
        return $result;
    }

    public function install_plugin($plugin, $url)
    {
        $upgrader = $this->container['plugin_upgrader'];

        $result   = $upgrader->install($url);

        return $result;
    }

    public function is_plugin_installed($slug)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        // get all the plugins
        $installedPlugins = get_plugins();

        foreach ($installedPlugins as $installedPlugin => $data) {
            // check for the plugin title
            if ($installedPlugin == $slug) {
                // return the plugin folder/file
                return $installedPlugin;
            }
        }

        return false;
    }

    public function can_upgrade_plugin($slug)
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $data = $this->container['user_account']->getAccountData();

        foreach ($data['extensions'] as $extension) {
            if (($extension['type'] != 'Plugin') || ($extension['slug'] != $slug)) {
                continue;
            }

            $plugin_active = is_plugin_active($extension['plugin_path']);

            if (!$plugin_active) {
                return false;
            }

            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $extension['plugin_path']);

            if (!is_array($plugin_data)) {
                return false;
            }

            $current_version = $plugin_data['Version'];
            $latest_version = $extension['version'];
            if (version_compare($current_version, $latest_version) === -1) {
                return true;
            }

            return false;
        }
    }
}
