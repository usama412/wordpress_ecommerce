<?php

namespace Vehica\Providers;


use Elementor\Plugin;
use Vehica\Core\ServiceProvider;

/**
 * Class ElementorServiceProvider
 *
 * @package Vehica\Providers
 */
class ElementorServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('mobile_breakpoint', static function () {
            $mobileBreakpoint = (int)Plugin::instance()->kits_manager->get_current_settings('viewport_mobile');
            if (empty($mobileBreakpoint)) {
                return 899;
            }

            return $mobileBreakpoint - 1;
        });

        $this->app->bind('tablet_breakpoint', static function () {
            $tabletBreakpoint = (int)Plugin::instance()->kits_manager->get_current_settings('viewport_tablet');
            if (empty($tabletBreakpoint)) {
                return 1199;
            }

            return $tabletBreakpoint - 1;
        });
    }

}