<?php

namespace Vehica\Managers;


use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use WP_Query;

/**
 * Class KeywordSearchManager
 * @package Vehica\Managers
 */
class KeywordSearchManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_nopriv_vehica_keyword', [$this, 'query']);
        add_action('admin_post_vehica_keyword', [$this, 'query']);

        add_action('save_post_' . Car::POST_TYPE, [$this, 'updateTermAliases']);
        add_action('edit_term', [$this, 'updateTermAliases']);
    }

    public function query()
    {
        if (empty($_GET['query'])) {
            echo json_encode([]);
            return;
        }

        if (empty($_POST['browse']) || !is_array($_POST['browse'])) {
            echo json_encode([]);
            return;
        }

        $query = (string)$_GET['query'];

        $groups = [];
        $browse = $_POST['browse'];

        Collection::make($browse)->each(static function ($taxonomyId) use ($query, &$groups) {
            if ($taxonomyId === 'cars') {
                return;
            }

            $taxonomy = vehicaApp('taxonomy_' . $taxonomyId);
            if (!$taxonomy) {
                return;
            }

            /* @var Taxonomy $taxonomy */
            $terms = get_terms([
                'taxonomy' => $taxonomy->getKey(),
                'hide_empty' => false,
                'number' => 5,
                'meta_query' => [
                    [
                        'key' => Term::ALIAS,
                        'value' => $query,
                        'compare' => 'LIKE'
                    ]
                ]
            ]);

            if (!is_array($terms)) {
                return;
            }

            $groups[] = [
                'group_name' => $taxonomy->getName(),
                'options' => Collection::make($terms)->map(static function ($term) {
                    $term = new Term($term);
                    return [
                        'id' => $term->getId(),
                        'name' => $term->getAlias(),
                        'url' => $term->getUrl()
                    ];
                })->all()
            ];
        });

        if (in_array('cars', $browse, true)) {
            $carsGroup = $this->getCarsGroup($query);
            if ($carsGroup) {
                $groups[] = $carsGroup;
            }
            unset($browse['cars']);
        }

        echo json_encode($groups);
    }

    public function updateTermAliases()
    {
        vehicaApp('taxonomies')->each(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            $terms = get_terms([
                'taxonomy' => $taxonomy->getKey(),
                'hide_empty' => false
            ]);

            if (!is_array($terms)) {
                return;
            }

            $taxonomy->getTerms()->each(static function ($term) {
                /* @var Term $term */
                $term->updateAlias();
            });
        });
    }

    /**
     * @param string $query
     * @return array|false
     */
    protected function getCarsGroup($query)
    {
        $carsQuery = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => 5,
            's' => $query
        ]);

        if (empty($carsQuery->posts)) {
            return false;
        }

        return [
            'group_name' => vehicaApp('vehicles_string'),
            'options' => Collection::make($carsQuery->posts)->map(static function ($post) {
                $car = new Car($post);
                return [
                    'id' => $car->getId(),
                    'name' => $car->getName(),
                    'url' => $car->getUrl()
                ];
            })
        ];
    }

}