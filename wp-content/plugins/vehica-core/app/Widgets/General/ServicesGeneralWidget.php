<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;

/**
 * Class ServicesGeneralWidget
 * @package Vehica\Widgets\General
 */
class ServicesGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_services_general_widget';
    const TEMPLATE = 'general/services';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Services', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'services_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addServicesControl();

        $this->end_controls_section();

        $this->addStyleSections();
    }

    private function addStyleSections()
    {
        $this->addHeadingStyleControls();

        $this->addBoxStyleControls();
    }

    private function addHeadingStyleControls()
    {
        $this->start_controls_section(
            'heading_style',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'heading',
            '.vehica-services__name'
        );

        $this->addTextTypographyControl(
            'heading',
            '.vehica-services__name'
        );

        $this->end_controls_section();
    }

    private function addBoxStyleControls()
    {
        $this->start_controls_section(
            'box_style',
            [
                'label' => esc_html__('Box', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addBackgroundColorControl(
            'box',
            '.vehica-services__service'
        );

        $this->addBackgroundColorControl(
            'box_hover',
            '.vehica-services__service:hover',
            esc_html__('Background Color (Hover)', 'vehica-core')
        );

        $this->addBorderRadiusControl(
            'box',
            '.vehica-services__service'
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => esc_html__('Shadow', 'vehica-core'),
                'name' => 'box_shadow',
                'selector' => '{{WRAPPER}} .vehica-services__service',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label' => esc_html__('Shadow (Hover)', 'vehica-core'),
                'name' => 'box_shadow_hover',
                'selector' => '{{WRAPPER}} .vehica-services__service:hover',
            ]
        );

        $this->addPaddingControl(
            'box',
            '.vehica-services__service'
        );

        $this->end_controls_section();
    }

    private function addServicesControl()
    {
        $services = new Repeater();

        $services->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'vehica-core'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $services->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $services->add_control(
            'button_label',
            [
                'label' => esc_html__('Button Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => vehicaApp('view_string'),
            ]
        );

        $services->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'vehica-core'),
                'type' => Controls_Manager::URL
            ]
        );

        $this->add_control(
            'services',
            [
                'label' => esc_html__('Services', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $services->get_controls(),
            ]
        );
    }

    /**
     * @return array
     */
    public function getServices()
    {
        $services = $this->get_settings_for_display('services');

        if (!is_array($services)) {
            return [];
        }

        return $services;
    }

}