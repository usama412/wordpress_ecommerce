<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\User\User;
use Vehica\Panel\Package;

/**
 * Class UserPackagesManager
 *
 * @package Vehica\Managers
 */
class UserPackagesManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_user_packages', [$this, 'get']);

        add_action('admin_post_vehica_check_user_packages_changes', [$this, 'checkChanges']);

        add_action('admin_post_vehica_user_add_free', [$this, 'addFree']);

        add_action('vehica/user/created', [$this, 'addFreeToNewUser']);
    }

    /**
     * @param User $user
     */
    public function addFreeToNewUser(User $user)
    {
        if (!vehicaApp('settings_config')->isAddPackageWhenRegisterEnabled()) {
            return;
        }

        $user->addPackage(Package::getFreeWhenRegister());
    }

    public function checkChanges()
    {
        echo json_encode(['reload' => !empty(get_option('vehica_reload_packages'))]);
    }

    public function get()
    {
        $user = new User(_wp_get_current_user());

        update_option('vehica_reload_packages', '0');

        echo json_encode($user->getPackages());
    }

    public function addFree()
    {
        if (!vehicaApp('settings_config')->isFreeListingEnabled()) {
            return;
        }

        $user = new User(_wp_get_current_user());
        $user->addPackage(Package::getFree());
    }

}