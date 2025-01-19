<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Relation\FieldRelation;
use Vehica\Model\Term\Term;
use WP_Term;

/**
 * Class TermManager
 * @package Vehica\Managers
 */
class TermManager extends Manager
{

    public function boot()
    {
        if (is_admin() && current_user_can('manage_options')) {
            vehicaApp('taxonomies')->each(function ($taxonomy) {
                /* @var Taxonomy $taxonomy */
                add_action($taxonomy->getKey() . '_add_form_fields', [$this, 'createTermFields']);
                add_action($taxonomy->getKey() . '_edit_form_fields', [$this, 'editTermFields']);
            });

            add_action('admin_menu', [$this, 'resetTermCount']);
        }

        add_action('edit_term', [$this, 'updateTerm']);

        add_action('create_term', [$this, 'createTerm']);

        add_action('save_post_' . Car::POST_TYPE, [$this, 'savePostTerms']);

        add_action('save_post_' . Car::POST_TYPE, [$this, 'resetTermCount']);

        add_filter('term_link', [$this, 'termLink'], 10, 3);

        add_action('admin_init', static function () {
            foreach (vehicaApp('taxonomies') as $taxonomy) {
                /* @var Taxonomy $taxonomy */
                if (!$taxonomy->hasParentTaxonomy()) {
                    continue;
                }

                $parentTaxonomies = $taxonomy->getParentTaxonomies();
                if (!$parentTaxonomies) {
                    continue;
                }

                add_filter('manage_edit-' . $taxonomy->getKey() . '_columns', static function () use ($parentTaxonomies) {
                    $column = [
                        'cb' => '<input type="checkbox" />',
                        'name' => esc_html__('Name', 'vehica-core'),
                    ];

                    foreach ($parentTaxonomies as $parentTaxonomy) {
                        /* @var Taxonomy $parentTaxonomy */
                        $column[$parentTaxonomy->getKey()] = $parentTaxonomy->getName();
                    }

                    $column['description'] = esc_html__('Description', 'vehica-core');
                    $column['slug'] = esc_html__('Slug', 'vehica-core');
                    $column['posts'] = esc_html__('Count', 'vehica-core');

                    return $column;
                });

                foreach ($parentTaxonomies as $parentTaxonomy) {
                    add_filter('manage_' . $taxonomy->getKey() . '_custom_column', static function ($content, $columnName, $termId) use ($parentTaxonomy) {
                        if ($columnName !== $parentTaxonomy->getKey()) {
                            return $content;
                        }

                        $term = Term::getById($termId);
                        if (!$term || !$term->hasParentTerm()) {
                            return esc_html__('Not set', 'vehica-core');
                        }

                        $parentTerms = $term->getParentTerms()->filter(static function ($parentTerm) use ($parentTaxonomy) {
                            /* @var Term $parentTerm */
                            return $parentTerm->getTaxonomyKey() === $parentTaxonomy->getKey();
                        });

                        if ($parentTerms->isEmpty()) {
                            return esc_html__('Not set', 'vehica-core');
                        }

                        return $parentTerms->map(static function ($parentTerm) {
                            /* @var Term $parentTerm */
                            return $parentTerm->getName();
                        })->implode();
                    }, 10, 3);
                }
            }
        });
    }

    public function resetTermCount()
    {
        delete_transient('vehica_term_count');
    }

    /**
     * @param string $link
     * @param WP_Term $term
     * @param string $taxonomyKey
     * @return mixed
     */
    public function termLink($link, WP_Term $term, $taxonomyKey)
    {
        if (!in_array($taxonomyKey, vehicaApp('taxonomies_keys'), true)) {
            return $link;
        }

        return Term::make($term)->getUrl();
    }

    /**
     * @param int $postId
     */
    public function savePostTerms($postId)
    {
        vehicaApp('taxonomies')->each(static function ($taxonomy) use ($postId) {
            /* @var Taxonomy $taxonomy */
            if (isset($_POST[$taxonomy->getKey()])) {
                wp_set_object_terms($postId, $_POST[$taxonomy->getKey()], $taxonomy->getKey());
            }
        });
    }

    /**
     * @param int $termId
     * @noinspection DuplicatedCode
     */
    public function updateTerm($termId)
    {
        if (!isset($_POST['vehica_nonce']) || !current_user_can('edit_term', $termId) || !wp_verify_nonce($_POST['vehica_nonce'], 'vehica_update_term')) {
            return;
        }

        $term = Term::getById($termId);
        $taxonomyKeys = vehicaApp('car_fields')->filter(static function ($field) {
            return $field instanceof Taxonomy;
        })->map(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey();
        })->all();

        if (!$term instanceof Term || $term instanceof Currency || !in_array($term->getTaxonomyKey(), $taxonomyKeys, true)) {
            return;
        }

        $term->update($_POST);

        if ($term->getTaxonomy()->isFieldsDependencyEnabled()) {
            $term->saveRelations($_POST);
        }
    }

    /**
     * @param $termId
     * @noinspection DuplicatedCode
     */
    public function createTerm($termId)
    {
        if (empty($_POST['vehica_create_term']) || !current_user_can('edit_term', $termId)) {
            return;
        }

        $term = Term::getById($termId);
        $taxonomyKeys = vehicaApp('car_fields')->filter(static function ($field) {
            return $field instanceof Taxonomy;
        })->map(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey();
        })->all();

        if (!$term instanceof Term || $term instanceof Currency || !in_array($term->getTaxonomyKey(), $taxonomyKeys, true)) {
            return;
        }

        $term->update($_POST);

        if (!$term->getTaxonomy()->isFieldsDependencyEnabled()) {
            return;
        }

        vehicaApp('usable_car_fields')->each(static function ($field) use ($term) {
            /* @var Field $field */
            $key = 'vehica_relation_' . $field->getId();
            $value = isset($_POST[$key]) ? (int)$_POST[$key] : 0;

            $relation = new FieldRelation($term, $field);
            $relation->setValue($value);
        });
    }

    /**
     * @param string $taxonomyKey
     */
    public function createTermFields($taxonomyKey)
    {
        $vehicaTaxonomy = Taxonomy::getByKey($taxonomyKey);
        if (!$vehicaTaxonomy) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        require vehicaApp('views_path') . 'term/create_term.php';
    }

    /**
     * @param WP_Term $term
     */
    public function editTermFields(WP_Term $term)
    {
        $vehicaTerm = new Term($term);
        $vehicaTaxonomy = $vehicaTerm->getTaxonomy();
        if (!$vehicaTaxonomy) {
            return;
        }

        /** @noinspection PhpIncludeInspection */
        require vehicaApp('views_path') . 'term/edit_term.php';
    }

}