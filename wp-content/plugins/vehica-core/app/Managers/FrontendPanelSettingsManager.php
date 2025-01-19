<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Config\Setting;

/**
 * Class FrontendPanelSettingsManager
 * @package Vehica\Managers
 */
class FrontendPanelSettingsManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_panel_save_monetization', [$this, 'save']);
    }

    public function save()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        vehicaApp('settings_config')->update($_POST, Setting::getFrontendPanelSettings());

        wp_redirect(admin_url('admin.php?page=vehica_panel_monetization'));
        exit;
    }

}