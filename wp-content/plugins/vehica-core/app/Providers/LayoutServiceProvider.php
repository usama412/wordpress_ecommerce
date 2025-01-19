<?php

namespace Vehica\Providers;


use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Page;
use Vehica\Model\Post\Template\Layout;
use WP_Post;

/**
 * Class LayoutServiceProvider
 * @package Vehica\Providers
 */
class LayoutServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('global_layout_id', static function () {
            $layout = vehicaApp('global_layout');
            if (!$layout instanceof Layout) {
                return 0;
            }

            return $layout->getId();
        });

        $this->app->bind('global_layout', static function () {
            $layoutId = Layout::getGlobalId();
            $layout = Layout::getById($layoutId);

            if ($layout instanceof Layout) {
                return $layout;
            }

            $layout = vehicaApp('layouts')->first();
            if (!$layout instanceof Layout) {
                return false;
            }

            Layout::setGlobal($layout->getId());

            return $layout;
        });

        $this->app->bind('car_single_layout', static function () {
            $layout = vehicaApp('car_config')->getSingleLayout();

            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });

        $this->app->bind('car_archive_layout', static function () {
            $layout = vehicaApp('car_config')->getArchiveLayout();

            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });

        $this->app->bind('post_single_layout', static function () {
            $layout = vehicaApp('post_config')->getSingleLayout();

            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });

        $this->app->bind('post_archive_layout', static function () {
            $layout = vehicaApp('post_config')->getArchiveLayout();

            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });

        $this->app->bind('user_layout', static function () {
            $layout = vehicaApp('user_config')->getSingleLayout();

            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });

        $this->app->bind('page_layout', static function () {
            global $post;
            if (!$post instanceof WP_Post) {
                return vehicaApp('global_layout');
            }

            $page = Page::getById($post->ID);
            if (!$page instanceof Page) {
                return vehicaApp('global_layout');
            }

            $layout = $page->getLayout();
            if (!$layout instanceof Layout) {
                return vehicaApp('global_layout');
            }

            return $layout;
        });
    }

}