<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;

/**
 * Class CommentNumberSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class CommentNumberSinglePostWidget extends SinglePostWidget
{
    const NAME = 'vehica_comment_number_single_post_widget';
    const TEMPLATE = 'post/single/comment_number';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Comment Number', 'vehica-core');
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