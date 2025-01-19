<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class UserPhoneSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserPhoneSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_simple_phone_single_car_widget';
    const TEMPLATE = 'car/single/simple_phone';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Phone (Simple)', 'vehica-core');
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

        $this->addTextAlignControl(
            'vehica_align',
            '.vehica-user-simple-phone-wrapper'
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'simple_phone_typography',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-user-simple-phone'
            ]
        );

        $this->addTextColorControl(
            'simple_phone_color',
            '.vehica-user-simple-phone'
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'simple_phone_typography_hover',
                'label' => esc_html__('Typography (Hover)', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-user-simple-phone:hover'
            ]
        );

        $this->addTextColorControl(
            'simple_phone_color_hover',
            '.vehica-user-simple-phone:hover',
            esc_html__('Color (Hover)', 'vehica-core')
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}