<?php

namespace Vehica\Managers;

use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Field\Taxonomy\RegisterTaxonomy;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use WP_Term_Query;

/**
 * Class TaxonomyManager
 * @package Vehica\Managers
 */
class TaxonomyManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'register'], 5);

        if (is_admin()) {
            add_action('wp_ajax_vehica_taxonomy_terms', [$this, 'taxonomyTerms']);
        }

        add_filter('rest_prepare_taxonomy', static function ($response, $currentTaxonomy) {
            $check = vehicaApp('taxonomies')->find(static function ($taxonomy) use ($currentTaxonomy) {
                /* @var Taxonomy $taxonomy */
                return $currentTaxonomy->name === $taxonomy->getKey();
            });

            if ($check) {
                $response->data['visibility']['show_ui'] = false;
            }

            return $response;
        }, 10, 2);
    }

    public function register()
    {
        $registerTaxonomy = new RegisterTaxonomy();
        vehicaApp('taxonomies')->each(static function ($taxonomy) use ($registerTaxonomy) {
            /* @var Taxonomy $taxonomy */
            $registerTaxonomy->register($taxonomy);
        });
    }

    public function taxonomyTerms()
    {
        if (empty($_GET['taxonomy'])) {
            wp_die();
        }

        $taxonomyKey = $_GET['taxonomy'];
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $query = new WP_Term_Query([
            'taxonomy' => $taxonomyKey,
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            'name__like' => $search
        ]);

        if (!is_array($query->terms)) {
            echo json_encode([]);
            wp_die();
        }

        $terms = Collection::make($query->terms)->map(static function ($term) {
            return new Term($term);
        });

        echo json_encode($terms);
        wp_die();
    }

}