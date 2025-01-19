<?php

namespace Vehica\Widgets\Layout;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;

/**
 * Class Template
 * @package Vehica\Widgets\Core
 */
class Template extends Widget
{
    /**
     * @return string
     */
    public function get_name()
    {
        return esc_html__('Layout Page Content', 'vehica-core');
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Layout Page Content', 'vehica-core');
    }

    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::LAYOUT
        ];
    }

    /**
     * @param array $instance
     */
    protected function render($instance = [])
    {
        if (is_singular(\Vehica\Model\Post\Template\Template::POST_TYPE)) {
            require vehicaApp('views_path') . 'info/template_content.php';
            return;
        }

        /* @var \Vehica\Model\Post\Template\Template $vehicaTemplate */
        global $vehicaTemplate;

        if (!$vehicaTemplate) {
            get_template_part('templates/default/single');
            return;
        }

        $vehicaTemplate->display();
    }

}