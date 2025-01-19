<?php

namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;

/**
 * Trait CarListPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait CarListPartialWidget
{

    protected function addColumnsControls()
    {
        $this->add_responsive_control(
            'vehica_car_list_per_row',
            [
                'label' => esc_html__('Columns', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1of1' => esc_html__('1', 'vehica-core'),
                    '1of2' => esc_html__('2', 'vehica-core'),
                    '1of3' => esc_html__('3', 'vehica-core'),
                    '1of4' => esc_html__('4', 'vehica-core'),
                    '1of5' => esc_html__('5', 'vehica-core'),
                    '1of6' => esc_html__('6', 'vehica-core'),
                ],
                'desktop_default' => '1of3',
                'tablet_default' => '1of2',
                'mobile_default' => '1of1',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'vehica_car_list_column_gap',
            [
                'label' => esc_html__('Gap (px)', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-grid__element' => 'padding-right: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vehica-grid' => ' margin-right: -{{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );
    }

    protected function addColumnAttributes()
    {
        $columnClass = [
            'vehica-grid__element',
            'vehica-grid__element--' . $this->get_settings_for_display('vehica_car_list_per_row'),
            'vehica-grid__element--tablet-' . $this->get_settings_for_display('vehica_car_list_per_row_tablet'),
            'vehica-grid__element--mobile-' . $this->get_settings_for_display('vehica_car_list_per_row_mobile'),
        ];

        $this->add_render_attribute('column', 'class', implode(' ', $columnClass));
    }

}