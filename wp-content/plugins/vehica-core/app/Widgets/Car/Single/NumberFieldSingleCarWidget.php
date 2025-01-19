<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Class NumberFieldSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class NumberFieldSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_number_field_single_car_widget';
    const TEMPLATE = 'car/single/number_field';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Number Field Value', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addNumberFieldControl();

        $this->addTextColorControl(
            'vehica_number_field',
            '.vehica-car-number-field'
        );

        $this->addTextTypographyControl(
            'vehica_number_field',
            '.vehica-car-number-field'
        );

        $this->addTextAlignControl(
            'vehica_number_field',
            '.vehica-car-number-field'
        );

        $this->end_controls_section();
    }

    private function addNumberFieldControl()
    {
        $list = [];
        foreach (vehicaApp('number_fields') as $numberField) {
            if ($numberField instanceof PriceField) {
                continue;
            }

            /* @var NumberField $numberField */
            $list[$numberField->getKey()] = $numberField->getName();
        }

        $this->add_control(
            'number_field',
            [
                'label' => esc_html__('Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $list,
                'default' => !empty($list) ? key($list) : null
            ]
        );
    }

    /**
     * @return NumberField|false
     */
    public function getNumberField()
    {
        $key = $this->get_settings_for_display('number_field');
        return vehicaApp('number_fields')->find(static function ($numberField) use ($key) {
            /* @var NumberField $numberField */
            return $numberField->getKey() === $key;
        });
    }

    /**
     * @return bool
     */
    public function hasNumberField()
    {
        return $this->getNumberField() !== false;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        $numberField = $this->getNumberField();
        if (!$numberField) {
            return '';
        }

        $car = $this->getCar();
        if (!$car) {
            return '';
        }

        return $numberField->getTextValue($car);
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->getValue() !== '';
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}