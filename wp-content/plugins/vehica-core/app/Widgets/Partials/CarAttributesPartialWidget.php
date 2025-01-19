<?php

namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Attribute\SimpleTextValue;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Trait CarAttributes
 * @package Vehica\Widgets\Partials
 */
trait CarAttributesPartialWidget
{
    /**
     * @var Collection
     */
    protected $attributes;

    /**
     * @param Collection|null $defaultFields
     */
    protected function addAttributesControl($defaultFields = null)
    {
        $fields = vehicaApp('simple_text_car_fields');
        $options = [];
        $fields->each(static function ($field) use (&$options) {
            /* @var SimpleTextAttribute $field */
            $options['vehica_' . $field->getId()] = $field->getName();
        });
        asort($options);

        if ($defaultFields !== null) {
            $defaultValues = $defaultFields->map(static function ($field) {
                /* @var Field $field */
                return [
                    'attribute_id' => 'vehica_' . $field->getId()
                ];
            })->all();
        } else {
            $defaultValues = [];
        }

        $attributes = new Repeater();
        $attributes->add_control(
            'attribute_id',
            [
                'label' => esc_html__('Attribute', 'vehica-core'),
                'options' => $options,
                'type' => Controls_Manager::SELECT2,
                'default' => key($options)
            ]
        );

        $this->add_control(
            'vehica_attributes',
            [
                'label' => esc_html__('Attributes', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $attributes->get_controls(),
                'default' => $defaultValues,
                'prevent_empty' => false,
                'title_field' => '{{{ vehicaSetFieldTitle(attribute_id) }}}'
            ]
        );
    }

    protected function prepareAttributes()
    {
        $car = $this->getCar();
        $attributes = $this->get_settings_for_display('vehica_attributes');

        if (!is_array($attributes) || empty($attributes)) {
            return Collection::make();
        }

        return Collection::make($attributes)->map(static function ($attribute) {
            $attributeId = (int)str_replace('vehica_', '', $attribute['attribute_id']);
            return vehicaApp('simple_text_car_fields')->find(static function ($simpleTextAttribute) use ($attributeId) {
                /* @var SimpleTextAttribute $simpleTextAttribute */
                return $simpleTextAttribute->getId() === $attributeId;
            });
        })->filter(static function ($simpleTextAttribute) {
            return $simpleTextAttribute !== false;
        })->map(static function ($simpleTextAttribute) use ($car) {
            /* @var SimpleTextAttribute $simpleTextAttribute */
            if ($simpleTextAttribute instanceof PriceField) {
                $value = $simpleTextAttribute->getFormattedValueByCurrency($car);
                if (!empty($value)) {
                    $values = [$simpleTextAttribute->getFormattedValueByCurrency($car)];
                }
            } else {
                $values = $simpleTextAttribute->getSimpleTextValues($car)->map(static function ($simpleTextValue) {
                    /* @var SimpleTextValue $simpleTextValue */
                    return $simpleTextValue->value;
                })->all();
            }

            if (empty($values)) {
                return false;
            }

            return [
                'name' => $simpleTextAttribute->getName(),
                'values' => $values
            ];
        })->filter(static function ($attribute) {
            return $attribute !== false;
        });
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        if (!$this->attributes instanceof Collection) {
            $this->attributes = $this->prepareAttributes();
        }

        return $this->attributes;
    }

}