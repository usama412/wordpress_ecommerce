<?php

namespace Vehica\Api;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use Vehica\Search\QueryModifier\QueryModifier;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Query;

/**
 * Class CarsApi
 *
 * @package Vehica\Api
 */
class CarsApi
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var array
     */
    protected $taxonomiesTermsCount;

    /**
     * @var bool
     */
    protected $disableCars = false;

    /**
     * @var bool
     */
    protected $disableTermsCount = false;

    /**
     * @var array
     */
    protected static $allCarIds = false;

    /**
     * @var bool
     */
    protected $includeExcluded = false;

    /**
     * CarsApi constructor.
     * @param array $params
     * @param false $includeExcluded
     */
    public function __construct($params = [], $includeExcluded = false)
    {
        $this->params = $params;

        $this->includeExcluded = $includeExcluded;

        $this->setParamsFromQueryVars();
    }

    private function setParamsFromQueryVars()
    {
        foreach (vehicaApp('taxonomy_url') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $value = get_query_var($taxonomy->getKey());
            if (!empty($value)) {
                $this->params[$taxonomy->getRewrite()] = explode(',', $value);
            }
        }
    }

    public function disableTermsCount()
    {
        $this->disableTermsCount = true;
    }

    /**
     * @param array $params
     * @param bool $includeExcluded
     * @return CarsApi
     */
    public static function make($params = [], $includeExcluded = false)
    {
        return new CarsApi($params, $includeExcluded);
    }

    /**
     * @param Collection $taxonomies
     */
    public function setTaxonomiesTermsCount($taxonomies)
    {
        $this->taxonomiesTermsCount = $taxonomies;
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        $results = $this->getResults();

        return $results['results'];
    }

    /**
     * @return array
     */
    public function getResults()
    {
        $searchFilters = vehicaApp('search_filters');
        $carsIds = $this->getCarsIds($searchFilters, $this->params);

        if (isset($this->params['base_url'])) {
            $baseUrl = $this->params['base_url'];
        } else {
            $baseUrl = get_post_type_archive_link(Car::POST_TYPE);
        }

        $resultsUrl = $this->getResultsUrl($baseUrl, vehicaApp('search_url_modifiers'), $this->params);

        if (is_array($carsIds) && empty($carsIds)) {
            return [
                'resultsCount' => 0,
                'formattedResultsCount' => 0,
                'results' => Collection::make(),
                'terms' => false,
                'url' => $resultsUrl,
                'markers' => [],
            ];
        }

        if (!is_array($carsIds)) {
            $carsIds = [];
        }

        $queryParams = [
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'post__in' => $carsIds,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ];

        vehicaApp('search_query_modifiers')->each(function ($queryModifier) use (&$queryParams) {
            /* @var QueryModifier $queryModifier */
            $queryParams = $queryModifier->modifyQuery($queryParams, $this->params);
        });

        $queryParams = apply_filters('vehica/search/params', $queryParams);

        $query = new WP_Query($queryParams);

        $resultsCount = $query->found_posts;

        if (!$this->disableCars) {
            $cars = Collection::make($query->posts)->map(static function ($car) {
                return new Car($car);
            });

            $this->prepareTerms($cars);
        } else {
            $cars = [];
        }

        return [
            'resultsCount' => $resultsCount,
            'formattedResultsCount' => number_format(
                $resultsCount,
                0,
                vehicaApp('decimal_separator'),
                vehicaApp('thousands_separator')
            ),
            'results' => $cars,
            'terms' => $this->getTermsCount($searchFilters, $this->params),
            'url' => $resultsUrl,
            'markers' => [],
        ];
    }

    /**
     * @param Collection $searchFilters
     * @param array $params
     *
     * @return array|false
     */
    private function getCarsIds($searchFilters, $params)
    {
        /* @var array|false $carsIds */
        $carsIds = false;

        $searchFilters->each(static function ($searchFilter) use ($params, &$carsIds) {
            /* @var SearchFilter $searchFilter */
            $ids = $searchFilter->getSearchedCarsIds($params);

            if ($ids === false) {
                return;
            }

            if ($carsIds === false) {
                $carsIds = $ids;
            } else {
                $carsIds = array_filter($carsIds, static function ($id) use ($ids) {
                    return in_array($id, $ids, true);
                });
            }
        });

        if ($carsIds === false) {
            $carsIds = $this->getAllCarIds();
        }

        return $this->filterExcludedFromSearch($carsIds);
    }

    /**
     * @return array
     */
    private function getAllCarIds()
    {
        if (self::$allCarIds !== false) {
            return self::$allCarIds;
        }

        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => -1,
            'fields' => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);

        self::$allCarIds = $query->posts;

        return self::$allCarIds;
    }

    /**
     * @param array $carIds
     * @return array
     */
    private function filterExcludedFromSearch($carIds)
    {
        if ($this->includeExcluded || empty(vehicaApp('cars_excluded_from_search'))) {
            return $carIds;
        }

        return Collection::make($carIds)->filter(static function ($carId) {
            return !in_array($carId, vehicaApp('cars_excluded_from_search'), true);
        })->all();
    }

    /**
     * @param Collection $allSearchFilters
     * @param array $params
     *
     * @return array
     */
    public function getTermsCount($allSearchFilters, $params = [])
    {
        $allTerms = [];

        if ($this->disableTermsCount) {
            return $allTerms;
        }

        if ($this->taxonomiesTermsCount !== null) {
            $taxonomies = $this->taxonomiesTermsCount;
        } else {
            $taxonomies = vehicaApp('taxonomies');
        }

        $taxonomies->each(function ($taxonomy) use ($params, $allSearchFilters, &$allTerms) {
            /* @var Taxonomy $taxonomy */
            $searchFilters = $allSearchFilters->filter(static function ($searchFilter) use ($taxonomy) {
                return !(
                    $searchFilter instanceof Taxonomy
                    && (
                        ($searchFilter->getId() === $taxonomy->getId() && $taxonomy->isCompareLogicOr())
                        || $taxonomy->isParentTaxonomy($searchFilter)
                        || $searchFilter->isParentTaxonomy($taxonomy)
                    )
                );
            });

            $carsIds = $this->getCarsIds($searchFilters, $params);
            $terms = $this->getTermsCountForTaxonomy($taxonomy, $carsIds);

            $allTerms = array_merge($allTerms, $terms);
        });

        return $allTerms;
    }

    /**
     * @param Taxonomy $taxonomy
     * @param array|false $carsIds
     *
     * @return array
     */
    private function getTermsCountForTaxonomy(Taxonomy $taxonomy, $carsIds = [])
    {
        if ($carsIds === false) {
            $query = new WP_Query([
                'post_type' => Car::POST_TYPE,
                'posts_per_page' => '-1',
                'fields' => 'ids',
                'post_status' => PostStatus::PUBLISH
            ]);
            $carsIds = $query->posts;
        }

        global $wpdb;
        if (empty($carsIds)) {
            $carsIdsQuery = 'AND p.post_status = "publish"';
        } else {
            $carsIdsQuery = "AND p.ID IN (" . implode(',', $carsIds) . ")";
        }

        $sql = "
            SELECT tt.term_id as id, COUNT(tr.term_taxonomy_id) as count FROM {$wpdb->posts} p
            LEFT OUTER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
            LEFT OUTER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
            WHERE tt.taxonomy = '" . $taxonomy->getKey() . "' $carsIdsQuery
            GROUP BY tr.term_taxonomy_id
            ";

        $terms = $wpdb->get_results($sql);

        if (!is_array($terms)) {
            return [
                [
                    'id' => $taxonomy->getKey(),
                    'count' => count($carsIds)
                ]
            ];
        }

        $output = Collection::make($terms)->map(static function ($term) use ($taxonomy) {
            return [
                'id' => apply_filters('wpml_object_id', (int)$term->id, $taxonomy->getKey()),
                'count' => (int)$term->count
            ];
        })->all();

        $output[] = [
            'id' => $taxonomy->getKey(),
            'count' => count($carsIds)
        ];

        return $output;
    }

    /**
     * @param string $baseUrl
     * @param Collection $urlModifiers
     * @param array $params
     *
     * @return string
     */
    private function getResultsUrl($baseUrl, $urlModifiers, $params)
    {
        $urlPartials = $urlModifiers->map(static function ($urlModifier) use ($params) {
            /* @var UrlModifier $urlModifier */
            return $urlModifier->getArchiveUrlPartial($params);
        })->filter(static function ($archiveUrlPartial) {
            return $archiveUrlPartial !== false;
        })->all();

        if (vehicaApp('pretty_urls_enabled')) {
            $skip = Collection::make();

            foreach (vehicaApp('taxonomy_url') as $taxonomy) {
                /* @var Taxonomy $taxonomy */
                if (!empty($params[$taxonomy->getRewrite()])) {
                    $value = $params[$taxonomy->getRewrite()];
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }

                    $baseUrl .= $value . '/';
                    $skip[] = $taxonomy->getRewrite();
                } else {
                    break;
                }
            }

            if ($skip->isNotEmpty()) {
                $urlPartials = Collection::make($urlPartials)->filter(static function ($partial) use ($skip) {
                    return !$skip->find(static function ($part) use ($partial) {
                        return strpos($partial, $part) !== false;
                    });
                })->all();
            }
        }

        if (!empty($urlPartials)) {
            $baseUrl .= '?' . implode('&', $urlPartials);
        }

        if (!headers_sent()) {
            setcookie('vehica_results', $baseUrl, time() + 86400, '/');
        }

        return $baseUrl;
    }

    /**
     * @param Collection $cars
     */
    protected function prepareTerms(Collection $cars)
    {
        if (!vehicaApp()->has('car_json_fields')) {
            return;
        }

        Collection::make(vehicaApp('car_json_fields'))->map(static function ($fieldId) {
            return vehicaApp('car_fields')->find(static function ($field) use ($fieldId) {
                /* @var Field $field */
                return $field->getId() === $fieldId;
            });
        })->filter(static function ($field) {
            return $field instanceof Taxonomy;
        })->each(static function ($taxonomy) use ($cars) {
            /* @var Taxonomy $taxonomy */
            $terms = $taxonomy->getPostsTerms($cars);
            $cars->each(static function ($car) use ($taxonomy, $terms) {
                /* @var Car $car */
                $carTerms = $terms->filter(static function ($term) use ($car) {
                    /* @var Term $term */
                    return $term->getObjectId() === $car->getId();
                })->all();
                $key = $taxonomy->getKey() . '_' . $car->getId();
                vehicaApp()->bind($key, $carTerms);
            });
        });
    }

    public function disableCars()
    {
        $this->disableCars = true;
    }

    public function enableCars()
    {
        $this->disableCars = false;
    }

}