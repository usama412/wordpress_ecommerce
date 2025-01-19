<?php


namespace Vehica\Components\Card\Car;


use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\LocationField;

/**
 * Class CardV3
 *
 * @package Vehica\Components\Card\Car
 */
class CardV3 extends Card
{
    /**
     * CardV3 constructor.
     *
     * @param bool $showLabels
     */
    public function __construct($showLabels = true)
    {
        $this->showLabels = $showLabels;
    }

    /**
     * @param Car $car
     *
     * @return Collection
     */
    public function getPrimaryAttributes(Car $car)
    {
        $attributes = Collection::make();

        foreach (vehicaApp('row_primary_features') as $attribute) {
            /* @var SimpleTextAttribute $attribute */
            $attributes = $attributes->merge($attribute->getSimpleTextValues($car));
        }

        return $attributes;
    }

    /**
     * @param Car $car
     *
     * @return Collection
     */
    public function getSecondaryValues(Car $car)
    {
        $values = Collection::make();

        foreach (vehicaApp('row_secondary_features') as $attribute) {
            $values = $values->merge($attribute->getSimpleTextValues($car));
        }

        return $values;
    }

    /**
     * @return string
     */
    protected function getImageSize()
    {
        return 'vehica_670_372';
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'card_v3';
    }

    /**
     * @return bool
     */
    public function showFinanceCalculator()
    {
        return vehicaApp('calculator_page_url') && !vehicaApp('row_hide_calculate');
    }

    /**
     * @param array $config
     * @return Card|static
     */
    public static function create(array $config)
    {
        return new static(!empty($config['showLabels']));
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Card::TYPE_V3;
    }

    /**
     * @return Collection
     */
    public static function getFields()
    {
        $fields = Collection::make();

        if (vehicaApp('card_price_field')) {
            $fields[] = vehicaApp('card_price_field');
        }

        if (vehicaApp('card_gallery_field')) {
            $fields[] = vehicaApp('card_gallery_field');
        }

        foreach (vehicaApp('row_primary_features') as $field) {
            $fields[] = $field;
        }

        foreach (vehicaApp('row_secondary_features') as $field) {
            $fields[] = $field;
        }

        return $fields;
    }

    /**
     * @param Car $car
     * @return false|string
     */
    public function getLocation(Car $car)
    {
        if (!vehicaApp('row_location_type')) {
            return false;
        }

        /** @noinspection SuspiciousBinaryOperationInspection */
        if (vehicaApp('row_location_type') === 'user_location') {
            return $this->getUserLocation($car);
        }

        return $this->getFieldLocation($car);
    }

    /**
     * @param Car $car
     * @return false|string
     */
    private function getFieldLocation(Car $car)
    {
        $locationField = vehicaApp('location_fields')->find(static function ($locationField) {
            /* @var LocationField $locationField */
            return $locationField->getKey() === vehicaApp('row_location_type');
        });

        if (!$locationField instanceof LocationField) {
            return false;
        }

        $value = $locationField->getValue($car);
        if (empty($value['address'])) {
            return false;
        }

        return $value['address'];
    }

    /**
     * @param Car $car
     * @return false|string
     */
    private function getUserLocation(Car $car)
    {
        $user = $car->getUser();

        if (!$user) {
            return false;
        }

        if (vehicaApp('settings_config')->isUserAddressTextInput()) {
            return $user->hasDisplayAddress() ? $user->getDisplayAddress() : false;
        }

        return $user->hasAddress() ? $user->getAddress() : false;
    }

}