<?php

namespace Vehica\Managers;

use Vehica\Core\Collection;
use Vehica\Core\CreateConfig;
use Vehica\Core\Manager;

/**
 * Class ConfigManager
 *
 * @package Vehica\Managers
 */
class ConfigManager extends Manager
{

    public function boot()
    {
        add_action('admin_init', [$this, 'createConfigPosts'], 5);
    }

    public function createConfigPosts()
    {
        /** @noinspection PhpIncludeInspection */
        Collection::make(require vehicaApp('path') . '/config/configs.php')->map(static function ($config) {
            return new CreateConfig($config['key'], $config['name']);
        })->each(static function ($createConfig) {
            /* @var CreateConfig $createConfig */
            if ( ! $createConfig->exists()) {
                $createConfig->create();
            }
        });
    }

}