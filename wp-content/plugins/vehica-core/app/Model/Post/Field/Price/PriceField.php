<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field\Price;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\AttributeValue;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Field\Fields\Price\Price;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Search\Field\PriceSearchField;
use Vehica\Search\Field\SearchField;

/**
 * Class PriceField
 * @package Vehica\CustomField\Fields
 */
class PriceField extends NumberField
{
    const KEY = 'price';

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            Rewrite::REWRITE,
            self::DISPLAY_BEFORE,
            self::DISPLAY_AFTER,
            self::PANEL_PLACEHOLDER,
        ];
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return array
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return [];
        }

        $values = [];

        foreach ($this->getPrices() as $price) {
            /* @var Price $price */
            $key = $price->getKey();
            $value = $fieldsUser->getMeta($key);

            if ($value === '') {
                $values[$key] = $value;
            } else {
                $values[$key] = ($price->getCurrency())->parseValue($value);
            }
        }

        return $values;
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param Currency $currency
     * @return float|int
     */
    public function getValueByCurrency(FieldsUser $fieldsUser, Currency $currency = null)
    {
        if ($currency === null) {
            $currency = vehicaApp('current_currency');
        }

        if (!$currency) {
            return 0;
        }

        $price = new Price($this, $currency);
        $value = $fieldsUser->getMeta($price->getKey());
        $currency = $price->getCurrency();
        return $currency->parseValue($value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param Currency|null $currency
     * @return string
     */
    public function getFormattedValueByCurrency(FieldsUser $fieldsUser, Currency $currency = null)
    {
        if (!$this->isVisible()) {
            return '';
        }

        $value = $this->getValueByCurrency($fieldsUser, $currency);

        if (trim($value) === '') {
            return '';
        }

        return apply_filters('vehica/price/formatted', $this->getFormattedValue($value), $fieldsUser);
    }

    /**
     * @param array $value
     * @param bool $filterEmpty
     * @return array
     */
    public function getDisplayValue($value, $filterEmpty = true)
    {
        $displayValues = [];

        foreach ($this->getPrices() as $price) {
            /* @var Price $price */
            $key = $price->getKey();
            if ($filterEmpty && (!isset($value[$key]) || $value[$key] === '')) {
                continue;
            }

            $val = isset($value[$key]) ? $value[$key] : 0;
            $displayValues[$key] = $price->formatValue($val);
        }

        return $displayValues;
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param array $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        if (!is_array($value)) {
            $value = [];
        }

        foreach ($value as $key => $price) {
            $currency = vehicaApp('currencies')->find(function ($currency) use ($key) {
                /* @var Currency $currency */
                $currencyKey = 'vehica_currency_' . $this->getId() . '_' . $currency->getId();
                return $currencyKey === $key;
            });

            if (!$currency instanceof Currency) {
                return;
            }

            $price = trim($price);

            if ($price !== '') {
                $price = str_replace(
                    [
                        $currency->getSign(),
                        $this->getDisplayBefore(),
                        $this->getDisplayAfter(),
                        $currency->getThousandsSeparator()
                    ], '', $price
                );

                $price = str_replace($currency->getDecimalSeparator(), '.', $price);
                $price = $currency->parseValue($price);
            }

            $fieldsUser->setMeta($key, $price);
        }
    }

    /**
     * @return Collection
     */
    public function getPrices()
    {
        $currencies = vehicaApp('currencies');

        if (empty($currencies)) {
            return Collection::make();
        }

        return $currencies->map(function ($currency) {
            /* @var Currency $currency */
            return new Price($this, $currency);
        });
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'numberType' => $this->getNumberType(),
            'fields' => $this->getPrices(),
            'rewrite' => $this->getRewrite(),
        ];
    }

    /**
     * @param Price $price
     * @return bool
     */
    public function isPriceRequired(Price $price)
    {
        $isRequired = $this->getMeta($price->getRequiredKey());
        return !empty($isRequired);
    }

    /**
     * @param array $postData
     * @param array $settings
     */
    public function update($postData, $settings = [])
    {
        parent::update($postData);

        $this->getPrices()->each(function ($price) use ($postData) {
            /* @var Price $price */
            $key = $price->getRequiredKey();
            $value = isset($postData[$key]) ? $postData[$key] : 0;
            $this->setMeta($key, $value);
        });
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getSimpleTextValues(FieldsUser $fieldsUser)
    {
        $values = array_filter($this->getValue($fieldsUser), static function ($value) {
            return trim($value) !== '';
        });

        return Collection::make($this->getDisplayValue($values, true))->map(static function ($displayValue) {
            return new SimpleTextValue($displayValue);
        });
    }

    /**
     * @param array $config
     * @return PriceSearchField|SearchField
     */
    public function getSearchField($config)
    {
        return new PriceSearchField($config, $this);
    }

    /**
     * @param array|false $parameters
     * @return array|bool
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

        $priceKey = Price::make($this, vehicaApp('current_currency'))->getKey();

        return array_unique(array_merge(
            $this->getCardsIdsIn($parameters, $priceKey),
            $this->getCarsIdsFromTo($parameters, $priceKey)
        ));
    }

    /**
     * @param array $parameters
     * @param string $priceKey
     * @return array
     */
    private function getCarsIdsFromTo($parameters, $priceKey)
    {
        $metaQuery = [];

        if (!empty($parameters[$this->getRewriteFrom()])) {
            $metaQuery[] = [
                'key' => $priceKey,
                'compare' => '>=',
                'type' => 'NUMERIC',
                'value' => $this->parseValue($parameters[$this->getRewriteFrom()])
            ];
        }

        if (!empty($parameters[$this->getRewriteTo()])) {
            $metaQuery[] = [
                'key' => $priceKey,
                'compare' => '<=',
                'type' => 'NUMERIC',
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
     * @param array $parameters
     * @param string $priceKey
     * @return array
     */
    private function getCardsIdsIn($parameters, $priceKey)
    {
        $metaQuery = [];

        if (empty($parameters[$this->getRewrite()])) {
            return [];
        }

        if (!is_array($parameters[$this->getRewrite()])) {
            $metaQuery[] = [
                'key' => $priceKey,
                'compare' => '=',
                'type' => 'NUMERIC',
                'value' => $this->parseValue($parameters[$this->getRewrite()])
            ];
        } else {
            $values = Collection::make($parameters[$this->getSlug()])->map(function ($value) {
                return $this->parseValue($value);
            })->all();

            $metaQuery[] = [
                'key' => $priceKey,
                'compare' => 'IN',
                'type' => 'NUMERIC',
                'value' => $values
            ];
        }

        return $this->getPartialCarsIds($metaQuery);
    }

    /**
     * @param int|float|string $value
     * @param Currency $currency
     * @return string
     */
    public function getFormattedValue($value, Currency $currency = null)
    {
        if ($currency === null) {
            $currency = vehicaApp('current_currency');
        }

        $value = $this->prepareValue($value, $currency);

        return Price::make($this, $currency)->formatValue($value);
    }

    /**
     * @param $value
     * @param Currency $currency
     * @return float|int
     */
    private function prepareValue($value, Currency $currency)
    {
        $decimalPlaces = $currency->getDecimalPlaces();

        if (empty($decimalPlaces)) {
            return (int)$value;
        }

        return (double)number_format((double)$value, $decimalPlaces, '.', '');
    }

    /**
     * @param array $parameters
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

                    if (vehicaApp('current_currency')) {
                        $displayValue = $this->getFormattedValue($parsedValue, vehicaApp('current_currency'));
                    } else {
                        $displayValue = $parsedValue;
                    }

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
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getAttributeValues(FieldsUser $fieldsUser)
    {
        $values = $this->getValue($fieldsUser);
        $displays = $this->getDisplayValue($values);
        $attributeValues = Collection::make();

        foreach ($values as $key => $value) {
            if (!empty($value) && isset($displays[$key])) {
                $attributeValues[] = AttributeValue::make(
                    $value,
                    $displays[$key]
                );
            }
        }

        return $attributeValues;
    }

}