<?php

namespace Vehica\Providers;

if ( ! defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Config\CarConfig;
use Vehica\Model\Post\Config\Config;
use Vehica\Model\Post\Config\PostConfig;
use Vehica\Model\Post\Config\UserConfig;
use Vehica\Model\Post\Config\SettingsConfig;
use WP_Query;

/**
 * Class ConfigServiceProvider
 *
 * @package Vehica\Providers
 */
class ConfigServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('configs', static function () {
            $query = new WP_Query([
                'post_type'      => Config::POST_TYPE,
                'posts_per_page' => '-1',
                'post_status'    => PostStatus::PUBLISH
            ]);

            return Collection::make($query->posts)->map(static function ($post) {
                return Config::getByPost($post);
            })->filter(static function ($config) {
                return $config !== false;
            });
        });

        $this->app->bind('post_config', static function () {
            return vehicaApp('configs')->find(static function ($config) {
                return $config instanceof PostConfig;
            });
        });

        $this->app->bind('car_config', static function () {
            return vehicaApp('configs')->find(static function ($config) {
                return $config instanceof CarConfig;
            });
        });

        $this->app->bind('user_config', static function () {
            return vehicaApp('configs')->find(static function ($config) {
                return $config instanceof UserConfig;
            });
        });

        $this->app->bind('settings_config', static function () {
            return vehicaApp('configs')->find(static function ($config) {
                return $config instanceof SettingsConfig;
            });
        });
    }

}