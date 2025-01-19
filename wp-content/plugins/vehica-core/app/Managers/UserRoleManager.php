<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class UserRoleManager
 * @package Vehica\Managers
 */
class UserRoleManager extends Manager
{

    public function boot()
    {
        add_action('admin_init', [$this, 'create']);
    }

    public function create()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        global $wp_roles;

        if (!$wp_roles->is_role('vehica_private_role')) {
            add_role('vehica_private_role', esc_html__('Private seller'));
        }

        if (!$wp_roles->is_role('vehica_business_role')) {
            add_role('vehica_business_role', esc_html__('Business seller'));
        }
    }

}