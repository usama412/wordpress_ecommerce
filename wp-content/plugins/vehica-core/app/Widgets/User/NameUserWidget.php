<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class NameUserWidget
 * @package Vehica\Widgets\User
 */
class NameUserWidget extends UserWidget
{
    const NAME = 'vehica_name_user_widget';
    const TEMPLATE = 'user/name';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Name', 'vehica-core');
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

        $this->addTextAlignControl(
            'vehica_align',
            '.vehica-user-name'
        );

        $this->end_controls_section();
    }

    private function addHtmlTagControl()
    {
        $this->add_control(
            'vehica_tag',
            [
                'label' => esc_html__('HtmlTag', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'div' => esc_html__('DIV', 'vehica-core'),
                    'h1' => esc_html__('H1', 'vehica-core'),
                    'h2' => esc_html__('H2', 'vehica-core'),
                    'h3' => esc_html__('H3', 'vehica-core'),
                    'h4' => esc_html__('H4', 'vehica-core'),
                    'h5' => esc_html__('H5', 'vehica-core'),
                ],
                'default' => 'div',
            ]
        );
    }

    /**
     * @return string
     */
    public function getHtmlTag()
    {
        return (string)$this->get_settings_for_display('vehica_tag');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}