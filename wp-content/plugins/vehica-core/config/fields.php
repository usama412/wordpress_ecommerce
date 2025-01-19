<?php

if (!defined('ABSPATH')) {
    exit;
}

return [
    \Vehica\Model\Post\Field\TextField::class => [
        'name' => esc_html__('Text', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\TextField::KEY,
    ],
    \Vehica\Model\Post\Field\NumberField::class => [
        'name' => esc_html__('Number', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\NumberField::KEY
    ],
    \Vehica\Model\Post\Field\Taxonomy\Taxonomy::class => [
        'name' => esc_html__('Taxonomy', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\Taxonomy\Taxonomy::KEY
    ],
    \Vehica\Model\Post\Field\Price\PriceField::class => [
        'name' => esc_html__('Price', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\Price\PriceField::KEY
    ],
    \Vehica\Model\Post\Field\GalleryField::class => [
        'name' => esc_html__('Gallery', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\GalleryField::KEY
    ],
    \Vehica\Model\Post\Field\EmbedField::class => [
        'name' => esc_html__('Embed', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\EmbedField::KEY
    ],
    \Vehica\Model\Post\Field\DateTimeField::class => [
        'name' => esc_html__('Date', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\DateTimeField::KEY
    ],
    \Vehica\Model\Post\Field\HeadingField::class => [
        'name' => esc_html__('Heading', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\HeadingField::KEY
    ],
    \Vehica\Model\Post\Field\LocationField::class => [
        'name' => esc_html__('Location', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\LocationField::KEY
    ],
    \Vehica\Model\Post\Field\AttachmentsField::class => [
        'name' => esc_html__('Attachments', 'vehica-core'),
        'key' => \Vehica\Model\Post\Field\AttachmentsField::KEY
    ],
];