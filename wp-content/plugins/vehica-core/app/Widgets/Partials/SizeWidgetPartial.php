<?php

namespace Vehica\Widgets\Partials;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;

/**
 * Trait SizeWidgetPartial
 * @package Vehica\Widgets\Partials
 */
trait SizeWidgetPartial
{
    use WidgetPartial;

    protected function addSizeControl()
    {
        $this->add_control(
            'size',
            [
                'label' => esc_html__('Size', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__('Default', 'vehica-core'),
                    'small' => esc_html__('Small', 'vehica-core'),
                    'medium' => esc_html__('Medium', 'vehica-core'),
                    'large' => esc_html__('Large', 'vehica-core'),
                    'xl' => esc_html__('XL', 'vehica-core'),
                    'xxl' => esc_html__('XXL', 'vehica-core'),
                ],
            ]
        );
    }

    protected function applySizeSetting()
    {
        $size = $this->get_settings_for_display('size');

        if (!empty($size)) {
            $this->add_render_attribute('title', 'class', 'vehica-size-' . $size);
        }
    }

}