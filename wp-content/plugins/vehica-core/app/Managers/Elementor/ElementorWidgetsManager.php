<?php

namespace Vehica\Managers\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\Post\Post;
use Elementor\Plugin;
use Vehica\Widgets\WidgetCategory;
use WP_Post;

/**
 * Class ElementorWidgetsManager
 * @package Vehica\Managers\Elementor
 */
class ElementorWidgetsManager extends Manager
{

    public function boot()
    {
        add_action('elementor/widgets/register', [$this, 'registerWidgets']);

        add_action('elementor/controls/register', [$this, 'registerWidgetControls']);

        add_action('elementor/elements/categories_registered', [$this, 'registerWidgetCategories']);
    }

    public function registerWidgets()
    {
        $categories = $this->getCurrentCategories();
        $path = vehicaApp('config_path') . 'widgets.php';

        Collection::make(require $path)->each(static function ($widgets, $category) use ($categories) {
            if (in_array($category, $categories, true)) {
                Collection::make($widgets)->each(static function ($widget) {
                    Plugin::instance()->widgets_manager->register_widget_type(new $widget);
                });
            }
        });
    }

    public function registerWidgetCategories()
    {
        $path = vehicaApp('config_path') . 'widgetcategories.php';

        Collection::make(require $path)->each(static function ($category, $categoryKey) {
            Plugin::instance()->elements_manager->add_category(
                $categoryKey,
                [
                    'title' => $category['name'],
                    'icon' => $category['icon']
                ]
            );
        });
    }

    /**
     * @param WP_Post $post
     * @return array
     */
    private function getElementorTemplateCategories(WP_Post $post)
    {
        $categories = [
            WidgetCategory::GENERAL,
            WidgetCategory::LAYOUT
        ];

        $context = get_post_meta($post->ID, 'vehica_template_context', true);
        if ($context === 'general') {
            return $categories;
        }

        if ($context === 'post_single') {
            $categories[] = WidgetCategory::POST_SINGLE;
            return $categories;
        }

        if ($context === 'post_archive') {
            $categories[] = WidgetCategory::POST_ARCHIVE;
            return $categories;
        }

        if ($context === 'car_single') {
            $categories[] = WidgetCategory::CAR_SINGLE;
            return $categories;
        }

        if ($context === 'car_archive') {
            $categories[] = WidgetCategory::CAR_ARCHIVE;
            return $categories;
        }

        if ($context === 'user') {
            $categories[] = WidgetCategory::USER;
            return $categories;
        }

        return $categories;
    }

    /**
     * @return array
     */
    private function getCurrentCategories()
    {
        $categories = [
            WidgetCategory::GENERAL,
            WidgetCategory::LAYOUT
        ];

        if (is_singular('elementor_library')) {
            global $post;
            return $this->getElementorTemplateCategories($post);
        }

        if (isset($_POST['editor_post_id']) && !is_singular('page')) {
            $postId = (int)$_POST['editor_post_id'];
            $post = get_post($postId);

            if ($post && $post->post_type === Template::POST_TYPE) {
                $template = Template::getById($postId);

                if ($template) {
                    $categories[] = $template->getWidgetCategory();
                }

                return $categories;
            }

            if ($post && $post->post_type === 'elementor_library') {
                return $this->getElementorTemplateCategories($post);
            }
        }

        if (is_home() || is_category() || is_tag() || is_search()) {
            $categories[] = WidgetCategory::POST_ARCHIVE;
        } elseif (is_singular()) {
            $postType = get_post_type();
            if ($postType === Post::POST_TYPE) {
                $categories[] = WidgetCategory::POST_SINGLE;
            } elseif ($postType === Car::POST_TYPE) {
                $categories[] = WidgetCategory::CAR_SINGLE;
            } elseif ($postType === Template::POST_TYPE) {
                global $post;
                $categories[] = Template::get($post)->getWidgetCategory();
            }
        } elseif (is_post_type_archive(Car::POST_TYPE)) {
            $categories[] = WidgetCategory::CAR_ARCHIVE;
        } elseif (is_author()) {
            $categories[] = WidgetCategory::USER;
        }

        return $categories;
    }

    /**
     * @param Controls_Manager $controlsManager
     * @return void
     */
    public function registerWidgetControls($controlsManager)
    {
        $path = vehicaApp('config_path') . 'widgetcontrols.php';

        Collection::make(require $path)->each(static function ($class, $type) use ($controlsManager) {
            $controlsManager->register(new $class);
        });
    }

}