<?php

namespace Vehica\Managers;


use Elementor\Plugin;
use Vehica\Core\Manager;

/**
 * Class AdminPanelManager
 * @package Vehica\Managers
 */
class AdminPanelManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_panel_save_basic_setup', [$this, 'saveBasicSetup']);

        add_filter('admin_body_class', static function ($classes) {
            if (isset($_GET['page']) && strpos($_GET['page'], 'vehica_panel') !== false) {
                $classes .= ' ' . $_GET['page'];
            }

            return $classes;
        });

        add_filter('admin_body_class', static function ($classes) {
            if (isset($_GET['vehica_type']) !== false) {
                $classes .= ' vehica-edit-field';
            }

            return $classes;
        });

        add_action('admin_init', static function () {
            if (wp_doing_ajax()) {
                return;
            }

            $redirect = !empty(get_option('vehica_welcome'));
            if ($redirect && !isset($_GET['welcome']) && class_exists(Plugin::class) && !vehicaApp('hide_importer')) {
                wp_redirect(admin_url('admin.php?page=vehica_demo_importer&welcome=1'));
                exit;
            }

            if (isset($_GET['welcome'])) {
                update_option('vehica_welcome', '0');
            }
        });
    }

    public function saveBasicSetup()
    {
        if (!$this->currentUserCanManageOptions()) {
            return;
        }

        vehicaApp('settings_config')->update($_POST);

        $redirect = admin_url('admin.php?page=vehica_panel');

        if (!empty($_POST['hook'])) {
            $redirect .= '#' . $_POST['hook'];
        }

        do_action('vehica_flush_rewrites');

        wp_redirect($redirect);
        exit;
    }

}