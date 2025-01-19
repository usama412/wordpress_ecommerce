<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;

/**
 * Class ImageServiceProvider
 * @package Vehica\Providers
 */
class ImageServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('image_sizes', static function () {
            global $_wp_additional_image_sizes;

            $sizes = get_intermediate_image_sizes();
            $output = [];

            foreach ($sizes as $size) {
                $output[$size]['width'] = (int)get_option("{$size}_size_w");
                $output[$size]['height'] = (int)get_option("{$size}_size_h");
                $output[$size]['crop'] = get_option("{$size}_crop") ?: false;
            }

            if (isset($_wp_additional_image_sizes) && count($_wp_additional_image_sizes)) {
                $output = array_merge($output, $_wp_additional_image_sizes);
            }

            return $output;
        });

        $this->app->bind('card_image_ratio', static function () {
            $key = vehicaApp('card_image_size');
            $imageSize = Collection::make(vehicaApp('image_sizes'))->find(static function ($size, $sizeKey) use ($key) {
                return $sizeKey === $key;
            });

            if (!$imageSize || empty($imageSize['width']) || empty($imageSize['height'])) {
                return 0.5552;
            }

            return $imageSize['height'] / $imageSize['width'];
        });

        $this->app->bind('card_image_ratio_padding', static function () {
            return (vehicaApp('card_image_ratio') * 100) . '%';
        });

        $this->app->bind('row_image_ratio', static function () {
            $key = vehicaApp('row_image_size');
            $imageSize = Collection::make(vehicaApp('image_sizes'))->find(static function ($size, $sizeKey) use ($key) {
                return $sizeKey === $key;
            });

            if (!$imageSize || empty($imageSize['width']) || empty($imageSize['height'])) {
                return 0.5552;
            }

            return $imageSize['height'] / $imageSize['width'];
        });

        $this->app->bind('row_image_ratio_padding', static function () {
            return (vehicaApp('row_image_ratio') * 100) . '%';
        });
    }

    /**
     * @param int $id
     * @param string $size
     * @return string|false
     */
    public static function getUrl($id, $size)
    {
        return wp_get_attachment_image_url($id, $size);
    }

    /**
     * @param int $id
     * @param string $size
     * @return string|false
     */
    public static function getSrcset($id, $size)
    {
        return wp_get_attachment_image_srcset($id, $size);
    }

}