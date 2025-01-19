<?php

namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Page;
use WP_Query;

/**
 * Class PageServiceProvider
 * @package Vehica\Providers
 */
class PageServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('pages', static function () {
            $query = new WP_Query([
                'post_status' => PostStatus::PUBLISH,
                'post_type' => Page::POST_TYPE,
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ]);

            return Collection::make($query->posts)->map(static function ($page) {
                return new Page($page);
            });
        });

        $this->app->bind('pages_list', static function () {
            return vehicaApp('pages')->toList();
        });
    }

}