<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class SaveSubmitPanelManager
 * @package Vehica\Managers
 */
class SaveSubmitPanelManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_save_submit_panel', [$this, 'save']);
    }

    public function save()
    {
        if (!isset($_POST['selectedFields']) || !current_user_can('manage_options')) {
            return;
        }

        $selectedFields = $_POST['selectedFields'];
        update_option('vehica_submit_panel_fields', $selectedFields);
    }

}