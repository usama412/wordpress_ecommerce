<?php

if (!defined('ABSPATH')) {
    exit;
}

return [
    \Vehica\Model\Post\Template\Template::class => [
        'name' => esc_html__('Templates', 'vehica-core'),
        'singular_name' => esc_html__('Template', 'vehica-core'),
        'key' => \Vehica\Model\Post\Template\Template::POST_TYPE,
        'is_custom' => true,
        'is_core' => true,
        'options' => [
            'supports' => ['title'],
            'query_var' => true,
            'publicly_queryable' => true,
        ],
        'taxonomies' => [],
    ],
    \Vehica\Model\Post\Field\Field::class => [
        'name' => esc_html__('Custom fields', 'vehica-core'),
        'singular_name' => esc_html__('Custom field', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\Field::POST_TYPE,
        'is_custom' => true,
        'is_core' => true,
        'options' => [],
        'taxonomies' => [],
    ],
    \Vehica\Model\Post\Config\Config::class => [
        'name' => esc_html__('Configs', 'vehica-core'),
        'singular_name' => esc_html__('Config', 'vehica-core'),
        'key' => \Vehica\Model\Post\Config\Config::POST_TYPE,
        'is_custom' => true,
        'is_core' => true,
        'taxonomies' => [],
        'options' => []
    ],
];