<?php

namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Widgets\Partials\CarAttributesPartialWidget;

/**
 * Class AttributesSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class AttributesSingleCarWidget extends SingleCarWidget
{
    use CarAttributesPartialWidget;

    const NAME = 'vehica_attributes_single_car_widget';
    const TEMPLATE = 'car/single/attributes';
    const LIMIT_AT_START = 'vehica_attributes_limit_at_start';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Details', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('Listing Details', 'vehica-core'),
                'type' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_responsive_control(
            'vehica_columns',
            [
                'label' => esc_html__('Columns', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1of1' => esc_html__('1', 'vehica-core'),
                    '1of2' => esc_html__('2', 'vehica-core'),
                    '1of3' => esc_html__('3', 'vehica-core'),
                    '1of4' => esc_html__('4', 'vehica-core'),
                    '1of5' => esc_html__('5', 'vehica-core'),
                    '1of6' => esc_html__('6', 'vehica-core'),
                ],
                'default' => '1of1',
                'desktop_default' => '1of2',
                'mobile_default' => '1of1',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'vehica_columns_gap',
            [
                'label' => esc_html__('Columns Gap (px)', 'vehica-core'),
                'description' => esc_html__('Columns Gap works only if element contains more than one column', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-car-attributes-grid > .vehica-grid__element' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vehica-car-attributes-grid' => ' margin-right: -{{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            self::LIMIT_AT_START,
            [
                'label' => esc_html__('Limit number of attributes at start', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'label_block' => true,
                'default' => '',
            ]
        );

        $this->addAttributesControl(vehicaApp('simple_text_car_fields')->filter(static function ($field) {
            return !$field instanceof PriceField;
        }));


        $this->end_controls_section();
    }

    protected function addStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_name_style',
            [
                'label' => esc_html__('Attribute Name', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl(
            'vehica_attributes_name',
            '.vehica-car-attributes__name'
        );

        $this->addTextColorControl(
            'vehica_attributes_name',
            '.vehica-car-attributes__name'
        );

        $this->end_controls_section();

        $this->start_controls_section(
            self::NAME . '_values_style',
            [
                'label' => esc_html__('Attribute Values', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'vehica_attributes_values',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Attribute Values', 'vehica-core')
            ]
        );

        $this->addTextTypographyControl(
            'vehica_attributes_values',
            '.vehica-car-attributes__values'
        );

        $this->addTextColorControl(
            'vehica_attributes_values',
            '.vehica-car-attributes__values'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $columnClasses = [
            'vehica-grid__element',
            'vehica-grid__element--' . $this->get_settings_for_display('vehica_columns'),
            'vehica-grid__element--tablet-' . $this->get_settings_for_display('vehica_columns_tablet'),
            'vehica-grid__element--mobile-' . $this->get_settings_for_display('vehica_columns_mobile'),
        ];

        $this->add_render_attribute('column', 'class', implode(' ', $columnClasses));

        $this->loadTemplate();
    }

    /**
     * @return bool
     */
    public function showTeaser()
    {
        return $this->getInitialLimit() > 0 && $this->getInitialLimit() < count($this->getAttributes());
    }

    /**
     * @return int
     */
    public function getInitialLimit()
    {
        return (int)$this->get_settings_for_display(self::LIMIT_AT_START);
    }

    /**
     * @return Collection
     */
    public function getTeaserAttributes()
    {
        if ($this->getAttributes()->count() <= $this->getInitialLimit()) {
            return $this->getAttributes();
        }

        return $this->getAttributes()->slice(0, $this->getInitialLimit());
    }

}