<?php

namespace Vehica\Search\QueryModifier;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Field\Fields\Price\Price;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Search\UrlModifier;

/**
 * Class SortQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class SortQueryModifier implements QueryModifier, UrlModifier
{

    public function getKey()
    {
        // TODO: Implement getKey() method.
    }

    public function setRewrite($rewrite)
    {
        // TODO: Implement setRewrite() method.
    }

    /**
     * @param array $queryParams
     * @param array $requestParams
     * @return array
     */
    public function modifyQuery($queryParams, $requestParams = [])
    {
        $searchSlug = vehicaApp('sort_by_rewrite');

        if (empty($requestParams[$searchSlug])) {
            return $this->orderByNewest($queryParams);
        }

        $sortBy = $requestParams[$searchSlug];

        if ($sortBy === vehicaApp('newest_rewrite')) {
            return $this->orderByNewest($queryParams);
        }

        if ($sortBy === vehicaApp('oldest_rewrite')) {
            return $this->orderByOldest($queryParams);
        }

        if ($sortBy === vehicaApp('featured_rewrite')) {
            return $this->orderByFeatured($queryParams);
        }

        if ($sortBy === 'random') {
            return $this->orderByRandom($queryParams);
        }

        if ($sortBy === vehicaApp('name_a_z_rewrite')) {
            return $this->orderNameAZ($queryParams);
        }

        if ($sortBy === vehicaApp('name_z_a_rewrite')) {
            return $this->orderNameZA($queryParams);
        }

        foreach (vehicaApp('price_fields') as $priceField) {
            /* @var PriceField $priceField */
            if ($sortBy === $priceField->getRewrite() . '-' . vehicaApp('high_to_low_rewrite')) {
                return $this->orderByPrice($priceField, $queryParams, 'DESC');
            }

            if ($sortBy === $priceField->getRewrite() . '-' . vehicaApp('low_to_high_rewrite')) {
                return $this->orderByPrice($priceField, $queryParams);
            }
        }

        foreach (vehicaApp('number_fields') as $numberField) {
            /* @var NumberField $numberField */
            if ($sortBy === $numberField->getRewrite() . '-' . vehicaApp('high_to_low_rewrite')) {
                return $this->orderByNumberField($numberField, $queryParams, 'DESC');
            }

            if ($sortBy === $numberField->getRewrite() . '-' . vehicaApp('low_to_high_rewrite')) {
                return $this->orderByNumberField($numberField, $queryParams);
            }
        }

        return $this->orderByNewest($queryParams);
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderNameAZ($queryParams)
    {
        $queryParams['orderby'] = 'title';
        $queryParams['order'] = 'ASC';

        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderNameZA($queryParams)
    {
        $queryParams['orderby'] = 'title';
        $queryParams['order'] = 'DESC';

        return $queryParams;
    }

    /**
     * @param NumberField $numberField
     * @param array $queryParams
     * @param string $order
     * @return array
     */
    private function orderByNumberField(NumberField $numberField, $queryParams, $order = 'ASC')
    {
        $queryParams['orderby'] = [
            'meta_value_num' => $order
        ];
        $queryParams['meta_key'] = $numberField->getKey();
        $queryParams['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => $numberField->getKey(),
                'compare' => 'EXISTS'
            ],
            [
                'key' => $numberField->getKey(),
                'compare' => 'NOT EXISTS',
                'value' => ''
            ]
        ];

        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderByNewest($queryParams)
    {
        $queryParams['orderby'] = 'date';
        $queryParams['order'] = 'DESC';
        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderByOldest($queryParams)
    {
        $queryParams['orderby'] = 'date';
        $queryParams['order'] = 'ASC';
        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderByFeatured($queryParams)
    {
        $queryParams['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => 'vehica_featured',
                'compare' => 'NOT EXISTS',
            ],
            [
                'relation' => 'OR',
                [
                    'key' => 'vehica_featured',
                    'value' => '1',
                ],
                [
                    'key' => 'vehica_featured',
                    'value' => '1',
                    'compare' => '!=',
                ],
            ],
        ];
        $queryParams['orderby'] = [
            'meta_value' => 'DESC',
            'date' => 'DESC',
        ];
        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return array
     */
    private function orderByRandom($queryParams)
    {
        $queryParams['orderby'] = 'rand';
        return $queryParams;
    }


    /**
     * @param PriceField $priceField
     * @param array $queryParams
     * @param string $order
     * @return array
     */
    private function orderByPrice(PriceField $priceField, $queryParams, $order = 'ASC')
    {
        if (vehicaApp('current_currency') === false) {
            return $queryParams;
        }

        $priceKey = Price::make($priceField, vehicaApp('current_currency'))->getKey();

        $queryParams['orderby'] = [
            'meta_value_num' => $order
        ];
        $queryParams['meta_key'] = $priceKey;
        $queryParams['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => $priceKey,
                'compare' => 'EXISTS'
            ],
            [
                'key' => $priceKey,
                'compare' => 'NOT EXISTS',
                'value' => ''
            ]
        ];

        return $queryParams;
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $searchSlug = vehicaApp('sort_by_rewrite');

        if (empty($_GET[$searchSlug])) {
            return [];
        }

        return [$searchSlug => $_GET[$searchSlug]];
    }

    /**
     * @param array $parameters
     * @return string|false
     */
    public function getArchiveUrlPartial($parameters)
    {
        $searchSlug = vehicaApp('sort_by_rewrite');

        if (empty($parameters[$searchSlug])) {
            return false;
        }

        return $searchSlug . '=' . $parameters[$searchSlug];
    }

    public function getRewrite()
    {
        return 'sort_by';
    }

}