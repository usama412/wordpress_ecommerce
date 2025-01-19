<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class UserRoleSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserRoleSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_role_single_car_widget';
    const TEMPLATE = 'car/single/user_role';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Type', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME . '_style',
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
                'selector' => '{{WRAPPER}} .vehica-user-role'
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-role' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__('Alignment', 'vehica-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'vehica-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vehica-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'vehica-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'vehica-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-role' => 'text-align: {{VALUE}};',
                ],
                'frontend_available' => true,
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