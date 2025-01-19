<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use WP_Term;

class ListingsAdminTableManager extends Manager
{
    public function boot()
    {
        add_action('restrict_manage_posts', [$this, 'addFilters']);

        add_filter('parse_query', [$this, 'parseQuery']);
    }

    public function addFilters()
    {
        global $typenow;

        if ($typenow !== Car::POST_TYPE) {
            return;
        }

        foreach (vehicaApp('settings_config')->getListingsTableTaxonomies() as $taxonomy) {
            $selected = $_GET[$taxonomy->getKey()] ?? '';
            $infoTaxonomy = get_taxonomy($taxonomy->getKey());

            wp_dropdown_categories(array(
                'show_option_all' => __("Show all {$infoTaxonomy->label}"),
                'taxonomy' => $taxonomy->getKey(),
                'name' => $taxonomy->getKey(),
                'orderby' => 'name',
                'selected' => $selected,
                'show_count' => true,
                'hide_empty' => false,
            ));
        }
    }

    public function parseQuery($query)
    {
        global $pagenow;
        $queryVars = &$query->query_vars;

        if (!isset($queryVars['post_type']) || $queryVars['post_type'] !== Car::POST_TYPE) {
            return;
        }

        foreach (vehicaApp('settings_config')->getListingsTableTaxonomies() as $taxonomy) {
            if (
                isset($queryVars[$taxonomy->getKey()])
                && $pagenow === 'edit.php'
                && is_numeric($queryVars[$taxonomy->getKey()])
                && $queryVars[$taxonomy->getKey()] !== 0
            ) {
                $term = get_term_by('id', $queryVars[$taxonomy->getKey()], $taxonomy->getKey());
                if ($term instanceof WP_Term) {
                    $queryVars[$taxonomy->getKey()] = $term->slug;
                }
            }
        }
    }
}