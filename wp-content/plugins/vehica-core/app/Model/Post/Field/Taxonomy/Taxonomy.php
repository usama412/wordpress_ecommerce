<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field\Taxonomy;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\Attribute;
use Vehica\Attribute\AttributeValue;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\Rewrite\Rewritable;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\RewritableField;
use Vehica\Model\Post\Post;
use Vehica\Model\Term\Relation\FieldRelation;
use Vehica\Model\Term\Relation\Relation;
use Vehica\Model\Term\Relation\TaxonomyRelation;
use Vehica\Model\Term\Term;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Field\TaxonomySearchField;
use Vehica\Search\Searchable;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Post;
use WP_Query;

/**
 * Class Taxonomy
 * @package Vehica\Model\Post\Field\Taxonomy
 */
class Taxonomy extends Field implements Attribute, SimpleTextAttribute, SearchFilter, Searchable, UrlModifier, RewritableField
{
    use Rewritable;

    const KEY = 'taxonomy';
    const ALLOW_MULTIPLE = 'vehica_allow_multiple';
    const PARENT_TAXONOMY = 'vehica_parent_taxonomy';
    const FIELDS_DEPENDENCY = 'vehica_fields_dependency';
    const COMPARE_LOGIC = 'vehica_compare_logic';
    const COMPARE_LOGIC_AND = 'compare_logic_and';
    const COMPARE_LOGIC_OR = 'compare_logic_or';
    const PANEL_TERMS = 'vehica_panel_terms';
    const ALLOW_NEW_VALUES = 'vehica_allow_new_values';

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            self::ALLOW_MULTIPLE,
            self::FIELDS_DEPENDENCY,
            self::PARENT_TAXONOMY,
            self::COMPARE_LOGIC,
            self::PANEL_PLACEHOLDER,
            self::PANEL_TERMS,
            self::ALLOW_NEW_VALUES,
            Rewrite::REWRITE,
        ];
    }

    /**
     * @return string
     */
    public function getPostTypeKey()
    {
        return self::POST_TYPE;
    }

    /**
     * @param array $parentTaxonomy
     */
    public function setParentTaxonomy($parentTaxonomy)
    {
        if (!is_array($parentTaxonomy)) {
            $parentTaxonomy = [(int)$parentTaxonomy];
        }

        $this->setMeta(self::PARENT_TAXONOMY, $parentTaxonomy);
    }

    /**
     * @return bool
     */
    public function hasParentTaxonomy()
    {
        return $this->getParentTaxonomies()->isNotEmpty();
    }

    /**
     * @return array
     */
    public function getParentTaxonomyIds()
    {
        $ids = $this->getMeta(self::PARENT_TAXONOMY);
        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return Collection::make($ids)->map(static function ($id) {
            return (int)$id;
        })->filter(static function ($id) {
            return !empty($id) && in_array($id, vehicaApp('taxonomy_ids'), true);
        })->all();
    }

    /**
     * @param Taxonomy|int $taxonomy
     *
     * @return bool
     */
    public function isParentTaxonomy($taxonomy)
    {
        $parentTaxonomyIds = $this->getParentTaxonomyIds();

        if (empty($parentTaxonomyIds)) {
            return false;
        }

        if ($taxonomy instanceof self) {
            return in_array($taxonomy->getId(), $parentTaxonomyIds, true);
        }

        return in_array((int)$taxonomy, $parentTaxonomyIds, true);
    }

    /**
     * @param Taxonomy $taxonomy
     *
     * @return bool
     */
    public function isChildTaxonomy($taxonomy)
    {
        return in_array($this->getId(), $taxonomy->getParentTaxonomyIds(), true);
    }

    /**
     * @return Collection
     */
    public function getParentTaxonomies()
    {
        $parentTaxonomyIds = $this->getParentTaxonomyIds();
        if (empty($parentTaxonomyIds)) {
            return Collection::make();
        }

        return Collection::make($parentTaxonomyIds)->map(static function ($parentTaxonomyId) {
            return self::getById($parentTaxonomyId);
        })->filter(static function ($parentTaxonomy) {
            /* @var Taxonomy $parentTaxonomy */
            return $parentTaxonomy !== false;
        });
    }

    /**
     * @param int $id
     *
     * @return Taxonomy|false
     */
    public static function getById($id)
    {
        return vehicaApp('taxonomy_' . $id);
    }

    /**
     * @param int $allowMultiple
     */
    public function setAllowMultiple($allowMultiple)
    {
        $allowMultiple = (int)$allowMultiple;
        $this->setMeta(self::ALLOW_MULTIPLE, $allowMultiple);
    }

    /**
     * @return bool
     */
    public function allowMultiple()
    {
        $allowMultiple = $this->getMeta(self::ALLOW_MULTIPLE);

        if ($allowMultiple === '') {
            return false;
        }

        return !empty($allowMultiple);
    }

    /**
     * @return array
     */
    public function getPostTypeKeys()
    {
        global $wp_taxonomies;
        if (!isset($wp_taxonomies[$this->getKey()])) {
            return [];
        }

        return $wp_taxonomies[$this->getKey()]->object_type;
    }

    /**
     * @param WP_Post $post
     *
     * @return Taxonomy
     */
    public static function get(WP_Post $post)
    {
        return new self($post);
    }

    /**
     * @param array $args
     *
     * @return Collection
     */
    public function getTerms($args = [])
    {
        $params = [
                'taxonomy' => $this->getKey(),
                'hide_empty' => false,
            ] + $args;

        $wpTerms = get_terms($params);

        if (!is_array($wpTerms)) {
            return Collection::make();
        }

        return Collection::make($wpTerms)
            ->map(static function ($wpTerm) {
                return new Term($wpTerm);
            });
    }

    /**
     * @return array
     */
    public function getTermsList()
    {
        $terms = [];
        foreach ($this->getTerms() as $term) {
            $terms[$term->getId()] = $term->getName();
        }

        return $terms;
    }

    /**
     * @param Term $term
     *
     * @return Collection
     */
    public function getCarFieldsRelations(Term $term)
    {
        return vehicaApp('car_fields')->filter(function ($field) {
            /* @var Field $field */
            return $field->getId() !== $this->getId();
        })->map(static function ($field) use ($term) {
            return new FieldRelation($term, $field);
        });
    }

    /**
     * @param Term $term
     *
     * @return Collection
     */
    public function getCarTaxonomyRelations(Term $term)
    {
        return vehicaApp('taxonomies')->map(static function ($taxonomy) use ($term) {
            return new TaxonomyRelation($term, $taxonomy);
        })->filter(static function ($taxonomyRelation) use ($term) {
            /* @var TaxonomyRelation $taxonomyRelation */
            return $taxonomyRelation->getTaxonomy()->getId() !== $term->getTaxonomy()->getId();
        });
    }

    /**
     * @param Term $term
     *
     * @return Collection
     */
    public function getRelations(Term $term)
    {
        return $this->getCarFieldsRelations($term);
    }

    /**
     * @param Term $term
     *
     * @return Collection
     */
    public function getFieldsRelations(Term $term)
    {
        return $this->getCarFieldsRelations($term);
    }

    /**
     * @param Term $term
     *
     * @return Collection
     */
    public function getTaxonomyRelations(Term $term)
    {
        return $this->getCarTaxonomyRelations($term);
    }

    /**
     * @return bool
     */
    public function isFieldsDependencyEnabled()
    {
        $isEnabled = $this->getMeta(self::FIELDS_DEPENDENCY);

        return !empty($isEnabled);
    }

    /**
     * @param int $fieldsDependency
     */
    public function setFieldsDependency($fieldsDependency)
    {
        $fieldsDependency = (int)$fieldsDependency;
        $this->setMeta(self::FIELDS_DEPENDENCY, $fieldsDependency);
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'parentTaxonomy' => $this->getParentTaxonomyIds(),
            'rewrite' => $this->getRewrite(),
            'allowMultiple' => $this->allowMultiple(),
            'fieldsDependencyEnabled' => $this->isFieldsDependencyEnabled(),
            'create' => apply_filters('vehica/backend/listing/taxonomy/create', apply_filters('vehica/backend/listing/taxonomy/' . $this->getId() . '/create', true)),
            'allowNewValues' => $this->allowNewValues(),
            'parent' => $this->getParentTaxonomyIds(),
        ];
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return get_rest_url() . 'wp/v2/' . $this->getKey();
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Collection
     */
    public function getSimpleTextValues(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return Collection::make();
        }

        /* @var Car $fieldsUser */
        $terms = get_the_terms($fieldsUser->model, $this->getKey());
        if (!is_array($terms)) {
            return Collection::make();
        }

        return Collection::make($terms)->map(static function ($wpTerm) {
            $term = new Term($wpTerm);

            return new SimpleTextValue($term->getName(), $term->getUrl());
        });
    }

    /**
     * @param array $config
     *
     * @return SearchField|TaxonomySearchField
     */
    public function getSearchField($config)
    {
        return new TaxonomySearchField($config, $this);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $rewrite = $this->getRewrite();

        if (empty($_GET[$rewrite])) {
            return [];
        }

        return [$rewrite => $_GET[$rewrite]];
    }

    /**
     * @param array $parameters
     *
     * @return string|false
     */
    public function getArchiveUrlPartial($parameters)
    {
        $rewrite = $this->getRewrite();

        if (empty($parameters[$rewrite])) {
            return false;
        }

        $values = $parameters[$rewrite];
        if (!is_array($values)) {
            return $rewrite . '=' . $values;
        }

        /** @noinspection ImplodeMissUseInspection */
        return implode('&', Collection::make($values)->map(static function ($value) use ($rewrite) {
            return $rewrite . '[]=' . $value;
        })->all());
    }

    /**
     * @param array $parameters
     *
     * @return array|false
     */
    public function getInitialSearchParams($parameters)
    {
        $rewrite = $this->getRewrite();

        if (!isset($parameters[$rewrite])) {
            return false;
        }

        $terms = $this->getTerms([
            'slug' => $parameters[$rewrite]
        ])->map(static function ($term) {
            /* @var Term $term */
            return [
                'id' => $term->getId(),
                'key' => $term->getKey(),
                'name' => $term->getName(),
                'value' => $term->getSlug(),
                'link' => $term->getUrl(),
                'taxonomy' => $term->getTaxonomyKey(),
                'relations' => $term->getRelations()->map(static function ($relation) {
                    /* @var Relation $relation */
                    if (!$relation->isChecked()) {
                        return false;
                    }

                    return $relation->getParamKey();
                })->filter(static function ($relation) {
                    return $relation !== false;
                }),
            ];
        })->all();

        if (empty($terms)) {
            return false;
        }

        return [
            [
                'id' => $this->getId(),
                'key' => $this->getKey(),
                'rewrite' => $this->getRewrite(),
                'name' => $this->getName(),
                'values' => $terms,
                'type' => self::KEY,
            ]
        ];
    }

    /**
     * @param array $parameters
     *
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        if (!isset($parameters[$this->getRewrite()])) {
            return false;
        }

        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            'tax_query' => [
                [
                    'taxonomy' => $this->getKey(),
                    'terms' => $parameters[$this->getRewrite()],
                    'field' => 'slug',
                    'operator' => $this->isCompareLogicOr() ? 'IN' : 'AND'
                ]
            ]
        ]);

        return $query->posts;
    }

    /**
     * @param string $compareLogic
     */
    public function setCompareLogic($compareLogic)
    {
        if (empty($compareLogic) || ($compareLogic !== self::COMPARE_LOGIC_OR && $compareLogic !== self::COMPARE_LOGIC_AND)) {
            $compareLogic = self::COMPARE_LOGIC_OR;
        }

        $this->setMeta(self::COMPARE_LOGIC, $compareLogic);
    }

    /**
     * @return string
     */
    public function getCompareLogic()
    {
        $compareLogic = $this->getMeta(self::COMPARE_LOGIC);

        if (empty($compareLogic) || ($compareLogic !== self::COMPARE_LOGIC_OR && $compareLogic !== self::COMPARE_LOGIC_AND)) {
            return self::COMPARE_LOGIC_OR;
        }

        return $compareLogic;
    }

    /**
     * @return bool
     */
    public function isCompareLogicAnd()
    {
        return $this->getCompareLogic() === self::COMPARE_LOGIC_AND;
    }

    /**
     * @return bool
     */
    public function isCompareLogicOr()
    {
        return $this->getCompareLogic() === self::COMPARE_LOGIC_OR;
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Collection
     */
    public function getAttributeValues(FieldsUser $fieldsUser)
    {
        if (!$fieldsUser instanceof Car) {
            return Collection::make();
        }

        return $fieldsUser->getTerms($this)->map(static function ($term) {
            /* @var Term $term */
            return AttributeValue::makeFromTerm($term);
        });
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'hierarchical' => false,
        ];
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param array $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        $termIds = [];

        if (!empty($value)) {
            foreach ($value as $termName) {
                $term = get_term_by('name', $termName, $this->getKey());
                if (!$term) {
                    continue;
                }

                $termIds[] = $term->term_id;
            }
        }

        wp_set_object_terms($fieldsUser->getId(), $termIds, $this->getKey());
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return \Vehica\Core\Field\Attribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new \Vehica\Core\Field\Attribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return array
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return [];
        }

        $key = $this->getKey() . '_' . $fieldsUser->getId();
        if (vehicaApp()->has($key)) {
            return vehicaApp($key);
        }

        $terms = wp_get_post_terms($fieldsUser->getId(), $this->getKey());
        if (is_wp_error($terms)) {
            return [];
        }

        return Collection::make($terms)->map(static function ($term) {
            return new Term($term);
        })->all();
    }

    /**
     * @param Collection $posts
     *
     * @return Collection
     */
    public function getPostsTerms(Collection $posts)
    {
        $postIds = $posts->map(static function ($post) {
            /* @var Post $post */
            return $post->getId();
        })->all();

        $terms = wp_get_object_terms($postIds, $this->getKey(), ['fields' => 'all_with_object_id']);

        if (is_wp_error($terms)) {
            return Collection::make();
        }

        return Collection::make($terms)->map(static function ($term) {
            return new Term($term);
        });
    }

    /**
     * @param array $terms
     */
    public function setPanelTerms($terms)
    {
        $this->setMeta(self::PANEL_TERMS, $terms);
    }

    /**
     * @return array
     */
    public function getPanelTermIds()
    {
        $ids = $this->getMeta(self::PANEL_TERMS);

        if (!is_array($ids)) {
            return [];
        }

        return Collection::make($ids)->map(static function ($id) {
            return (int)$id;
        })->all();
    }

    /**
     * @return Collection
     */
    public function getPanelTerms()
    {
        $termIds = $this->getPanelTermIds();
        if (empty($termIds)) {
            return Collection::make();
        }

        $terms = Term::getTerms($this, $this->getPanelTermIds());

        return Collection::make($termIds)->map(static function ($termId) use ($terms) {
            return $terms->find(static function ($term) use ($termId) {
                /* @var Term $term */
                return $term->getId() === $termId;
            });
        })->filter(static function ($term) {
            return $term !== false;
        });
    }

    /**
     * @return bool
     */
    public function isUsedForCardLabels()
    {
        foreach (vehicaApp('card_label_elements') as $cardElementKey) {
            if ($cardElementKey === $this->getKey()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public static function getAllTermList()
    {
        $terms = [];

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            foreach ($taxonomy->getTerms() as $term) {
                $terms[] = $term;
            }
        }

        usort($terms, static function ($a, $b) {
            return strcmp($a->getName(), $b->getName());
        });

        return $terms;
    }

    /**
     * @param int $allow
     */
    public function setAllowNewValues($allow)
    {
        $this->setMeta(self::ALLOW_NEW_VALUES, (int)$allow);
    }

    /**
     * @return bool
     */
    public function allowNewValues()
    {
        return !empty((int)$this->getMeta(self::ALLOW_NEW_VALUES));
    }

}