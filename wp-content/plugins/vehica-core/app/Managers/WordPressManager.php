<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use WP_Admin_Bar;

/**
 * Class WordPressManager
 * @package Vehica\Managers
 */
class WordPressManager extends Manager
{

    public function boot()
    {
        add_action('admin_bar_menu', [$this, 'modifyAdminBar'], 999);

        add_filter('wp_fatal_error_handler_enabled', '__return_false');

        add_action('admin_post_vehica/settings/setHomepage', [$this, 'setHomepage']);

        add_filter('upload_mimes', static function ($mimes) {
            $mimes['svg'] = 'image/svg+xml';

            return $mimes;
        });
    }

    public function setHomepage()
    {
        if (!empty($_POST['pageId']) && !$this->currentUserCanManageOptions()) {
            return;
        }

        update_option('page_on_front', (int)$_POST['pageId']);
        update_option('show_on_front', 'page');

        wp_redirect(admin_url('admin.php?page=vehica_demo_importer&state=finish'));
        exit;
    }

    public function modifyAdminBar($adminBar)
    {
        /* @var WP_Admin_Bar $adminBar */
        foreach ([
                     'new-vehica_custom_field',
                     'new-vehica_config',
                 ] as $node) {
            $adminBar->remove_node($node);
        }

        if (current_user_can('manage_options')) {
            return;
        }

        foreach ([
                     'new-vehica_template',
                     'new-vehica_car',
                 ] as $node) {
            $adminBar->remove_node($node);
        }
    }

}