<?php
/** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\AttributeValue;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\Rewrite\Rewritable;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Model\Post\Car;
use Vehica\Search\Field\NumberSearchField;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Searchable;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Query;

/**
 * Class NumberCustomField
 *
 * @package Vehica\Field\Fields
 */
class NumberField extends Field implements SimpleTextAttribute, SearchFilter, Searchable, UrlModifier, RewritableField
{
    use Rewritable;

    const KEY = 'number';
    const NUMBER_TYPE_INTEGER = 'integer';
    const NUMBER_TYPE_FLOAT = 'float';
    const DISPLAY_BEFORE = 'vehica_display_before';
    const DISPLAY_AFTER = 'vehica_display_after';
    const DECIMAL_PLACES = 'vehica_decimal_places';
    const DECIMAL_PLACES_DEFAULT = 0;
    const HIDE_THOUSANDS_SEPARATOR = 'vehica_hide_thousands_separator';

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            Rewrite::REWRITE,
            self::DISPLAY_BEFORE,
            self::DISPLAY_AFTER,
            self::DECIMAL_PLACES,
            self::HIDE_THOUSANDS_SEPARATOR,
            self::PANEL_PLACEHOLDER,
        ];
    }

    /**
     * @param int $hideThousandsSeparator
     */
    public function setHideThousandsSeparator($hideThousandsSeparator)
    {
        $hideThousandsSeparator = (int)$hideThousandsSeparator;
        $this->setMeta(self::HIDE_THOUSANDS_SEPARATOR, $hideThousandsSeparator);
    }

    /**
     * @return bool
     */
    public function hideThousandsSeparator()
    {
        $hideThousandsSeparator = (int)$this->getMeta(self::HIDE_THOUSANDS_SEPARATOR);

        return !empty($hideThousandsSeparator);
    }


    /**
     * @return string
     */
    public function getNumberType()
    {
        $decimalPlaces = $this->getDecimalPlaces();

        if (empty($decimalPlaces)) {
            return self::NUMBER_TYPE_INTEGER;
        }

        return self::NUMBER_TYPE_FLOAT;
    }

    /**
     * @return bool
     */
    public function hasDisplayBefore()
    {
        return $this->getDisplayBefore() !== '';
    }

    /**
     * @return string
     */
    public function getDisplayBefore()
    {
        return $this->getMeta(self::DISPLAY_BEFORE);
    }

    /**
     * @param string $displayBefore
     *
     * @return void
     */
    public function setDisplayBefore($displayBefore)
    {
        $this->setMeta(self::DISPLAY_BEFORE, $displayBefore);
    }

    /**
     * @return string
     */
    public function getDisplayAfter()
    {
        return $this->getMeta(self::DISPLAY_AFTER);
    }

    public function hasDisplayAfter()
    {
        return $this->getDisplayAfter();
    }

    /**
     * @param string $displayAfter
     *
     * @return void
     */
    public function setDisplayAfter($displayAfter)
    {
        $this->setMeta(static::DISPLAY_AFTER, $displayAfter);
    }

    /**
     * @return int
     */
    public function getDecimalPlaces()
    {
        $decimalPlaces = $this->getMeta(self::DECIMAL_PLACES);
        if (empty($decimalPlaces)) {
            return self::DECIMAL_PLACES_DEFAULT;
        }

        return (int)$decimalPlaces;
    }

    /**
     * @param int $decimalPlaces
     */
    public function setDecimalPlaces($decimalPlaces)
    {
        $decimalPlaces = (int)$decimalPlaces;
        $this->setMeta(self::DECIMAL_PLACES, $decimalPlaces);
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'rewrite' => $this->getRewrite(),
            'numberType' => $this->getNumberType(),
            'displayBefore' => $this->getDisplayBefore(),
            'displayAfter' => $this->getDisplayAfter(),
            'decimalPlaces' => $this->getDecimalPlaces(),
        ];
    }


    /**
     * @param FieldsUser $fieldsUser
     * @param string $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        $numberValue = str_replace(vehicaApp('decimal_separator'), '.', $value);
        $numberValue = $this->parseValue($numberValue);
        $fieldsUser->setMeta($this->getKey(), $numberValue);
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return float|int|string
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return '';
        }

        $value = $fieldsUser->getMeta($this->getKey());

        return $this->parseValue($value);
    }

    /**
     * @param float|string $value
     *
     * @return string
     */
    public function getDisplayValue($value)
    {
        if ($value !== '') {
            $displayValue = number_format(
                $value,
                $this->getDecimalPlaces(),
                vehicaApp('decimal_separator'),
                $this->getThousandsSeparator()
            );
        } else {
            $displayValue = $value;
        }

        return $this->getDisplayBefore() . $displayValue . $this->getDisplayAfter();
    }

    /**
     * @return string
     */
    private function getThousandsSeparator()
    {
        if ($this->hideThousandsSeparator()) {
            return '';
        }

        return vehicaApp('thousands_separator');
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return string
     */
    public function getTextValue(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);

        return $this->getDisplayValue($value);
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Attribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new Attribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Collection
     */
    public function getSimpleTextValues(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);
        if (trim($value) === '') {
            return Collection::make();
        }

        $displayValue = $this->getDisplayValue($value);
        $simpleTextValue = new SimpleTextValue($displayValue);

        return Collection::make([$simpleTextValue]);
    }

    /**
     * @param array $config
     *
     * @return NumberSearchField|SearchField
     */
    public function getSearchField($config)
    {
        return new NumberSearchField($config, $this);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        if (
            !isset($_GET[$this->getRewrite()])
            && !isset($_GET[$this->getRewriteFrom()])
            && !isset($_GET[$this->getRewriteTo()])
        ) {
            [];
        }

        $params = [];
        Collection::make([
            $this->getRewrite(),
            $this->getRewriteFrom(),
            $this->getRewriteTo()
        ])->each(static function ($searchSlug) use (&$params) {
            if (isset($_GET[$searchSlug])) {
                $params[$searchSlug] = $_GET[$searchSlug];
            }
        });

        return $params;
    }

    /**
     * @param array $parameters
     *
     * @return string|false
     */
    public function getArchiveUrlPartial($parameters)
    {
        if (
            !isset($parameters[$this->getRewrite()])
            && !isset($parameters[$this->getRewriteFrom()])
            && !isset($parameters[$this->getRewriteTo()])
        ) {
            return false;
        }

        $archiveUrlPartial = [];
        Collection::make([
            $this->getRewrite(),
            $this->getRewriteFrom(),
            $this->getRewriteTo()
        ])->each(static function ($searchSlug) use ($parameters, &$archiveUrlPartial) {
            if (isset($parameters[$searchSlug])) {
                if (is_array($parameters[$searchSlug])) {
                    $archiveUrlPartial[] = implode('&', array_map(static function ($param) use ($searchSlug) {
                        return $searchSlug . '[]=' . $param;
                    }, $parameters[$searchSlug]));
                } else {
                    $archiveUrlPartial[] = $searchSlug . '=' . $parameters[$searchSlug];
                }
            }
        });

        return implode('&', $archiveUrlPartial);
    }

    /**
     * @param array $parameters
     *
     * @return array|false
     */
    public function getInitialSearchParams($parameters)
    {
        if (
            !isset($parameters[$this->getRewrite()])
            && !isset($parameters[$this->getRewriteFrom()])
            && !isset($parameters[$this->getRewriteTo()])
        ) {
            return false;
        }

        $initialParams = [];

        Collection::make([
            [
                'key' => $this->getKey(),
                'slug' => $this->getRewrite(),
                'compare' => '='
            ],
            [
                'key' => $this->getKey() . 'From',
                'slug' => $this->getRewriteFrom(),
                'compare' => '>'
            ],
            [
                'key' => $this->getKey() . 'To',
                'slug' => $this->getRewriteTo(),
                'compare' => '<'
            ],
        ])->each(function ($search) use ($parameters, &$initialParams) {
            if (!isset($parameters[$search['slug']])) {
                return;
            }

            if (is_array($parameters[$search['slug']])) {
                $parameterValues = $parameters[$search['slug']];
            } else {
                $parameterValues = [$parameters[$search['slug']]];
            }

            $initialParams[] = [
                'id' => $this->getId(),
                'key' => $search['key'],
                'name' => $this->getName(),
                'rewrite' => $search['slug'],
                'values' => Collection::make($parameterValues)->map(function ($value) use ($search) {
                    $parsedValue = $this->parseValue($value);
                    $displayValue = $this->getDisplayValue($parsedValue);

                    return [
                        'key' => $parsedValue,
                        'name' => $this->getName() . ' ' . $search['compare'] . ' ' . $displayValue,
                        'value' => $parsedValue
                    ];
                })->all()
            ];
        });

        return $initialParams;
    }

    /**
     * @param array|false $parameters
     *
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        if (
            !isset($parameters[$this->getRewrite()])
            && !isset($parameters[$this->getRewriteFrom()])
            && !isset($parameters[$this->getRewriteTo()])
        ) {
            return false;
        }

        return array_unique(array_merge(
            $this->getCardsIdsIn($parameters),
            $this->getCarsIdsFromTo($parameters)
        ));
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function getCardsIdsIn($parameters)
    {
        $metaQuery = [];

        if (empty($parameters[$this->getRewrite()])) {
            return [];
        }

        if (!is_array($parameters[$this->getRewrite()])) {
            $metaQuery[] = [
                'key' => $this->getKey(),
                'compare' => '=',
                'type' => 'NUMERIC',
                'value' => $this->parseValue($parameters[$this->getRewrite()])
            ];
        } else {
            $values = Collection::make($parameters[$this->getSlug()])->map(function ($value) {
                return $this->parseValue($value);
            })->all();

            $metaQuery[] = [
                'key' => $this->getKey(),
                'compare' => 'IN',
                'type' => 'NUMERIC',
                'value' => $values
            ];
        }

        return $this->getPartialCarsIds($metaQuery);
    }

    /**
     * @param array $parameters
     *
     * @return array
     */
    private function getCarsIdsFromTo($parameters)
    {
        $metaQuery = [];

        if (!empty($parameters[$this->getRewriteFrom()])) {
            $metaQuery[] = [
                'key' => $this->getKey(),
                'compare' => '>=',
                'type' => 'DECIMAL(10,3)',
                'value' => $this->parseValue($parameters[$this->getRewriteFrom()])
            ];
        }

        if (!empty($parameters[$this->getRewriteTo()])) {
            $metaQuery[] = [
                'key' => $this->getKey(),
                'compare' => '<=',
                'type' => 'DECIMAL(10,3)',
                'value' => $this->parseValue($parameters[$this->getRewriteTo()])
            ];
        }

        if (empty($metaQuery)) {
            return [];
        }

        $metaQuery['relation'] = 'AND';

        return $this->getPartialCarsIds($metaQuery);
    }

    /**
     * @param array $metaQuery
     *
     * @return array
     */
    protected function getPartialCarsIds($metaQuery)
    {
        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'meta_query' => $metaQuery
        ]);

        return $query->posts;
    }

    /**
     * @param mixed $value
     *
     * @return float|int
     */
    public function parseValue($value)
    {
        if ($value === '') {
            return $value;
        }

        if ($this->isInteger()) {
            return (int)$value;
        }

        return (double)number_format((double)$value, $this->getDecimalPlaces(), '.', '');
    }

    /**
     * @param $value
     *
     * @return float|int
     */
    public function toNumberType($value)
    {
        if ($this->isInteger()) {
            return (int)$value;
        }

        return (double)$value;
    }

    /**
     * @return bool
     */
    public function isInteger()
    {
        return $this->getNumberType() === self::NUMBER_TYPE_INTEGER;
    }

    /**
     * @return bool
     */
    public function isFloat()
    {
        return $this->getNumberType() === self::NUMBER_TYPE_FLOAT;
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Collection
     */
    public function getAttributeValues(FieldsUser $fieldsUser)
    {
        $value = $this->getValue($fieldsUser);

        if (empty($value)) {
            return Collection::make();
        }

        $display = $this->getDisplayValue($value);

        return Collection::make([AttributeValue::make($value, $display)]);
    }


}