<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Search\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Search\SearchControl;

/**
 * Class NumberSearchField
 * @package Vehica\Search\Field
 */
class NumberSearchField extends SearchField
{
    const TYPE = 'number';
    const PLACEHOLDER = 'number_placeholder';
    const PLACEHOLDER_FROM = 'number_placeholder_from';
    const PLACEHOLDER_TO = 'number_placeholder_to';
    const ANY_TEXT_FROM = 'number_any_text_from';
    const ANY_TEXT_TO = 'number_any_text_to';
    const ANY_TEXT = 'number_any_text';
    const VALUES = 'number_values';
    const VALUES_FROM = 'number_values_from';
    const VALUES_TO = 'number_values_to';
    const CONTROL = 'number_control';
    const NUMBER_TYPE_FROM = 'From';
    const NUMBER_TYPE_TO = 'To';
    const EQUAL = 'equal';
    const GREATER_THAN = 'greater';
    const LESS_THAN = 'less';
    const COMPARE_VALUE_TYPE = 'number_compare_value_type';
    const NUMBER_VALUES = 'number_values';
    const NUMBER_VALUES_FROM = 'number_values_from';
    const NUMBER_VALUES_TO = 'number_values_to';
    const ADD_GREATER_THAN = 'number_add_greater_than';

    /**
     * @var NumberField
     */
    protected $searchable;

    /**
     * @var array|false
     */
    protected $staticValuesFrom = false;

    /**
     * @var array|false
     */
    protected $staticValuesTo = false;

    /**
     * @var array|false
     */
    protected $staticValues = false;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->searchable->getKey();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->searchable->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @return bool
     */
    public function hasPlaceholder()
    {
        return true;
    }

    /**
     * @param bool $shortPlaceholder
     * @return string
     */
    public function getPlaceholder($shortPlaceholder = false)
    {
        if (!empty($this->config[static::PLACEHOLDER])) {
            return $this->config[static::PLACEHOLDER];
        }

        if ($shortPlaceholder) {
            return vehicaApp('any_string');
        }

        return $this->getName();
    }

    /**
     * @return bool
     */
    public function hasPlaceholderFrom()
    {
        return true;
    }

    /**
     * @param bool $shortPlaceholder
     * @return string
     */
    public function getPlaceholderFrom($shortPlaceholder = false)
    {
        if (empty($this->config[static::PLACEHOLDER_FROM])) {
            if (!$shortPlaceholder) {
                return vehicaApp('min_string');
            }

            return vehicaApp('min_string') . ' ' . $this->getName();
        }

        return $this->config[static::PLACEHOLDER_FROM];
    }

    /**
     * @return bool
     */
    public function hasPlaceholderTo()
    {
        return true;
    }

    /**
     * @param bool $shortPlaceholder
     * @return string
     */
    public function getPlaceholderTo($shortPlaceholder = false)
    {
        if (empty($this->config[static::PLACEHOLDER_TO])) {
            if (!$shortPlaceholder) {
                return vehicaApp('max_string');
            }

            return vehicaApp('max_string') . ' ' . $this->getName();
        }

        return $this->config[static::PLACEHOLDER_TO];
    }


    /**
     * @return string
     */
    public function getControl()
    {
        if (empty($this->config[static::CONTROL])) {
            return self::getDefaultControl();
        }

        return $this->config[static::CONTROL];
    }

    /**
     * @return array
     */
    public static function getControlsList()
    {
        return [
            SearchControl::TYPE_INPUT_FROM_TO => esc_html__('Text from/to', 'vehica-core'),
            SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
        ];
    }

    /**
     * @return string
     */
    public static function getDefaultControl()
    {
        return SearchControl::TYPE_INPUT_FROM_TO;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->searchable->getId();
    }

