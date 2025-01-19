<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Components\Card\Car\CardV1;
use Vehica\Core\Collection;
use Vehica\Widgets\Partials\CarCardPartialWidget;
use Vehica\Widgets\Partials\CarTabsPartialWidget;
use Vehica\Widgets\Partials\SwiperPartialWidget;

/**
 * Class CarTabsCarouselGeneralWidget
 * @package Vehica\Widgets\General
 */
class CarTabsCarouselGeneralWidget extends GeneralWidget
{
    use SwiperPartialWidget;
    use CarTabsPartialWidget;
    use CarCardPartialWidget;

    const NAME = 'vehica_car_tabs_carousel_general_widget';
    const TEMPLATE = 'general/car_tabs_carousel';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Vehicle Tabs Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('Vehicle Tabs Carousel', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addHeadingTextControl();

        $this->addOrderByControl();

        $this->addTabsControls();

        $this->addFeaturedControl();

        $this->addLimitControl();

        $this->addIncludeExcludedControl();

        $this->addShowCardLabelsControl();

        $this->addArrowsPositionControl();

        $this->addShowControl(
            'view_all_button',
            esc_html__('Display "View All Button"', 'vehica-core')
        );

        $this->end_controls_section();
    }

    private function addArrowsPositionControl()
    {
        $this->add_control(
            'arrows_position',
            [
                'label' => esc_html__('Arrows Position', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'inside',
                'options' => [
                    'inside' => esc_html__('Inside', 'vehica-core'),
                    'outside' => esc_html__('Outside', 'vehica-core'),
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getArrowsPosition()
    {
        return (string)$this->get_settings_for_display('arrows_position');
    }

    private function addLimitControl()
    {
        $this->add_control(
            'vehica_cars_limit',
            [
                'label' => esc_html__('Number of vehicles', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 12
            ]
        );
    }

    private function addHeadingTextControl()
    {
        $this->add_control(
            'vehica_heading_text',
            [
                'label' => esc_html__('Heading Text', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Heading', 'vehica-core')
            ]
        );
    }

    /**
     * @return string
     */
    public function getHeadingText()
    {
        return (string)$this->get_settings_for_display('vehica_heading_text');
    }

    protected function render()
    {
        global $vehicaCarCardV1;
        $vehicaCarCardV1 = $this->getCardV1();

        $this->loadTemplate();
    }

    /**
     * @return int
     */
    public function getCarsNumber()
    {
        return (int)$this->get_settings_for_display('vehica_cars_limit');
    }

    /**
     * @return Collection
     */
    protected function getCarFields()
    {
        return CardV1::getFields();
    }

    /**
     * @return array
     */
    public function getBreakpoints()
    {
        return [
            [
                'width' => 1200,
                'number' => 4
            ],
            [
                'width' => 900,
                'number' => 3
            ],
            [
                'width' => 600,
                'number' => 2
            ],
            [
                'width' => 0,
                'number' => 1
            ],
        ];
    }

}