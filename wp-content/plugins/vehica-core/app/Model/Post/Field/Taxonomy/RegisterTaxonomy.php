<?php

namespace Vehica\Model\Post\Field\Taxonomy;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Car;

/**
 * Class RegisterTaxonomy
 * @package Vehica\Model\Post\Field\Taxonomy
 */
class RegisterTaxonomy
{
    /**
     * @param Taxonomy $taxonomy
     */
    public function register(Taxonomy $taxonomy)
    {
        register_taxonomy(
            $taxonomy->getKey(),
            Car::POST_TYPE,
            [
                'labels' => $this->getLabels(
                    $taxonomy->getName(),
                    $taxonomy->getName()
                ),
                'hierarchical' => false,
                'rewrite' => [
                    'slug' => $taxonomy->getRewrite(),
                    'with_front' => true,
                    'hierarchical' => false,
                    'ep_mask' => EP_NONE
                ],
                'show_in_rest' => true,
                'rest_base' => $taxonomy->getKey(),
                'show_in_quick_edit' => false,
                'meta_box_cb' => false
            ]
        );
    }

    /**
     * @param string $name
     * @param string $singularName
     * @return array
     */
    public function getLabels($name, $singularName)
    {
        return [
            'name' => $name,
            'singular_name' => $singularName,
            'search_items' => sprintf(esc_html__('Search %s', 'vehica-core'), $name),
            'popular_items' => sprintf(esc_html__('Popular %s', 'vehica-core'), $name),
            'all_items' => sprintf(esc_html__('All %s', 'vehica-core'), $name),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => sprintf(esc_html__('Edit %s', 'vehica-core'), $singularName),
            'update_item' => sprintf(esc_html__('Update %s', 'vehica-core'), $singularName),
            'add_new_item' => sprintf(esc_html__('Add New %s', 'vehica-core'), $singularName),
            'new_item_name' => sprintf(esc_html__('New %s Name', 'vehica-core'), $singularName),
            'separate_items_with_commas' => sprintf(esc_html__('Separate %s with commas', 'vehica-core'), $name),
            'add_or_remove_items' => sprintf(esc_html__('Add or remove %s', 'vehica-core'), $name),
            'choose_from_most_used' => sprintf(esc_html__('Choose from the most used %s', 'vehica-core'), $name),
            'not_found' => sprintf(esc_html__('No %s found.', 'vehica-core'), $name),
            'menu_name' => $name,
        ];
    }

}