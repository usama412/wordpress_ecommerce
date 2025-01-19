<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Field;
use Vehica\Widgets\Partials\WidgetPartial;

/**
 * Trait FeaturesPartial
 * @package Vehica\Widgets\Car\Single
 */
trait FeaturesPartial
{
    use WidgetPartial;

    protected function addFeatureControls()
    {
        $options  = vehicaApp('simple_text_car_fields_list');
        $repeater = new Repeater();
        $repeater->add_control(
            'field',
            [
                'label'   => esc_html__('Field', 'vehica-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => $options
            ]
        );

        $this->add_control(
            'vehica_fields',
            [
                'label'  => esc_html__('Fields', 'vehica-core'),
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls()
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        $selected = $this->get_settings_for_display('vehica_fields');
        if ( ! is_array($selected) || empty($selected)) {
            return Collection::make();
        }

        return Collection::make($selected)->map(static function ($field) {
            $fieldId = (int)$field['field'];

            return vehicaApp('simple_text_car_fields')->find(static function ($field) use ($fieldId) {
                /* @var Field $field */
                return $field->getId() === $fieldId;
            });
        })->filter(static function ($field) {
            return $field !== false;
        });
    }

}