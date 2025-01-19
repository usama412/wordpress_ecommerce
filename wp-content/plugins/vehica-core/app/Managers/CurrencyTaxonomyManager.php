<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Field\Field;

/**
 * Class CurrencyTaxonomyManager
 * @package Vehica\Managers
 */
class CurrencyTaxonomyManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'register']);
    }

    public function register()
    {
        register_taxonomy(Currency::CURRENCIES, Field::POST_TYPE, $this->getSettings());
    }

    /**
     * @return array
     */
    private function getSettings()
    {
        return [
            'labels' => $this->getLabels(),
            'query_var' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => false,
        ];
    }

    /**
     * @return array
     */
    private function getLabels()
    {
        return [
            'name' => esc_html__('Currencies', 'vehica-core'),
            'singular_name' => esc_html__('Currency', 'vehica-core'),
            'search_items' => esc_html__('Search Currencies', 'vehica-core'),
            'popular_items' => esc_html__('Popular Currencies', 'vehica-core'),
            'all_items' => esc_html__('All Currencies', 'vehica-core'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Currency', 'vehica-core'),
            'update_item' => esc_html__('Update Currency', 'vehica-core'),
            'add_new_item' => esc_html__('Add New Currency', 'vehica-core'),
            'new_item_name' => esc_html__('New Currency Name', 'vehica-core'),
            'separate_items_with_commas' => esc_html__('Separate currencies with commas', 'vehica-core'),
            'add_or_remove_items' => esc_html__('Add or remove currencies', 'vehica-core'),
            'choose_from_most_used' => esc_html__('Choose from the most used currencies', 'vehica-core'),
            'not_found' => esc_html__('No currencies found.', 'vehica-core'),
            'menu_name' => esc_html__('Currencies', 'vehica-core'),
        ];
    }

}