<?php

namespace Vehica\Managers;

use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Core\PostType\PostTypeData;
use Vehica\Core\PostType\RegisterPostType;

/**
 * Class PostTypeManager
 * @package Vehica\Managers
 */
class PostTypeManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'register'], 5);

        add_action('init', [$this, 'registerDynamic'], 10);
    }

    public function register()
    {
        $registerType = new RegisterPostType();
        vehicaApp('core_post_types_data')->each(static function ($postTypeData) use ($registerType) {
            /* @var $postTypeData PostTypeData */
            if ($postTypeData->isCustom()) {
                $registerType->register($postTypeData);
            }
        });
    }

    public function registerDynamic()
    {
        $registerType = new RegisterPostType();
        $postTypes = apply_filters('vehica/postTypes/register', Collection::make());

        $postTypes->each(static function ($postType) use ($registerType) {
            $registerType->registerDynamic($postType);
        });
    }

}