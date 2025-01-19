<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Components\Card\Car\Card;
use Vehica\Widgets\Partials\CarCardPartialWidget;
use Vehica\Widgets\Partials\Cars\QueryCarsPartialWidget;

/**
 * Class CarGridGeneralWidget
 * @package Vehica\Widgets\General
 */
class CarGridGeneralWidget extends GeneralWidget
{
    use CarCardPartialWidget;
    use QueryCarsPartialWidget;

    const NAME = 'vehica_car_grid_general_widget';
    const TEMPLATE = 'general/car_grid';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Grid', 'vehica-core');
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

        $this->addCardControl();

        $this->addColumnsControls();

        $this->addFeaturedControl();

        $this->addShowCardLabelsControl();

        $this->addQueryCarsControls();

        $this->end_controls_section();
    }

    protected function addFeaturedControl()
    {
        $this->add_control(
            'featured',
            [
                'label' => esc_html__('Narrow results to featured listings', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function featured()
    {
        return !empty($this->get_settings_for_display('featured'));
    }

    private function addCardControl()
    {
        $this->add_control(
            'vehica_card',
            [
                'label' => esc_html__('Card', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    Card::TYPE_V1 => esc_html__('Card V1', 'vehica-core'),
                    Card::TYPE_V2 => esc_html__('Card V2', 'vehica-core'),
                    Card::TYPE_V3 => esc_html__('Card V3', 'vehica-core'),
                ],
                'default' => Card::TYPE_V1
            ]
        );
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        $cardType = $this->get_settings_for_display('vehica_card');
        if ($cardType === Card::TYPE_V2) {
            return $this->getCardV2();
        }

        if ($cardType === Card::TYPE_V3) {
            return $this->getCardV3();
        }

        return $this->getCardV1();
    }

    protected function render()
    {
        /** @noinspection SuspiciousBinaryOperationInspection */
        if ($this->get_settings_for_display('vehica_card') !== Card::TYPE_V3) {
            $this->addColumnAttributes();
        }

        $this->loadTemplate();
    }

    protected function addColumnAttributes()
    {
        $columnClass = [
            'vehica-grid__element',
            'vehica-grid__element--' . $this->getPerRowDesktop(),
            'vehica-grid__element--tablet-' . $this->getPerRowTablet(),
            'vehica-grid__element--mobile-' . $this->getPerRowMobile(),
        ];

        $this->add_render_attribute('column', 'class', implode(' ', $columnClass));
    }

    private function getPerRowDesktop()
    {
        $value = $this->get_settings_for_display('vehica_car_list_per_row');

        if (empty($value)) {
            return '1of3';
        }

        return $value;
    }

    private function getPerRowTablet()
    {
        $value = $this->get_settings_for_display('vehica_car_list_per_row_tablet');

        if (empty($value)) {
            return '1of2';
        }

        return $value;
    }

    private function getPerRowMobile()
    {
        $value = $this->get_settings_for_display('vehica_car_list_per_row_mobile');

        if (empty($value)) {
            return '1of1';
        }

        return $value;
    }

    protected function addColumnsControls()
    {
        $this->add_responsive_control(
            'vehica_car_list_per_row',
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
                'desktop_default' => '1of3',
                'tablet_default' => '1of2',
                'mobile_default' => '1of1',
                'condition' => [
                    'vehica_card!' => Card::TYPE_V3
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'vehica_car_list_column_gap',
            [
                'label' => esc_html__('Gap (px)', 'vehica-core'),
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
                    '{{WRAPPER}} .vehica-grid__element' => 'padding-right: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vehica-grid' => ' margin-right: -{{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'condition' => [
                    'vehica_card!' => Card::TYPE_V3
                ],
                'frontend_available' => true,
            ]
        );
    }

}