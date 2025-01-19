<?php /** @noinspection InterfacesAsConstructorDependenciesInspection */

/** @noinspection ContractViolationInspection */

namespace Vehica\Field\Fields\Price;

if (!defined('ABSPATH')) {
    exit;
}

use JsonSerializable;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Class Price
 * @package Vehica\CustomField\Fields\Price
 */
class Price implements JsonSerializable
{
    /**
     * @var PriceField
     */
    protected $priceField;

    /**
     * @var Currency
     */
    protected $currency;

    /**
     * Price constructor.
     * @param PriceField $priceField
     * @param Currency $currency
     */
    public function __construct(PriceField $priceField, Currency $currency)
    {
        $this->priceField = $priceField;
        $this->currency = $currency;
    }

    /**
     * @param PriceField $priceField
     * @param Currency $currency
     * @return Price
     */
    public static function make(PriceField $priceField, Currency $currency)
    {
        return new Price($priceField, $currency);
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return Currency::KEY . '_' . $this->getPriceField()->getId() . '_' . $this->currency->getId();
    }

    /**
     * @return string
     */
    public function getRequiredKey()
    {
        return Field::IS_REQUIRED . '_' . $this->getKey();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->priceField->getName() . ' (' . $this->currency->getName() . ')';
    }

    /**
     * @return PriceField
     */
    public function getPriceField()
    {
        return $this->priceField;
    }

    /**
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'id' => $this->priceField->getId(),
            'name' => $this->getName(),
            'key' => $this->getKey(),
            'sign' => $this->currency->getSign(),
            'isRequired' => $this->isRequired(),
            'currency' => $this->getCurrency(),
        ];
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->priceField->isPriceRequired($this);
    }

    /**
     * @return string
     */
    public function getSign()
    {
        return $this->currency->getSign();
    }

    /**
     * @param int|float $value
     * @return string
     */
    public function formatValue($value)
    {
        if (trim($value) === '') {
            return vehicaApp('contact_for_price_string');
        }

        if ($this->currency->isIndian()) {
            $formattedValue = $this->currency->formatIndia($value);
        } else {
            $formattedValue = number_format(
                $value,
                $this->currency->getDecimalPlaces(),
                $this->currency->getDecimalSeparator(),
                $this->currency->getThousandsSeparator()
            );
        }

        if ($this->currency->isSignPositionAfter()) {
            $formattedValue .= $this->currency->getSign();
        }

        if ($this->currency->isSignPositionBefore()) {
            $formattedValue = $this->currency->getSign() . $formattedValue;
        }

        return $this->priceField->getDisplayBefore() . $formattedValue . $this->priceField->getDisplayAfter();
    }

}