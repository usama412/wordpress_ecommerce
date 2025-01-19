<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Template\Layout;
use Vehica\Model\Post\Template\Template;
use Vehica\Core\Post\PostStatus;
use WP_Query;

/**
 * Class TemplateServiceProvider
 * @package Vehica\Providers
 */
class TemplateServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('templates', static function () {
            $query = new WP_Query([
                'post_status' => PostStatus::PUBLISH,
                'post_type' => Template::POST_TYPE,
                'posts_per_page' => '-1',
                'order' => 'ASC',
            ]);

            return Collection::make($query->posts)->map(static function ($post) {
                return Template::get($post);
            });
        });

        $this->app->bind('layouts', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template instanceof Layout;
            });
        });

        $this->app->bind('car_single_templates', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template->isCarSingle();
            });
        });

        $this->app->bind('car_single_template', static function () {
            return vehicaApp('car_config')->getSingleTemplate();
        });

        $this->app->bind('car_archive_templates', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template->isCarArchive();
            });
        });

        $this->app->bind('car_archive_template', static function () {
            return vehicaApp('car_config')->getArchiveTemplate();
        });

        $this->app->bind('post_single_templates', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template->isPostSingle();
            });
        });

        $this->app->bind('post_single_template', static function () {
            return vehicaApp('post_config')->getSingleTemplate();
        });

        $this->app->bind('post_archive_templates', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template->isPostArchive();
            });
        });

        $this->app->bind('post_archive_template', static function () {
            return vehicaApp('post_config')->getArchiveTemplate();
        });

        $this->app->bind('user_templates', static function () {
            return vehicaApp('templates')->filter(static function ($template) {
                /* @var Template $template */
                return $template->isUser();
            });
        });

        $this->app->bind('user_template', static function () {
            return vehicaApp('user_config')->getSingleTemplate();
        });

        $this->app->bind('template_types', static function () {
            return [
                [
                    'name' => esc_html__('Listing Page Templates', 'vehica-core'),
                    'singular' => esc_html__('Listing Page Template', 'vehica-core'),
                    'type' => Template::TYPE_CAR_SINGLE,
                    'icon' => 'fa fa-car',
                    'templateId' => vehicaApp('car_config')->getSingleTemplateId(),
                    'template' => vehicaApp('car_single_template'),
                ],
                [
                    'name' => esc_html__('Search Results Templates', 'vehica-core'),
                    'singular' => esc_html__('Search Results Template', 'vehica-core'),
                    'type' => Template::TYPE_CAR_ARCHIVE,
                    'icon' => 'fa fa-search',
                    'templateId' => vehicaApp('car_config')->getArchiveTemplateId(),
                    'template' => vehicaApp('car_archive_template'),
                ],
                [
                    'name' => esc_html__('User Page Templates', 'vehica-core'),
                    'singular' => esc_html__('User Page Template', 'vehica-core'),
                    'type' => Template::TYPE_USER,
                    'icon' => 'fas fa-user',
                    'templateId' => vehicaApp('user_config')->getSingleTemplateId(),
                    'template' => vehicaApp('user_template'),
                ],
                [
                    'name' => esc_html__('Post Page Templates', 'vehica-core'),
                    'singular' => esc_html__('Post Page Template', 'vehica-core'),
                    'type' => Template::TYPE_POST_SINGLE,
                    'icon' => 'fas fa-file-alt',
                    'templateId' => vehicaApp('post_config')->getSingleTemplateId(),
                    'template' => vehicaApp('post_single_template'),
                ],
                [
                    'name' => esc_html__('Blog Page Templates', 'vehica-core'),
                    'singular' => esc_html__('Blog Page Template', 'vehica-core'),
                    'type' => Template::TYPE_POST_ARCHIVE,
                    'icon' => 'fas fa-th',
                    'templateId' => vehicaApp('post_config')->getArchiveTemplateId(),
                    'template' => vehicaApp('post_archive_template'),
                ],
            ];
        });
    }

}