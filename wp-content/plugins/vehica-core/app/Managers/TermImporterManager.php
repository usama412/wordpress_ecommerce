<?php


namespace Vehica\Managers;


use Cocur\Slugify\Slugify;
use Vehica\Core\Manager;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use WP_Term;

/**
 * Class TermImporterManager
 * @package Vehica\Managers
 */
class TermImporterManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_term_importer', [$this, 'import']);
    }

    public function import()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['parent_taxonomy'])) {
            wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
            exit;
        }

        $parentTaxonomy = Taxonomy::getById($_POST['parent_taxonomy']);

        if (!$parentTaxonomy instanceof Taxonomy) {
            wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
            exit;
        }

        foreach ($this->getParentTerms() as $index => $parentTerm) {
            $parentTerm = trim($parentTerm);
            if (empty($parentTerm)) {
                continue;
            }

            $parentTermId = $this->addTerm($parentTerm, $parentTaxonomy);

            foreach ($_POST['child_taxonomy'] as $key => $childTaxonomyKey) {
                $childTaxonomy = Taxonomy::getById($childTaxonomyKey);
                if ($childTaxonomy) {
                    $childTerms = explode("\n", str_replace("\r", "", $_POST['child_terms'][$key]));
                } else {
                    $childTerms = [];
                }

                if (empty($childTerms[$index])) {
                    continue;
                }

                $this->addChildTerm(trim($childTerms[$index]), $childTaxonomy, $parentTermId);
            }
        }

        wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
        exit;
    }

    /**
     * @return array
     */
    private function getParentTerms()
    {
        $terms = explode("\n", str_replace("\r", "", $_POST['parent_terms']));

        if (!is_array($terms)) {
            return [];
        }

        return $terms;
    }

    /**
     * @param string $term
     * @param Taxonomy $taxonomy
     * @return int
     */
    private function addTerm($term, Taxonomy $taxonomy)
    {
        $wpTerm = get_term_by('name', $term, $taxonomy->getKey());

        if ($wpTerm instanceof WP_Term) {
            return $wpTerm->term_id;
        }

        $termData = wp_insert_term($term, $taxonomy->getKey());
        if (is_wp_error($termData)) {
            return 0;
        }

        return $termData['term_id'];
    }

    /**
     * @param string $term
     * @param Taxonomy $taxonomy
     * @param int $parentTermId
     * @param int $counter
     */
    private function addChildTerm($term, Taxonomy $taxonomy, $parentTermId, $counter = 1)
    {
        $name = $term;

        if ($counter > 1) {
            $name .= ' ' . $counter;
        }

        $counter++;

        $termData = wp_insert_term($term, $taxonomy->getKey(), [
            'slug' => Slugify::create()->slugify($name)
        ]);

        if (is_wp_error($termData)) {
            $this->addChildTerm($term, $taxonomy, $parentTermId, $counter);
            return;
        }

        update_term_meta($termData['term_id'], Term::PARENT_TERM, $parentTermId);
    }

}