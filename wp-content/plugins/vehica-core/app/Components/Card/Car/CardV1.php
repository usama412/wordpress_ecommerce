<?php


namespace Vehica\Components\Card\Car;


use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class CardV1
 * @package Vehica\Components\Card\Car
 */
class CardV1 extends Card
{
    /**
     * CardV1 constructor.
     *
     * @param bool $showLabels
     */
    public function __construct($showLabels = true)
    {
        $this->showLabels = $showLabels;
    }

    /**
     * @param array $cardConfig
     *
     * @return static
     */
    public static function create(array $cardConfig)
    {
        return new static(!empty($cardConfig['showLabels']));
    }

    /**
     * @param Car $car
     *
     * @return Collection
     */
    public function getAttributes(Car $car)
    {
        $attributes = Collection::make();

        foreach (vehicaApp('card_features') as $attribute) {
            /* @var SimpleTextAttribute $attribute */
            $attributes = $attributes->merge($attribute->getSimpleTextValues($car));
        }

        return $attributes;
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
        return 'card_v1';
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Card::TYPE_V1;
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

        foreach (vehicaApp('card_features') as $field) {
            $fields[] = $field;
        }

        return $fields;
    }

}