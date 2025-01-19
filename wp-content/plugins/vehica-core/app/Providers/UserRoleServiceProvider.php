<?php


namespace Vehica\Providers;


use Vehica\Core\ServiceProvider;

/**
 * Class UserRoleServiceProvider
 * @package Vehica\Providers
 */
class UserRoleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('private_user_role', static function () {
            return vehicaApp('settings_config')->getPrivateUserRole();
        });

        $this->app->bind('business_user_role', static function () {
            return vehicaApp('settings_config')->getBusinessUserRole();
        });

        $this->app->bind('user_roles', static function () {
            global $wp_roles;

            $roles = [];

            foreach ($wp_roles->roles as $key => $role) {
                $roles[$key] = $role['name'];
            }

            return $roles;
        });
    }

}