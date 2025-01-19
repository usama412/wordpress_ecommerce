<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Managers\CompareManager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;

/**
 * Class CompareGeneralWidget
 * @package Vehica\Widgets\General
 */
class CompareGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_compare_general_widget';
    const TEMPLATE = 'general/compare';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Compare', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'vehica_compare',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addFieldsControls();

        $this->add_control(
            'hide_empty_rows',
            [
                'label' => esc_html__('Hide Empty Rows', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->end_controls_section();
    }

    public function hideEmptyRows()
    {
        return !empty((int)$this->get_settings_for_display('hide_empty_rows'));
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        return Collection::make(CompareManager::getCarIds())->map(static function ($carId) {
            return Car::getById($carId);
        })->filter(static function ($car) {
            return $car !== false;
        });
    }

    /**
     * @return array
     */
    public function getCarsData()
    {
        $fields = $this->getFields();

        return $this->getCars()->map(static function ($car) use ($fields) {
            /* @var Car $car */
            $data = [
                'id' => $car->getId(),
                'name' => $car->getName(),
                'image' => $car->getImageUrl('vehica_670_372'),
                'url' => $car->getUrl(),
            ];

            foreach ($fields as $field) {
                /* @var SimpleTextAttribute $attribute */
                $attribute = $field['field'];
                if (!$field['multiple']) {
                    $data[$attribute->getId()] = $attribute->getSimpleTextValues($car)->implode();
                    continue;
                }

                if (!$attribute instanceof Taxonomy) {
                    continue;
                }

                $data[$attribute->getId()] = [];

                $terms = $attribute->getTerms();
                foreach ($terms as $term) {
                    /* @var Term $term */
                    if ($car->hasTerm($term)) {
                        $data[$attribute->getId()][] = $term->getId();
                    }
                }
            }

            return $data;
        })->all();
    }

    private function addFieldsControls()
    {
        $options = $this->getFieldOptions();

        $fields = new Repeater();

        $fields->add_control(
            'key',
            [
                'label' => esc_html__('Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => ''
            ]
        );

        $fields->add_control(
            'featured',
            [
                'label' => esc_html__('Is Featured', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );

        foreach ($this->getFieldMultipleKeys() as $key) {
            $fields->add_control(
                'multiple_type_' . $key,
                [
                    'label' => esc_html__('Display Type', 'vehica-core'),
                    'type' => Controls_Manager::SELECT,
                    'options' => [
                        'yes_no' => esc_html__('Yes/No', 'vehica-core'),
                        'string' => esc_html__('String', 'vehica-core'),
                    ],
                    'default' => 'yes_no',
                    'condition' => [
                        'key' => $key,
                    ]
                ]
            );

            $fields->add_control(
                'hide_empty_' . $key,
                [
                    'label' => esc_html__('Hide When All Empty', 'vehica-core'),
                    'type' => Controls_Manager::SWITCHER,
                    'return_value' => '1',
                    'default' => '0',
                    'condition' => [
                        'key' => $key,
                        'multiple_type_' . $key => 'yes_no',
                    ]
                ]
            );
        }

        $this->add_control(
            'fields',
            [
                'label' => esc_html__('Fields', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $fields->get_controls(),
                'prevent_empty' => false,
            ]
        );
    }

    /**
     * @return array
     */
    private function getFieldOptions()
    {
        $options = [];

        foreach (vehicaApp('car_fields') as $field) {
            /* @var Field $field */
            if ($field instanceof SimpleTextAttribute) {
                $options[$field->getKey()] = $field->getName();
            }
        }

        return $options;
    }

    /**
     * @return array
     */
    private function getFieldMultipleKeys()
    {
        return vehicaApp('taxonomies')
            ->filter(static function ($taxonomy) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->allowMultiple();
            })
            ->map(static function ($taxonomy) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->getKey();
            })
            ->all();
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        $fieldSettings = $this->get_settings_for_display('fields');

        if (empty($fieldSettings) || !is_array($fieldSettings)) {
            return $this->getDefaultFields();
        }

        return Collection::make($fieldSettings)
            ->map(static function ($fieldSetting) {
                $field = vehicaApp('car_fields')->find(static function ($field) use ($fieldSetting) {
                    /* @var Field $field */
                    return $field->getKey() === $fieldSetting['key'];
                });

                if (!$field instanceof Field) {
                    return false;
                }

                if ($field instanceof Taxonomy && $field->allowMultiple()) {
                    $multiple = $fieldSetting['multiple_type_' . $field->getKey()] === 'yes_no';
                    $hideEmpty = !empty($fieldSetting['hide_empty_' . $field->getKey()]);
                } else {
                    $multiple = false;
                    $hideEmpty = false;
                }

                return [
                    'field' => $field,
                    'featured' => !empty($fieldSetting['featured']),
                    'multiple' => $multiple,
                    'hide_empty' => $hideEmpty,
                ];
            })
            ->filter(static function ($field) {
                return $field !== false;
            });
    }

    /**
     * @return Collection
     */
    private function getDefaultFields()
    {
        $fields = Collection::make();

        foreach (vehicaApp('price_fields') as $priceField) {
            /* @var PriceField $priceField */
            $fields[] = [
                'field' => $priceField,
                'featured' => true,
                'multiple' => false,
            ];
        }

        foreach (vehicaApp('simple_text_car_fields') as $field) {
            /* @var SimpleTextAttribute $field */
            if ($field instanceof PriceField) {
                continue;
            }

            if ($field instanceof Taxonomy && $field->allowMultiple()) {
                continue;
            }

            $fields[] = [
                'field' => $field,
                'featured' => false,
                'multiple' => false,
            ];
        }

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            if (!$taxonomy->allowMultiple()) {
                continue;
            }

            $fields[] = [
                'field' => $taxonomy,
                'featured' => false,
                'multiple' => true,
            ];
        }

        return $fields;
    }

}