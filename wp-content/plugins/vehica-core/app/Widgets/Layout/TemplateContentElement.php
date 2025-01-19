<?php

namespace Vehica\Widgets\Layout;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Page\Page;
use Elementor\Plugin;
use Vehica\Model\Post\Template\Layout;
use \Vehica\Model\Post\Template\Template;

/**
 * Class Template
 * @package Vehica\Widgets\Core
 */
class TemplateContentElement extends LayoutWidget
{
    const NAME = 'vehica_template_content';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Layout Page Content', 'vehica-core');
    }

    /**
     * @return bool
     */
    public function show_in_panel()
    {
        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;
        return Template::make($post)->isLayout();
    }

    /**
     * @param array $instance
     */
    protected function render($instance = [])
    {
        if (is_singular(Page::POST_TYPE)) {
            global $post;
            echo apply_filters('the_content', $post->post_content);
            return;
        }

        if (is_singular('attachment')) {
            get_template_part('templates/attachment');
        }

        if (is_404()) {
            echo Plugin::instance()->frontend->get_builder_content_for_display(vehicaApp('404_page_id'));
            return;
        }

        if (is_singular(Template::POST_TYPE)) {
            global $post;
            $template = Template::getByPost($post);
            if ($template->isLayout()) {
                require vehicaApp('views_path') . 'info/template_content.php';
            } else {
                the_content();
            }
            return;
        }

        /* @var Template $vehicaTemplate */
        global $vehicaTemplate;
        if ($vehicaTemplate) {
            echo Plugin::instance()->frontend->get_builder_content_for_display($vehicaTemplate->getId());
        }
    }

}