<?php

namespace Vehica\Model\Term\Relation;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;

/**
 * Class TaxonomyRelation
 * @package Vehica\Model\Term\Relation
 */
class TaxonomyRelation extends Relation
{
    /**
     * @var Taxonomy
     */
    protected $taxonomy;

    /**
     * TaxonomyRelation constructor.
     * @param Term $term
     * @param Taxonomy $taxonomy
     */
    public function __construct(Term $term, Taxonomy $taxonomy)
    {
        parent::__construct($term);

        $this->taxonomy = $taxonomy;
    }

    /**
     * @return string
     */
    public function getParamKey()
    {
        return Taxonomy::POST_TYPE . '_' . $this->taxonomy->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->taxonomy->getName();
    }

    /**
     * @return Taxonomy
     */
    public function getTaxonomy()
    {
        return $this->taxonomy;
    }

}