<?php

namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;

/**
 * Class FontsServiceProvider
 * @package Vehica\Providers
 */
class FontsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('heading_font', static function () {
            return vehicaApp('settings_config')->getHeadingFont();
        });

        $this->app->bind('text_font', static function () {
            return vehicaApp('settings_config')->getTextFont();
        });

        $this->app->bind('fonts_list', static function () {
            $fonts = [];
            $path = vehicaApp('path') . '/config/fonts.php';

            Collection::make(require $path)->each(static function ($fontType) use (&$fonts) {
                Collection::make($fontType)->each(static function ($font) use (&$fonts) {
                    $fonts[$font] = $font;
                });
            });

            return $fonts;
        });
    }

}