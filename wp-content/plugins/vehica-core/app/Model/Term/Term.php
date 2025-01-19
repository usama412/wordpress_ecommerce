<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Term;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Model;
use Vehica\Core\Post\PostStatus;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\Term\Relation\Relation;
use WP_Error;
use WP_Query;
use WP_Term;

/**
 * Class Term
 *
 * @package Vehica\Model\Term
 */
class Term extends Model
{
    const TYPE = 'vehica_term';
    const PARENT_TERM = 'vehica_parent_term';
    const ALIAS = 'vehica_alias';
    const LABEL_COLOR = 'vehica_label_color';
    const LABEL_BACKGROUND_COLOR = 'vehica_label_background_color';
    const USE_AS_LABEL = 'vehica_use_as_label';
    const CAR_SINGLE_CUSTOM_TEMPLATE = 'vehica_car_single_custom_template';

    private static $countData = false;

    /**
     * @var array
     */
    protected $settings = [
        self::PARENT_TERM,
        self::LABEL_COLOR,
        self::LABEL_BACKGROUND_COLOR,
        self::USE_AS_LABEL,
        self::CAR_SINGLE_CUSTOM_TEMPLATE,
    ];

    /**
     * @var WP_Term
     */
    public $model;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->model->term_id;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool|int|mixed|WP_Error
     */
    public function setMeta($key, $value)
    {
        /** @noinspection UnusedFunctionResultInspection */
        return update_term_meta($this->getId(), $key, $value);
    }

    /**
     * @param string $key
     * @param bool $isSingle
     *
     * @return mixed
     */
    public function getMeta($key, $isSingle = true)
    {
        return get_term_meta($this->getId(), $key, $isSingle);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->model->name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->model->slug;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->model->description;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        $archiveUrl = get_post_type_archive_link(Car::POST_TYPE);
        $taxonomy = $this->getTaxonomy();

        if (!$archiveUrl || !$taxonomy) {
            return $this->getTermUrl();
        }

        if (!$this->hasParentTerm()) {
            return $this->parseUrl($archiveUrl . '?' . $taxonomy->getRewrite() . '=' . $this->getSlug());
        }

        if (is_singular(Car::POST_TYPE)) {
            global $post;
            $car = Car::getByPost($post);
            foreach ($this->getParentTerms() as $parentTerm) {
                if ($car->hasTerm($parentTerm)) {
                    return $this->parseUrl($parentTerm->getRawUrl() . '&' . $taxonomy->getRewrite() . '=' . $this->getSlug());
                }
            }
        } else {
            /** @noinspection LoopWhichDoesNotLoopInspection */
            foreach ($this->getParentTerms() as $parentTerm) {
                return $this->parseUrl($parentTerm->getRawUrl() . '&' . $taxonomy->getRewrite() . '=' . $this->getSlug());
            }
        }

        return $this->parseUrl($archiveUrl . '?' . $taxonomy->getRewrite() . '=' . $this->getSlug());
    }

    /**
     * @return string
     */
    public function getRawUrl()
    {
        $archiveUrl = get_post_type_archive_link(Car::POST_TYPE);
        $taxonomy = $this->getTaxonomy();

        if (!$archiveUrl || !$taxonomy) {
            return $this->getTermUrl();
        }

        if (!$this->hasParentTerm()) {
            return $archiveUrl . '?' . $taxonomy->getRewrite() . '=' . $this->getSlug();
        }

        if (is_singular(Car::POST_TYPE)) {
            global $post;
            $car = Car::getByPost($post);
            foreach ($this->getParentTerms() as $parentTerm) {
                /* @var Term $parentTerm */
                if ($car->hasTerm($parentTerm)) {
                    return $parentTerm->getUrl() . '&' . $taxonomy->getRewrite() . '=' . $this->getSlug();
                }
            }
        } else {
            /** @noinspection LoopWhichDoesNotLoopInspection */
            foreach ($this->getParentTerms() as $parentTerm) {
                return $parentTerm->getUrl() . '&' . $taxonomy->getRewrite() . '=' . $this->getSlug();
            }
        }

        return $archiveUrl . '?' . $taxonomy->getRewrite() . '=' . $this->getSlug();
    }

    /**
     * @param string $url
     * @return string
     */
    private function parseUrl($url)
    {
        if (!vehicaApp('pretty_urls_enabled')) {
            return $url;
        }

        $taxonomies = vehicaApp('settings_config')->getCarBreadcrumbs();
        if ($taxonomies->isEmpty()) {
            return $url;
        }

        $temp = explode('?', $url);
        if (count($temp) < 2) {
            return $url;
        }

        $parts = [];
        $tempParts = explode('&', $temp[1]);
        foreach ($tempParts as $part) {
            $tempPart = explode('=', $part);
            if (count($tempPart) > 1) {
                $parts[$tempPart[0]] = $tempPart[1];
            }
        }

        $newUrl = '';
        $skip = Collection::make();

        foreach ($taxonomies as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            if (isset($parts[$taxonomy->getRewrite()])) {
                $newUrl .= $parts[$taxonomy->getRewrite()] . '/';
                $skip[] = $taxonomy->getRewrite();
            } else {
                break;
            }
        }

        if ($skip->isNotEmpty()) {
            $tempParts = Collection::make($tempParts)->filter(static function ($part) use ($skip) {
                return !$skip->find(static function ($s) use ($part) {
                    return strpos($part, $s) !== false;
                });
            })->all();
        }

        return $temp[0] .= $newUrl . '?' . implode('&', $tempParts);
    }

