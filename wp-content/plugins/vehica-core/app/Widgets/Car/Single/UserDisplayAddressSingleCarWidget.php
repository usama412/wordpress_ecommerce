<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class UserDisplayAddressSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserDisplayAddressSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_display_address_single_car_widget';
    const TEMPLATE = 'car/single/user_display_address';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Display Address', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'vehicle_owner_style',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-user-address'
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-address' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__('Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-address i' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'show_icon',
            [
                'label' => esc_html__('Display Icon', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            'clear_icon_offset',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '0',
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-address' => 'padding-left: 0;'
                ],
                'condition' => [
                    'show_icon!' => '1'
                ]
            ]
        );

        $this->add_responsive_control(
            'icon_offset',
            [
                'label' => esc_html__('Icon Offset', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-address' => 'padding-left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'show_icon' => '1'
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return bool
     */
    public function showIcon()
    {
        $show = (int)$this->get_settings_for_display('show_icon');
        return !empty($show);
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}