    /**
     * @return bool
     */
    public function isTextFromToControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return true;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_INPUT_FROM_TO;
    }

    /**
     * @return bool
     */
    public function isSelectFromToControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return false;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_SELECT_FROM_TO;
    }

    /**
     * @return bool
     */
    public function isRadioControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return false;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_RADIO;
    }

    /**
     * @return bool
     */
    public function isCheckboxControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return false;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_CHECKBOX;
    }

    /**
     * @return bool
     */
    public function isSelectControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return false;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isSelectMultiControl()
    {
        if (!isset($this->config[static::CONTROL])) {
            return false;
        }

        return $this->config[static::CONTROL] === SearchControl::TYPE_MULTI_SELECT;
    }

    protected function prepareStaticValues()
    {
        $key = 'number_values';
        $this->staticValues = $this->getStaticValuesByKey($key);
    }

    /**
     * @return bool
     */
    public function hasStaticValues()
    {
        if ($this->staticValues === false) {
            $this->prepareStaticValues();
        }

        return !empty($this->staticValues);
    }

    /**
     * @return array
     */
    protected function getAllStaticValues()
    {
        if ($this->staticValues === false) {
            $this->prepareStaticValues();
        }

        return $this->staticValues;
    }

    /**
     * @return array|false
     */
    public function getStaticValues()
    {
        if ($this->staticValues === false) {
            $this->prepareStaticValues();
        }

        if (!$this->hasGreaterThanValue()) {
            return $this->staticValues;
        }

        $staticValues = $this->staticValues;
        array_pop($staticValues);

        return $staticValues;
    }

    protected function prepareStaticValuesFrom()
    {
        $key = 'number_values_from';
        $this->staticValuesFrom = $this->getStaticValuesByKey($key);
    }

    /**
     * @return array
     */
    public function getStaticValuesFrom()
    {
        if ($this->staticValuesFrom === false) {
            $this->prepareStaticValuesFrom();
        }

        return $this->staticValuesFrom;
    }

    /**
     * @return bool
     */
    public function hasStaticValuesFrom()
    {
        if ($this->staticValuesFrom === false) {
            $this->prepareStaticValuesFrom();
        }

        return !empty($this->staticValuesFrom);
    }

    protected function prepareStaticValuesTo()
    {
        $key = 'number_values_to';
        $this->staticValuesTo = $this->getStaticValuesByKey($key);
    }

    /**
     * @return array
     */
    public function getStaticValuesTo()
    {
        if ($this->staticValuesTo === false) {
            $this->prepareStaticValuesTo();
        }

        return $this->staticValuesTo;
    }

    /**
     * @return bool
     */
    public function hasStaticValuesTo()
    {
        if ($this->staticValuesTo === false) {
            $this->prepareStaticValuesTo();
        }

        return !empty($this->staticValuesTo);
    }

    /**
     * @param string $valuesKey
     * @return array
     */
    protected function getStaticValuesByKey($valuesKey)
    {
        if (empty($this->config[$valuesKey])) {
            return [];
        }

        if ($this->isCompareValueGreaterThan()) {
            $compareSign = '> ';
        } elseif ($this->isCompareValueLessThan()) {
            $compareSign = '< ';
        } else {
            $compareSign = $this->getName() . ' = ';
        }

        return Collection::make(explode(',', $this->config[$valuesKey]))->map(static function ($value) {
            return (int)trim($value);
        })->filter(static function ($value) {
            return !empty($value);
        })->map(function ($value) use ($compareSign) {
            return [
                'display' => $this->searchable->getDisplayValue($value),
                'controlDisplay' => $compareSign . $this->searchable->getDisplayValue($value),
                'value' => $value
            ];
        })->all();
    }

    /**
     * @return bool
     */
    public function hasGreaterThanValue()
    {
        return !empty($this->config[self::ADD_GREATER_THAN]);
    }

    /**
     * @return string
     */
    public function getGreaterThanDisplay()
    {
        $staticValues = $this->getAllStaticValues();
        if (!is_array($staticValues) || count($staticValues) < 1) {
            return 0;
        }
        $option = array_pop($staticValues);

        if ($this->isCompareValueEqual()) {
            return $this->getName() . ' > ' . $option['display'];
        }

        return $option['display'] . '+';
    }

    /**
     * @return float|int
     */
    public function getGreaterThanValue()
    {
        $staticValues = $this->getAllStaticValues();
        if (!is_array($staticValues) || count($staticValues) < 1) {
            return 0;
        }
        $option = array_pop($staticValues);
        return $option['value'];
    }

    /**
     * @return bool
     */
    public function isCompareValueGreaterThan()
    {
        if (!isset($this->config[static::COMPARE_VALUE_TYPE])) {
            return false;
        }

        return $this->config[static::COMPARE_VALUE_TYPE] === static::GREATER_THAN;
    }

    /**
     * @return bool
     */
    public function isCompareValueLessThan()
    {
        if (!isset($this->config[static::COMPARE_VALUE_TYPE])) {
            return false;
        }

        return $this->config[static::COMPARE_VALUE_TYPE] === static::LESS_THAN;
    }

    /**
     * @return bool
     */
    public function isCompareValueEqual()
    {
        return !$this->isCompareValueGreaterThan() && !$this->isCompareValueLessThan();
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'id' => $this->searchable->getId(),
            'rewrite' => $this->searchable->getRewrite(),
            'rewriteFrom' => $this->searchable->getRewriteFrom(),
            'rewriteTo' => $this->searchable->getRewriteTo(),
            'decimalPlaces' => $this->searchable->getDecimalPlaces(),
            'decimalSeparator' => vehicaApp('decimal_separator'),
            'thousandsSeparator' => vehicaApp('thousands_separator'),
            'numberType' => $this->searchable->getNumberType(),
            'displayBefore' => $this->searchable->getDisplayBefore(),
            'displayAfter' => $this->searchable->getDisplayAfter(),
        ];
    }

    /**
     * @return int
     */
    public function getOptionsNumber()
    {
        if (!$this->hasStaticValues()) {
            return 0;
        }

        return count($this->getStaticValues()) + 1;
    }

}