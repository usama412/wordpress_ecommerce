<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;

/**
 * Class CategoriesSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class CategoriesSinglePostWidget extends SinglePostWidget
{
    const NAME = 'vehica_categories_single_post_widget';
    const TEMPLATE = 'post/single/categories';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Categories', 'vehica-core');
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

        $this->add_control(
            'vehica_no_settings_info',
            [
                'label' => esc_html__('No Settings', 'vehica-core'),
                'type' => Controls_Manager::HEADING
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}