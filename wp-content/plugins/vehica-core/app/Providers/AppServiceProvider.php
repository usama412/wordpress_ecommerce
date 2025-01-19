<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\ServiceProvider;

/**
 * Class AppServiceProvider
 * @package Vehica\Providers
 */
class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('name', esc_html__('Vehica', 'vehica-core'));
        $this->app->bind('version', '1.0.90');
        $this->app->bind('prefix', 'vehica_');
        $this->app->bind('assets', vehicaApp('url') . 'assets/');
        $this->app->bind('assets_img', vehicaApp('url') . 'assets/img/');
        $this->app->bind('assets_js', vehicaApp('url') . 'assets/js/');
        $this->app->bind('assets_css', vehicaApp('url') . 'assets/css/');
    }

}