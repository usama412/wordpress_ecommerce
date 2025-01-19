<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;

/**
 * Class AuthorImageSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class AuthorImageSinglePostWidget extends SinglePostWidget
{
    const NAME = 'vehica_author_image_single_post_widget';
    const TEMPLATE = 'post/single/author_image';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Author Image', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addImageSizeControl('large');

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->prepareImageRenderAttributes();

        $this->loadTemplate();
    }

    private function prepareImageRenderAttributes()
    {
        $this->add_render_attribute('image', 'class', 'vehica-post-author-image');
    }

}