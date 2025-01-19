<?php


namespace Vehica\Managers;


use Automatic_Upgrader_Skin;
use Plugin_Upgrader;
use Vehica\Core\Manager;

/**
 * Class PluginManager
 * @package Vehica\Managers
 */
class PluginManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica/plugins/install', [$this, 'install']);
    }

    public function install()
    {
        if (empty($_POST['plugin']) || !current_user_can('manage_options')) {
            return;
        }

        $pluginData = $this->getPluginData($_POST['plugin']);
        if (!$pluginData) {
            return;
        }

        if (!function_exists('plugins_api')) {
            require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        }

        require_once ABSPATH . 'wp-admin/includes/misc.php';

        if (!function_exists('request_filesystem_credentials')) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        if (!class_exists('Plugin_Upgrader')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        }

        $api = plugins_api('plugin_information', ['slug' => $pluginData['slug'], 'fields' => ['sections' => false]]);

        $plugin = new Plugin_Upgrader(new Automatic_Upgrader_Skin());
        $plugin->install($api->download_link);

        activate_plugin(WP_PLUGIN_DIR . '/' . $pluginData['path']);
    }

    /**
     * @param string $plugin
     * @return false|array
     */
    private function getPluginData($plugin)
    {
        if ($plugin === 'wp-all-import') {
            return [
                'slug' => 'wp-all-import',
                'path' => 'wp-all-import/plugin.php',
            ];
        }

        if ($plugin === 'cookie-notice') {
            return [
                'slug' => 'cookie-notice',
                'path' => 'cookie-notice/cookie-notice.php',
            ];
        }

        return false;
    }

}