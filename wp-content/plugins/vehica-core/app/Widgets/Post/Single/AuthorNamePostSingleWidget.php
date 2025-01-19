<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class AuthorNamePostSingleWidget
 * @package Vehica\Widgets\Post\Single
 */
class AuthorNamePostSingleWidget extends SinglePostWidget
{
    const NAME = 'vehica_author_name_single_post_widget';
    const TEMPLATE = 'post/single/author_name';
    const SHOW_ICON = 'show_icon';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Author Name', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->showIconControl();

        $this->colorControls();

        $this->typographyControls();

        $this->end_controls_section();
    }

    private function typographyControls()
    {
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-post-author-name a'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography_hover',
                'label' => esc_html__('Typography Hover', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-post-author-name a:hover'
            ]
        );
    }

    private function colorControls()
    {
        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-post-author-name a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__('Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-post-author-name a:hover' => 'color: {{VALUE}};'
                ]
            ]
        );
    }

    private function showIconControl()
    {
        $this->add_control(
            self::SHOW_ICON,
            [
                'label' => esc_html__('Display Icon', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    public function showIcon()
    {
        return !empty($this->get_settings_for_display(self::SHOW_ICON));
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}