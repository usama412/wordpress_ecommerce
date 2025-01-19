<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class CheckExpireManager
 * @package Vehica\Managers
 */
class CheckExpireManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_nopriv_vehica/checkExpire', [$this, 'check']);
        add_action('admin_post_vehica/checkExpire', [$this, 'check']);

        add_action('admin_post_vehica/generateExpireHash', [$this, 'generateHash']);
    }

    public function check()
    {
        if ((empty($_GET['hash']) || $_GET['hash'] !== get_option('vehica_expire_hash')) && !current_user_can('manage_options')) {
            return;
        }

        do_action('vehica/checkExpire');

        if (is_user_logged_in() && current_user_can('manage_options')) {
            wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
            exit;
        }
    }

    public function generateHash()
    {
        if (!current_user_can('manage_options')) {
            exit;
        }

        update_option('vehica_expire_hash', md5(time() . ' ' . mt_rand(0, 1000000)));

        wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
        exit;
    }

}