<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Car;

/**
 * Class DescriptionSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class DescriptionSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_description_single_car_widget';
    const TEMPLATE = 'car/single/description';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Description', 'vehica-core');
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

        $this->addLabelControls();

        $this->addLimitControl();

        $this->addTextTypographyControl('content', '.vehica-car-description');

        $this->addTextColorControl('content', '.vehica-car-description');

        $this->addTextColorControl(
            'show_more',
            '.vehica-show-more',
            esc_html__('Show More Color', 'vehica-core')
        );

        $this->end_controls_section();
    }

    private function addLabelControls()
    {
        $this->add_control(
            'vehica_show_label',
            [
                'label' => esc_html__('Display Label', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            'vehica_label',
            [
                'label' => esc_html__('Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => vehicaApp('description_string')
            ]
        );
    }

    /**
     * @return bool
     */
    public function showLabel()
    {
        $show = $this->get_settings_for_display('vehica_show_label');
        return !empty($show);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = $this->get_settings_for_display('vehica_label');

        if (empty($label)) {
            return vehicaApp('description_string');
        }

        return $label;
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    protected function addLimitControl()
    {
        $this->add_control(
            'vehica_limit',
            [
                'label' => esc_html__('Limit text visible at start to X words', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
            ]
        );
    }

    /**
     * @return bool
     */
    public function limitLength()
    {
        $car = $this->getCar();

        if (!$car instanceof Car) {
            return false;
        }

        return $this->getLimit() > 0
            && count(preg_split('/\s+/', $car->getEscapedContent())) > $this->getLimit();
    }

    public function showTeaser()
    {
        /* @var Car $car */
        $car = $this->getCar();
        echo force_balance_tags(html_entity_decode(wp_trim_words(htmlentities($car->getContent()), $this->getLimit())));
    }

    /**
     * @return int
     */
    private function getLimit()
    {
        return (int)$this->get_settings_for_display('vehica_limit');
    }

}