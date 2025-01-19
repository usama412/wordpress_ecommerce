<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;

/**
 * Class NameSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class NameSinglePostWidget extends SinglePostWidget
{
    const NAME = 'vehica_name_single_post_widget';
    const TEMPLATE = 'post/single/name';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Post Name', 'vehica-core');
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

        $this->addHtmlTagControl();

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getHtmlTag()
    {
        return (string)$this->get_settings_for_display('vehica_html_tag');
    }

    private function addHtmlTagControl()
    {
        $this->add_control(
            'vehica_html_tag',
            [
                'label' => esc_html__('Tag', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => esc_html__('DIV', 'vehica-core'),
                    'h1' => esc_html__('H1', 'vehica-core'),
                    'h2' => esc_html__('H2', 'vehica-core'),
                    'h3' => esc_html__('H3', 'vehica-core'),
                    'h4' => esc_html__('H4', 'vehica-core'),
                    'h5' => esc_html__('H5', 'vehica-core'),
                ],
                'default' => 'h1',
            ]
        );
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }
}