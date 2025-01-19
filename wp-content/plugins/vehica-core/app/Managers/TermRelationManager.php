<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use WP_Post;
use WP_Query;

/**
 * Class TermRelationManager
 * @package Vehica\Managers
 */
class TermRelationManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_connect_terms', [$this, 'init']);

        add_action('admin_post_vehica_listing_ids', [$this, 'listingIds']);

        add_action('vehica/terms/connect', [$this, 'check']);

        add_action('admin_post_vehica/terms/backendConnect', [$this, 'backendConnect']);

        add_action('save_post', function ($postId, WP_Post $post) {
            if (!apply_filters('vehica/connectTermsOnSave', true)) {
                return;
            }

            if ($post->post_type !== Car::POST_TYPE) {
                return;
            }

            $this->check();
        }, 10, 2);

        add_action('vehica/car/created', [$this, 'connectCarTerms']);
        add_action('vehica/car/updated', [$this, 'connectCarTerms']);
    }

    /**
     * @param  Car  $car
     * @return void
     */
    public function connectCarTerms(Car $car)
    {
        vehicaApp('child_taxonomies')->each(function ($childTaxonomy) use ($car) {
            $this->connect($car, $childTaxonomy);
        });
    }

    public function backendConnect()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $ids = $_POST['ids'];
        if (empty($ids) || !is_array($ids)) {
            return;
        }

        foreach ($ids as $id) {
            $car = new Car(get_post($id));

            vehicaApp('child_taxonomies')->each(function ($childTaxonomy) use ($car) {
                $this->connect($car, $childTaxonomy);
            });
        }

        echo json_encode(['success' => true]);
    }

    public function listingIds()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $query = new WP_Query([
            'fields' => 'ids',
            'no_found_rows' => true,
            'post_type' => Car::POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        echo json_encode($query->posts);
    }

    public function init()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $this->check();

        wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
        exit;
    }

    public function check()
    {
        $query = new WP_Query([
            'fields' => 'ids',
            'no_found_rows' => true,
            'post_type' => Car::POST_TYPE,
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ]);

        foreach ($query->posts as $postId) {
            $car = new Car(get_post($postId));

            vehicaApp('child_taxonomies')->each(function ($childTaxonomy) use ($car) {
                $this->connect($car, $childTaxonomy);
            });
        }
    }

    /**
     * @param  Car  $car
     * @param  Taxonomy  $childTaxonomy
     */
    private function connect(Car $car, Taxonomy $childTaxonomy)
    {
        $parentTaxonomies = $childTaxonomy->getParentTaxonomies();
        foreach ($parentTaxonomies as $parentTaxonomy) {
            if (!$parentTaxonomy) {
                continue;
            }

            $parentTerms = $car->getTerms($parentTaxonomy);

            $car->getTerms($childTaxonomy)->each(static function ($childTerm) use ($parentTerms) {
                /* @var Term $childTerm */
                if ($childTerm->hasParentTerm()) {
                    return;
                }

                $childTerm->setParentTerm($parentTerms->map(static function ($parentTerm) {
                    /* @var Term $parentTerm */
                    return $parentTerm->getId();
                })->all());
            });
        }
    }

}