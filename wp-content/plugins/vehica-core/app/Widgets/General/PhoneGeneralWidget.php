<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class PhoneGeneralWidget
 * @package Vehica\Widgets\General
 */
class PhoneGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_phone_general_widget';
    const TEMPLATE = 'general/phone';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Phone', 'vehica-core');
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
            'vehica_style',
            [
                'label' => esc_html__('Style', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'v1' => esc_html__('Style 1', 'vehica-core'),
                    'v2' => esc_html__('Style 2', 'vehica-core'),
                    'v3' => esc_html__('Style 3', 'vehica-core'),
                    'v4' => esc_html__('Style 4', 'vehica-core'),
                ],
                'default' => 'v1'
            ]
        );

        $this->addTextAlignControl(
            'vehica_phone',
            '.vehica-phone'
        );

        $this->addTextColorControl(
            'phone',
            '.vehica-phone a'
        );

        $this->addTextColorControl(
            'phone_hover',
            '.vehica-phone a:hover',
            esc_html__('Color (Hover)', 'vehica-core')
        );

        $this->addTextTypographyControl(
            'phone',
            '.vehica-phone a'
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'phone_typography_hover',
                'label' => esc_html__('Typography (Hover)', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-phone a:hover'
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    private function getStyle()
    {
        $style = (string)$this->get_settings_for_display('vehica_style');

        if (empty($style)) {
            return 'v1';
        }

        return $style;
    }

    /**
     * @return bool
     */
    public function isStyleV1()
    {
        return $this->getStyle() === 'v1';
    }

    /**
     * @return bool
     */
    public function isStyleV2()
    {
        return $this->getStyle() === 'v2';
    }


    /**
     * @return bool
     */
    public function isStyleV3()
    {
        return $this->getStyle() === 'v3';
    }

    /**
     * @return bool
     */
    public function isStyleV4()
    {
        return $this->getStyle() === 'v4';
    }

}