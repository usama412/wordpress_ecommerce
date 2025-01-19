<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class SearchV2GeneralWidget
 *
 * @package Vehica\Widgets\General
 */
class SearchV2GeneralWidget extends SearchV1GeneralWidget
{
    const NAME = 'vehica_search_v2_general_widget';
    const TEMPLATE = 'general/search/search_v2';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Search Form V2', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleControls();
    }

    private function addStyleControls()
    {
        $this->addGeneralStyleControls();

        $this->addMainFieldStyleControls();
    }

    private function addMainFieldStyleControls()
    {
        $this->start_controls_section(
            'main_field_style',
            [
                'label' => esc_html__('Main Field', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'main_field_typo',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-radio label'
            ]
        );

        $this->add_control(
            'main_field_color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .vehica-search-classic-v2__top .vehica-radio' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'main_field_color_hover',
            [
                'label' => esc_html__('Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-search-classic-v2__top .vehica-radio label:hover' => 'color: {{VALUE}} !important;'
                ]
            ]
        );

        $this->add_control(
            'main_field_color_active',
            [
                'label' => esc_html__('Color Active', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .vehica-search-classic-v2__top .vehica-radio.vehica-radio--active label' => 'color: {{VALUE}} !important;'
                ]
            ]
        );

        $this->end_controls_section();
    }

    private function addGeneralStyleControls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'vehica_width',
            [
                'label' => esc_html__('Width', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-search-classic-v2' => 'max-width: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'vehica_fields_background_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-search-classic-v2__fields' => 'background-color: {{VALUE}} !important;',
                    '{{WRAPPER}} .vehica-search-classic-v2__top .vehica-radio--active:after' => 'border-bottom-color: {{VALUE}} !important;'
                ]
            ]
        );

        $this->add_responsive_control(
            'vehica_fields_border_radius',
            [
                'label' => esc_html__('Border Radius', 'vehica-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-search-classic-v2__fields' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .vehica-search-classic-v2-mask-bottom' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addPaddingControl('vehica_fields', '.vehica-search-classic-v2__fields');

        $this->add_control(
            'vehica_show_shadow',
            [
                'label' => esc_html__('Hide Shadow', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->end_controls_section();
    }

    public function showShadow()
    {
        $show = (int)$this->get_settings_for_display('vehica_show_shadow');
        return empty($show);
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_search_fields',
            [
                'label' => esc_html__('Search Fields', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addMainFieldsControls();

        $this->addResultsPageControl();

        $this->end_controls_section();
    }

}