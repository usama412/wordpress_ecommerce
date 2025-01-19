<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Components\Card\Car\CardV1;
use Vehica\Core\Car\CarFields;
use Vehica\Widgets\Partials\CarCardPartialWidget;
use Vehica\Widgets\Partials\CarListPartialWidget;
use Vehica\Widgets\Partials\Cars\QueryCarsPartialWidget;
use Vehica\Widgets\Partials\Cars\RelatedCars;
use Vehica\Widgets\Partials\SwiperPartialWidget;

/**
 * Class RelatedCarCarouselSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class RelatedCarCarouselSingleCarWidget extends SingleCarWidget
{
    use CarListPartialWidget;
    use RelatedCars;
    use CarCardPartialWidget;
    use QueryCarsPartialWidget;
    use SwiperPartialWidget;

    const NAME = 'vehica_related_car_carousel_single_car_widget';
    const TEMPLATE = 'car/single/related_car_carousel';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Related Listing Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('Related Listing Grid', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->addHeadingControl();

        $this->addRelatedByControls();

        $this->addQueryCarsLimitControl();

        $this->addQueryCarsSortByControl();

        $this->addIncludeExcludedControl();

        $this->addArrowsPositionControl();

        $this->addShowCardLabelsControl();

        $this->end_controls_section();
    }

    private function addHeadingControl()
    {
        $this->add_control(
            'show_heading',
            [
                'label' => esc_html__('Display Heading', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('related_cars_string'),
                'default' => ''
            ]
        );
    }

    /**
     * @return bool
     */
    public function showHeading()
    {
        $show = $this->get_settings_for_display('show_heading');
        return !empty($show);
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        $heading = (string)$this->get_settings_for_display('heading');

        if (empty($heading)) {
            return vehicaApp('related_cars_string');
        }

        return $heading;
    }

    protected function render()
    {
        parent::render();

        $this->addColumnAttributes();

        CarFields::make(CardV1::getFields(), $this->getRelatedCars())->prepare();

        $this->loadTemplate();
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

}