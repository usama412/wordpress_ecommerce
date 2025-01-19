<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\PostType\PostTypeData;
use Vehica\Core\ServiceProvider;

/**
 * Class PostTypeServiceProvider
 * @package Vehica\Providers
 */
class PostTypesServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->factory('core_post_types_data', static function ($app) {
            $path = $app['config_path'] . 'posttypes.php';
            /** @noinspection PhpIncludeInspection */
            $postTypesData = file_exists($path) ? require $path : [];
            return Collection::make($postTypesData)->map(static function ($data, $postClass) {
                return new PostTypeData(
                    $data['key'],
                    $postClass,
                    $data['name'],
                    $data['singular_name'],
                    $data['is_custom'],
                    $data['options'],
                    $data['taxonomies']
                );
            });
        });
    }

}