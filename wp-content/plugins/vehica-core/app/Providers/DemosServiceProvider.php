<?php


namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\Demo;
use Vehica\Core\ServiceProvider;

/**
 * Class DemosServiceProvider
 * @package Vehica\Providers
 */
class DemosServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('demos', static function () {
            /** @noinspection PhpIncludeInspection */
            return Collection::make(require vehicaApp('config_path') . 'demos.php')->map(static function ($demo) {
                return new Demo($demo);
            });
        });
    }

}