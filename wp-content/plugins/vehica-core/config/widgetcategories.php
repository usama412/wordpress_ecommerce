<?php

if (!defined('ABSPATH')) {
    exit;
}

return [
    \Vehica\Widgets\WidgetCategory::POST_SINGLE => [
        'name' => esc_html__('Vehica - Post Single', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::POST_ARCHIVE => [
        'name' => esc_html__('Vehica - Blog', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::CAR_SINGLE => [
        'name' => esc_html__('Vehica - Listing Page', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::CAR_ARCHIVE => [
        'name' => esc_html__('Vehica - Search', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::USER => [
        'name' => esc_html__('Vehica - User', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::GENERAL => [
        'name' => esc_html__('Vehica - General', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ],
    \Vehica\Widgets\WidgetCategory::LAYOUT => [
        'name' => esc_html__('Vehica - Layout', 'vehica-core'),
        'icon' => 'fa fa-plug'
    ]
];