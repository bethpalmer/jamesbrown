<?php

namespace DiviFramework\Hub\Admin;

/**
 * Admin Dashboard
 */
class Dashboard
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function redirectBase($additional_params = '')
    {
        wp_redirect($this->container['dashboard_page'] . $additional_params);
        exit;
    }

    public function view()
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'refresh':
                    $this->container['user_account']->syncAccountData();
                    $this->redirectBase();
                    break;

                case 'logout':
                    $this->container['user_account']->deleteAuthToken();
                    $this->redirectBase();
                    break;

                case 'dw_plugin':
                    $url = $this->container['user_account']->getDownloadUrl($_GET['slug']);
                    if ($url) {
                        wp_redirect($url);
                        exit;
                    } else {
                        $this->redirectBase();
                    }
                    break;

                case 'upgrade_plugin':
                    if ($this->container['admin']->can_upgrade_plugin($_GET['slug'])) {
                        $extension = $this->container['user_account']->getExtension($_GET['slug']);
                        if ($extension) {
                            $download_url = $this->container['user_account']->getDownloadUrl($extension['slug']);
                            $result = $this->container['admin']->upgrade_plugin($extension['plugin_path'], $download_url);
                            if ($result) {
                                activate_plugins($extension['plugin_path']);
                                $this->redirectBase('&action=df_notice&type=post_upgrade&slug=' . $extension['slug']);
                            }
                        }
                    }
                    break;

                case 'install_plugin':
                    // wp_die(var_dump($this->container['admin']->is_plugin_installed($_GET['slug'])));
                    if (!$this->container['admin']->is_plugin_installed($_GET['slug'])) {
                        $extension = $this->container['user_account']->getExtension($_GET['slug']);
                        
                        if ($extension) {
                            $download_url = $this->container['user_account']->getDownloadUrl($extension['slug']) ;
                            
                            $result = $this->container['admin']->install_plugin($extension['plugin_path'], $download_url);
                           
                            if (is_wp_error($result) || is_null($result)) {
                                $message = urlencode('Error downloading the plugin. Click the `Refresh Account` link on the top right and try again. If it still does not work, please contact support.');
                                $this->redirectBase('&action=df_notice&type=error&slug=' . $extension['slug'] . '&msg=' . $message);
                            }
                            if ($result) {
                                $this->redirectBase('&action=df_notice&type=post_install&slug=' . $extension['slug']);
                            }
                        }
                    }
                    
                    break;

                case 'df_notice':
                    switch ($_GET['type']) {
                        case 'post_upgrade':
                            $extension = $this->container['user_account']->getExtension($_GET['slug']);
                            $notice_message = sprintf('%s plugin upgraded to version %s', $extension['name'], $extension['version']);
                            $notice_type = 'success';

                            break;

                        case 'post_install':
                            $extension = $this->container['user_account']->getExtension($_GET['slug']);
                            $notice_message = sprintf('%s plugin installed - version %s. <a href="%s" class="pure-button">Activate Plugin</a>', $extension['name'], $extension['version'], $this->container['admin_dashboard']->pluginActivationLink($extension['plugin_path']));
                            $notice_type = 'success';

                            break;

                        case 'error':
                            $extension = $this->container['user_account']->getExtension($_GET['slug']);
                            $notice_message = $_GET['msg'];
                            $notice_type = 'error';

                            break;


                        default:
                            break;
                    }

                    break;

                default:
                    # code...
                    break;
            }
        }

        $this->container['admin']->admin_css();

        $isPost = $this->container['user_account']->postAuthentication();

        if (!$this->container['user_account']->authenticated()) {
            // die('herw');
            ob_start();
            require $this->container['plugin_dir'] . '/resources/views/admin/login.php';
            echo ob_get_clean();
        } else {
            $data = $this->container['user_account']->getAccountData();

            if (empty($data)) {
                // redirect same page.
                $this->container['user_account']->deleteAuthToken();
                $this->redirectBase();
            }

            $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($data['email']))) . "?s50";

            // var_dump($data);

            ob_start();
            require $this->container['plugin_dir'] . '/resources/views/admin/dashboard.php';
            echo ob_get_clean();
        }
    }

    //plugin upgrade link
    public function upgradePluginLink($extension)
    {
        return $this->container['dashboard_page'] . '&action=upgrade_plugin&slug=' . $extension['slug'];
    }

    public function installPluginLink($extension)
    {
        return $this->container['dashboard_page'] . '&action=install_plugin&slug=' . $extension['slug'];
    }

    public function downloadPluginLink($slug)
    {
        return $this->container['dashboard_page'] . '&action=dw_plugin&slug=' . $slug;
    }

    public function refreshAccountLink()
    {
        return $this->container['dashboard_page'] . '&action=refresh';
    }

    public function pluginActivationLink($plugin)
    {
        // the plugin might be located in the plugin folder directly

        $activateUrl = sprintf(admin_url('plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s'), urlencode($plugin));

        // change the plugin request to the plugin to pass the nonce check
        $_REQUEST['plugin'] = $plugin;
        $activateUrl = wp_nonce_url($activateUrl, 'activate-plugin_' . $plugin);

        return $activateUrl;
    }

    public function filteredExtensions($extensions)
    {
        $filter = isset($_GET, $_GET['filter']) && ! empty($_GET['filter']) ? $_GET['filter'] : '';
        $s = isset($_GET, $_GET['s']) && ! empty($_GET['s']) ? $_GET['s'] : '';

        
        if (empty($filter) && empty($s)) {
            return $extensions;
        }

        $filtered = array();
        
        foreach ($extensions as $extension) {
            if (!empty($filter) && !empty($s)) {
                if ($extension['type'] != $filter) {
                    continue;
                }

                if ($this->extension_exists($extension, $s)) {
                    $filtered[] = $extension;
                }
            }

            if (empty($filter) && !empty($s)) {
                if ($this->extension_exists($extension, $s)) {
                    $filtered[] = $extension;
                }
            }

            if (!empty($filter) && empty($s)) {
                if ($extension['type'] == $filter) {
                    $filtered[] = $extension;
                }
            }
        }
        return $filtered;
    }


    public function extension_exists($extension, $s)
    {
        return (strpos(strtolower($extension['name']), $s) !== false) || (strpos(strtolower($extension['description']), $s) !== false);
    }

    // get unique filters.
    public function getUniqueTypes($extensions)
    {
        $types = array();

        foreach ($extensions as $extension) {
            $types[$extension['type']] = '';
        }

        $types = array_keys($types);
        asort($types);
        return $types;
    }

    public function myAccountLink()
    {
        return $this->container['provider_base_url'] . $this->container['provider_my_account_uri'];
    }
}
