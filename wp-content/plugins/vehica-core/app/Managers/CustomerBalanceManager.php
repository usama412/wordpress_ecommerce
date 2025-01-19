<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\User\User;
use Vehica\Panel\Package;
use WP_User;

/**
 * Class CustomerBalanceManager
 * @package Vehica\Managers
 */
class CustomerBalanceManager extends Manager
{

    public function boot()
    {
        add_action('edit_user_profile', [$this, 'packagesForm'], 9);
        add_action('show_user_profile', [$this, 'packagesForm'], 9);

        add_action('edit_user_profile_update', [$this, 'addPackage']);
        add_action('personal_options_update', [$this, 'addPackage']);
        add_action('edit_user_profile_update', [$this, 'setPackages'], 9);
        add_action('personal_options_update', [$this, 'setPackages'], 9);
    }

    /**
     * @param WP_User $user
     */
    public function packagesForm(WP_User $user)
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        /** @noinspection PhpUnusedLocalVariableInspection */
        $vehicaUser = new User($user);
        /** @noinspection PhpIncludeInspection */
        require vehicaApp('views_path') . 'forms/customer_balance.php';
    }

    /**
     * @param int $userId
     */
    public function addPackage($userId)
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $wpUser = get_user_by('id', $userId);
        if (!$wpUser) {
            return;
        }

        $package = Package::create($_POST['package']);
        if ($package->isEmpty()) {
            return;
        }

        $user = new User($wpUser);
        $user->addPackage($package);
    }

    public function setPackages($userId)
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $wpUser = get_user_by('id', $userId);
        if (!$wpUser) {
            return;
        }

        if (!isset($_POST['packages'])) {
            return;
        }

        $user = new User($wpUser);
        $user->setPackages($_POST['packages']);
    }


}