    /**
     * @return string
     */
    public function getTermUrl()
    {
        $url = get_term_link($this->model);

        if (is_wp_error($url)) {
            return '';
        }

        return $url;
    }

    /**
     * @return int
     */
    public function getPostsNumber()
    {
        return $this->getCount();
    }

    /**
     * @param int $id
     *
     * @return static|false
     */
    public static function getById($id)
    {
        $term = get_term($id);
        if (!$term instanceof WP_Term) {
            return false;
        }

        if ($term->taxonomy === Currency::CURRENCIES) {
            return new Currency($term);
        }

        return new self($term);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = [
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'link' => $this->getUrl(),
            'postsNumber' => $this->getPostsNumber(),
            'type' => self::TYPE,
            'parentTerm' => false,
            'carsEndpoint' => $this->getCarsApiEndpoint(),
            'taxonomy' => $this->getTaxonomy()->getRewrite(),
            'taxonomyKey' => $this->getTaxonomyKey(),
        ];

        if ($this->hasParentTerm()) {
            $data['parentTerm'] = $this->getParentTermIds();
        }

        $taxonomy = $this->getTaxonomy();
        if ($taxonomy && $taxonomy->isFieldsDependencyEnabled()) {
            $data['relations'] = $this->getRelations();
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getTaxonomyKey()
    {
        return $this->model->taxonomy;
    }

    /**
     * @return Taxonomy|false
     */
    public function getTaxonomy()
    {
        $taxonomyKey = $this->getTaxonomyKey();

        return vehicaApp('taxonomy_' . $taxonomyKey);
    }

    /**
     * @param array $data
     */
    public function update($data)
    {
        foreach ($this->settings as $setting) {
            $value = array_key_exists($setting, $data) ? $data[$setting] : '';
            $method = 'set' . str_replace(' ', '',
                    ucwords(str_replace('_', ' ', str_replace(vehicaApp('prefix'), '', $setting))));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        $this->updateAlias();
    }

    /**
     * @return Collection
     */
    public function getFieldsRelations()
    {
        $taxonomy = $this->getTaxonomy();
        if (!$taxonomy) {
            return Collection::make();
        }

        return $taxonomy->getFieldsRelations($this);
    }

    /**
     * @return Collection
     */
    public function getRelations()
    {
        return $this->getFieldsRelations();
    }

    /**
     * @param array $data
     */
    public function saveRelations($data)
    {
        $this->getRelations()->each(static function ($relation) use ($data) {
            /* @var Relation $relation */
            if (isset($data[$relation->getKey()])) {
                $relation->setValue($data[$relation->getKey()]);
            } else {
                $relation->setValue(Relation::NOT_CHECKED);
            }
        });
    }

    private function fetchCountData()
    {
        $countData = get_transient('vehica_term_count');

        if (!is_array($countData)) {
            $countData = [];
        }

        self::$countData = $countData;
    }

    /**
     * @return int
     */
    public function getCount($includeExcluded = false)
    {
        if (empty(vehicaApp('cars_excluded_from_search')) || $includeExcluded) {
            return $this->model->count;
        }

        if (self::$countData === false) {
            $this->fetchCountData();
        }

        if (isset(self::$countData[$this->getKey()])) {
            return (int)self::$countData[$this->getKey()];
        }

        $carIds = $this->getCarIds();
        if (empty($carIds)) {
            return 0;
        }

        $count = Collection::make($carIds)->filter(static function ($carId) {
            return !in_array($carId, vehicaApp('cars_excluded_from_search'), true);
        })->count();

        self::$countData[$this->getKey()] = $count;

        set_transient('vehica_term_count', self::$countData);

        return $count;
    }

    /**
     * @param array $termIds
     */
    public function setParentTerm($termIds)
    {
        $this->setMeta(self::PARENT_TERM, $termIds);
    }

    /**
     * @return Collection
     */
    public function getParentTaxonomy()
    {
        $taxonomy = $this->getTaxonomy();
        if (!$taxonomy) {
            return Collection::make();
        }

        return $taxonomy->getParentTaxonomies();
    }

    /**
     * @return bool
     */
    public function hasParentTaxonomy()
    {
        return $this->getParentTaxonomy()->isNotEmpty();
    }

    /**
     * @return bool
     */
    public function hasParentTerm()
    {
        if (!$this->hasParentTaxonomy()) {
            return false;
        }

        $parentTermIds = $this->getParentTermIds();
        if (empty($parentTermIds)) {
            return false;
        }

        return !empty($parentTermIds[0]);
    }

    /**
     * @return array
     */
    public function getParentTermIds()
    {
        if (!$this->hasParentTaxonomy()) {
            return [];
        }

        $ids = $this->getMeta(self::PARENT_TERM);

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return Collection::make($ids)
            ->map(static function ($id) {
                return (int)$id;
            })->all();
    }

    /**
     * @return Collection
     */
    public function getParentTerms()
    {
        if (!$this->hasParentTaxonomy()) {
            return Collection::make();
        }

        return Collection::make($this->getParentTermIds())->map(static function ($parentTermId) {
            return self::getById($parentTermId);
        })->filter(static function ($term) {
            return $term !== false;
        });
    }

    /**
     * @param Term|int $term
     *
     * @return bool
     */
    public function isParentTerm($term)
    {
        $parentTermIds = $this->getParentTermIds();

        if ($term instanceof self) {
            return in_array($term->getId(), $parentTermIds, true);
        }

        return in_array((int)$term, $parentTermIds, true);
    }

    /**
     * @return string
     */
    public function getLink()
    {
        $link = get_term_link($this->model);

        if (is_wp_error($link)) {
            return '';
        }

        return $link;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        if (!property_exists($this->model, 'object_id')) {
            return 0;
        }

        return $this->model->object_id;
    }

    /**
     * @param Taxonomy|string $taxonomy
     * @param array $termIds
     *
     * @return Collection
     */
    public static function getTerms($taxonomy, $termIds)
    {
        $taxonomyKey = $taxonomy instanceof Taxonomy ? $taxonomy->getKey() : $taxonomy;
        $terms = get_terms([
            'taxonomy' => $taxonomyKey,
            'hide_empty' => false,
            'include' => $termIds
        ]);

        if (!is_array($terms)) {
            return Collection::make();
        }

        return Collection::make($terms)->map(static function ($term) {
            return new static($term);
        });
    }

    public function updateAlias()
    {
        return;
        if (!$this->hasParentTerm()) {
            $this->setMeta(self::ALIAS, $this->getName());

            return;
        }

        $parentTerm = $this->getParentTerms();
        if (!$parentTerm) {
            $this->setMeta(self::ALIAS, $this->getName());

            return;
        }

        $alias = $parentTerm->getName() . ' ' . $this->getName();
        $this->setMeta(self::ALIAS, $alias);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        $alias = $this->getMeta(self::ALIAS);

        if (empty($alias)) {
            return $this->getName();
        }

        return $alias;
    }

    /**
     * @param string $slug
     * @param string $taxonomy
     *
     * @return Term|false
     */
    public static function getBySlug($slug, $taxonomy)
    {
        $term = get_term_by('slug', $slug, $taxonomy);

        if (!$term) {
            return false;
        }

        return new self($term);
    }

    /**
     * @return string
     */
    public function getCarsApiEndpoint()
    {
        return get_rest_url() . 'vehica/v1/cars?' . $this->getTaxonomy()->getRewrite() . '=' . $this->getSlug();
    }

    /**
     * @param string $color
     */
    public function setLabelColor($color)
    {
        $this->setMeta(self::LABEL_COLOR, $color);
    }

    /**
     * @return string
     */
    public function getLabelColor()
    {
        $color = $this->getMeta(self::LABEL_COLOR);

        if (empty($color)) {
            return '#fff';
        }

        return (string)$color;
    }

    /**
     * @param string $color
     */
    public function setLabelBackgroundColor($color)
    {
        $this->setMeta(self::LABEL_BACKGROUND_COLOR, $color);
    }

    /**
     * @return string
     */
    public function getLabelBackgroundColor()
    {
        $color = $this->getMeta(self::LABEL_BACKGROUND_COLOR);

        if (empty($color)) {
            return vehicaApp('primary_color');
        }

        return (string)$color;
    }

    /**
     * @param int $use
     */
    public function setUseAsLabel($use)
    {
        $this->setMeta(self::USE_AS_LABEL, (int)$use);
    }

    /**
     * @return bool
     */
    public function useAsLabel()
    {
        $use = $this->getMeta(self::USE_AS_LABEL);

        if ($use === '') {
            return true;
        }

        return !empty($use);
    }

    /**
     * @return int[]
     */
    public function getCarIds()
    {
        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'update_post_meta_cache' => true,
            'update_post_term_cache' => true,
            'tax_query' => [
                [
                    'taxonomy' => $this->getTaxonomyKey(),
                    'terms' => [$this->getSlug()],
                    'field' => 'slug',
                ]
            ]
        ]);

        return $query->posts;
    }

    /**
     * @param int $templateId
     */
    public function setCarSingleCustomTemplate($templateId)
    {
        if (vehicaApp('settings_config')->customTemplatesEnabled()) {
            $this->setMeta(self::CAR_SINGLE_CUSTOM_TEMPLATE, (int)$templateId);
        }
    }

    /**
     * @return false|Template
     */
    public function getCarSingleCustomTemplate()
    {
        $templateId = (int)$this->getMeta(self::CAR_SINGLE_CUSTOM_TEMPLATE);

        if (empty($templateId)) {
            return false;
        }

        $template = Template::getById($templateId);
        if (!$template || !$template->isCarSingle()) {
            return false;
        }

        return $template;
    }

}