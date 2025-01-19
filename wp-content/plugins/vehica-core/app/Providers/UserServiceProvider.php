<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\User\User;
use WP_User;

/**
 * Class UserServiceProvider
 * @package Vehica\Providers
 */
class UserServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('current_user', static function () {
            if (!is_user_logged_in()) {
                return false;
            }

            $user = wp_get_current_user();
            return new User($user);
        });

        $this->app->bind('wp_users', static function () {
            return Collection::make(get_users());
        });

        $this->app->bind('users', static function () {
            return vehicaApp('wp_users')->map(static function ($user) {
                return new User($user);
            });
        });

        $this->app->bind('users_list', static function () {
            return vehicaApp('users')->toList();
        });
    }

    /**
     * @param string $key
     * @return User|false
     */
    public static function getUser($key)
    {
        $userKey = str_replace('user_', '', $key);
        $userId = (int)$userKey;
        $user = get_user_by('id', $userId);

        if (!$user instanceof WP_User) {
            return false;
        }

        return User::getByUser($user);
    }

    /**
     * @param User $user
     * @param string $size
     * @return string|false
     */
    public static function getImageUrl(User $user, $size)
    {
        $imageId = $user->getImageId();

        if (empty($imageId)) {
            return false;
        }

        return vehicaApp('image_url', $imageId, $size);
    }

}