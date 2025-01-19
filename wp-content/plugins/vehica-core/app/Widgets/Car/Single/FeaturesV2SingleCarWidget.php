<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class FeaturesV2SingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class FeaturesV2SingleCarWidget extends SingleCarWidget
{
    use FeaturesPartial;

    const NAME = 'vehica_features_v2_single_car_widget';
    const TEMPLATE = 'car/single/features_v2';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Features V2', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addFeatureControls();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}