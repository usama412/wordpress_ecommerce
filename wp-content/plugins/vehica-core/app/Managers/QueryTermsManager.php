<?php

namespace Vehica\Managers;

use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Term\Term;

class QueryTermsManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica/terms/query', [$this, 'query']);
        add_action('admin_post_nopriv_vehica/terms/query', [$this, 'query']);
    }

    public function query()
    {
        $taxonomy = $this->getTaxonomy();
        $parentTerms = $this->getParentTermIds();

        if (empty($parentTerms) || empty($taxonomy)) {
            echo json_encode(['terms' => []]);
            return;
        }

        echo json_encode(['terms' => $this->getTerms($taxonomy, $parentTerms)]);
    }

    /**
     * @param string $taxonomy
     * @return array
     */
    private function getTerms($taxonomy, $parentTerms)
    {
        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        if (!is_array($terms) || empty($terms)) {
            return [];
        }

        return Collection::make($terms)
            ->map(static function ($term) {
                return new Term($term);
            })->filter(static function ($term) use ($parentTerms) {
                /* @var Term $term */
                $parentTermIds = $term->getParentTermIds();

                foreach ($parentTerms as $parentTerm) {
                    if (in_array($parentTerm, $parentTermIds, true)) {
                        return true;
                    }
                }

                return false;
            })
            ->values()
            ->all();
    }

    /**
     * @return mixed|string
     */
    private function getTaxonomy()
    {
        return isset($_POST['taxonomy']) ? $_POST['taxonomy'] : '';
    }

    /**
     * @return array
     */
    private function getParentTermIds()
    {
        if (!isset($_POST['parentTerms']) || empty($_POST['parentTerms'])) {
            return [];
        }

        return Collection::make($_POST['parentTerms'])
            ->map(static function ($termId) {
                return (int)$termId;
            })->filter(static function ($termId) {
                return !empty($termId);
            })->all();
    }

}