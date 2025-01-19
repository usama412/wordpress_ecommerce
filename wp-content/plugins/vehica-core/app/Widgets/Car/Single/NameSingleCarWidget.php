<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class NameSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class NameSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_name_single_car_widget';
    const TEMPLATE = 'car/single/name';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Title', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addHtmlTagControl();

        $this->addTextColorControl(
            'vehica_name',
            '.vehica-car-name'
        );

        $this->addTextTypographyControl(
            'vehica_name',
            '.vehica-car-name'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    private function addHtmlTagControl()
    {
        $this->add_control(
            'vehica_tag',
            [
                'label'   => esc_html__('HtmlTag', 'vehica-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'div' => esc_html__('DIV', 'vehica-core'),
                    'h1'  => esc_html__('H1', 'vehica-core'),
                    'h2'  => esc_html__('H2', 'vehica-core'),
                    'h3'  => esc_html__('H3', 'vehica-core'),
                    'h4'  => esc_html__('H4', 'vehica-core'),
                    'h5'  => esc_html__('H5', 'vehica-core'),
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

}