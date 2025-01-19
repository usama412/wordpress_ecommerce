<?php

namespace Vehica\Search\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Field\Fields\Price\Price;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Search\SearchControl;

/**
 * Class PriceSearchField
 *
 * @package Vehica\Search\Field
 */
class PriceSearchField extends NumberSearchField
{
    const TYPE = 'price';
    const CONTROL = 'price_control';
    const COMPARE_VALUE_TYPE = 'price_compare_value_type';
    const PLACEHOLDER = 'price_placeholder';
    const PLACEHOLDER_FROM = 'price_placeholder_from';
    const PLACEHOLDER_TO = 'price_placeholder_to';

    /**
     * @var PriceField
     */
    protected $searchable;

    /**
     * @param string $valuesKey
     *
     * @return array
     */
    protected function getSelectValues($valuesKey)
    {
        $allValues = [];

        vehicaApp('currencies')->each(function ($currency) use (&$allValues, $valuesKey) {
            /* @var Currency $currency */
            $key = $valuesKey . $currency->getKey();

            if (empty($this->config[$key])) {
                $allValues[$currency->getId()] = [];
            }

            $price = new Price($this->searchable, $currency);

            $allValues[$currency->getId()] = Collection::make(explode(',', $this->config[$key]))->map(static function (
                $value
            ) {
                return (int)trim($value);
            })->filter(static function ($value) {
                return !empty($value);
            })->map(static function ($value) use ($price) {
                return [
                    'display' => $price->formatValue($value),
                    'controlDisplay' => $price->formatValue($value),
                    'value' => $value
                ];
            })->all();
        });

        return $allValues;
    }

    protected function prepareStaticValues()
    {
        $key = 'number_values_';
        $this->staticValues = $this->getSelectValues($key);
    }

    protected function prepareStaticValuesTo()
    {
        $key = 'number_values_to_';
        $this->staticValuesTo = $this->getSelectValues($key);
    }

    protected function prepareStaticValuesFrom()
    {
        $key = 'number_values_from_';
        $this->staticValuesFrom = $this->getSelectValues($key);
    }

    /**
     * @return array
     */
    public static function getControlsList()
    {
        return [
            SearchControl::TYPE_INPUT_FROM_TO => esc_html__('Text from/to', 'vehica-core'),
            SearchControl::TYPE_SELECT_FROM_TO => esc_html__('Select from/to', 'vehica-core'),
            SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
        ];
    }

}