<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Class PriceSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class PriceSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_price_single_car_widget';
    const TEMPLATE = 'car/single/price';
    const SHOW_CONTACT_FOR_PRICE = 'show_contact_for_price';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Price', 'vehica-core');
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

        $this->addPriceFieldControl();

        $this->addTextColorControl(
            'vehica_price',
            '.vehica-car-price'
        );

        $this->addTextTypographyControl(
            'vehica_price',
            '.vehica-car-price'
        );

        $this->addTextAlignControl(
            'vehica_price',
            '.vehica-car-price'
        );


        $this->add_control(
            self::SHOW_CONTACT_FOR_PRICE,
            [
                'label' => esc_html__('Force hiding "Contact for a price" text', 'vehica-core'),
                'label_block' => '1',
                'description' => esc_html__('Force hiding "Contact for a price" text even if it is set to true in the Vehica Panel > Basic Setup', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1',
            ]
        );

        $this->end_controls_section();
    }

    private function addPriceFieldControl()
    {
        $list = vehicaApp('price_field_key_list');

        if (count($list) === 1) {
            $this->add_control(
                'vehica_price_field',
                [
                    'label' => esc_html__('Field', 'vehica-core'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => key($list)
                ]
            );

            return;
        }

        $this->add_control(
            'vehica_price_field',
            [
                'label' => esc_html__('Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $list,
                'default' => !empty($list) ? key($list) : null
            ]
        );
    }

    /**
     * @return bool
     */
    public function hasPriceField()
    {
        return $this->getPriceField() !== false;
    }

    /**
     * @return PriceField|false
     */
    public function getPriceField()
    {
        $priceFieldKey = $this->get_settings_for_display('vehica_price_field');

        $priceField = vehicaApp('price_fields')->find(static function ($priceField) use ($priceFieldKey) {
            /* @var PriceField $priceField */
            return $priceField->getKey() === $priceFieldKey;
        });

        if (!$priceField) {
            return vehicaApp('price_fields')->first(false);
        }

        return $priceField;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if (!$this->hasPriceField()) {
            return '';
        }

        return $this->getPriceField()->getFormattedValueByCurrency($this->getCar());
    }

    /**
     * @return bool
     */
    public function hasValue()
    {
        return $this->getValue() !== '';
    }

    /**
     * @return bool
     */
    public function showContactForPrice()
    {
        return vehicaApp('show_contact_for_price') && empty((int)$this->get_settings_for_display(self::SHOW_CONTACT_FOR_PRICE));
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}