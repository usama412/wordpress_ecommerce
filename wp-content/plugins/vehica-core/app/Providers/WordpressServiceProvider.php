<?php

namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Page;
use Vehica\Model\Term\Term;

/**
 * Class WordpressServiceProvider
 * @package Vehica\Providers
 */
class WordpressServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('menus', static function () {
            $menus = get_terms('nav_menu', [
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);

            if (!is_array($menus)) {
                return Collection::make();
            }

            return Collection::make($menus)->map(static function ($menu) {
                return new Term($menu);
            });
        });

        $this->app->bind('404_page_id', static function () {
            return vehicaApp('settings_config')->getErrorPageId();
        });

        $this->app->bind('404_page', static function () {
            if (empty(vehicaApp('404_page_id'))) {
                return false;
            }

            return Page::getById(vehicaApp('404_page_id'));
        });
    }

}