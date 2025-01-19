<?php

namespace Vehica\Core\PostType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class RegisterPostType
 *
 * @package Vehica\Core\PostType
 */
class RegisterPostType
{
    /**
     * @param PostTypable $postTypable
     */
    public function registerDynamic(PostTypable $postTypable)
    {
        $this->register($postTypable->getPostTypeData());
    }

    /**
     * @param PostTypeData $postTypeData
     */
    public function register(PostTypeData $postTypeData)
    {
        $args = [
            'labels' => $this->getLabels($postTypeData->getName(), $postTypeData->getSingularName()),
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => vehicaApp('menu_slug'),
            'menu_position' => 3,
            'query_var' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title', 'excerpt'],
            'taxonomies' => $postTypeData->getTaxonomies(),
            'map_meta_cap' => true,
            'capability_type' => 'post'
        ];

        foreach ($postTypeData->getOptions() as $key => $option) {
            $args[$key] = $option;
        }

        register_post_type($postTypeData->getKey(), $args);
    }

    /**
     * @param $name
     * @param $singularName
     *
     * @return array
     */
    public function getLabels($name, $singularName)
    {
        return [
            'name' => $name,
            'singular_name' => $singularName,
            'menu_name' => $name,
            'name_admin_bar' => $singularName,
            'add_new' => sprintf(esc_html__('Add New %s', 'vehica-core'), $singularName),
            'add_new_item' => sprintf(esc_html__('Add New %s', 'vehica-core'), $singularName),
            'new_item' => sprintf(esc_html__('New %s', 'vehica-core'), $singularName),
            'edit_item' => sprintf(esc_html__('Edit %s', 'vehica-core'), $singularName),
            'view_item' => sprintf(esc_html__('View %s', 'vehica-core'), $singularName),
            'all_items' => $name,
            'search_items' => sprintf(esc_html__('Search %s', 'vehica-core'), $name),
            'parent_item_colon' => sprintf(esc_html__('Parent %s:', 'vehica-core'), $name),
            'not_found' => sprintf(esc_html__('No %s found.', 'vehica-core'), $name),
            'not_found_in_trash' => sprintf(esc_html__('No %s found in Trash.', 'vehica-core'), $name)
        ];
    }

}