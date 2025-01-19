<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class BigFeaturesSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class BigFeaturesSingleCarWidget extends FeaturesSingleCarWidget
{
    const NAME = 'vehica_big_features_single_car_widget';
    const TEMPLATE = 'car/single/big_features';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Big Features', 'vehica-core');
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

        $this->add_control(
            'limit_number',
            [
                'label' => esc_html__('Initial Limit', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Limit Number', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    'limit_number' => '1',
                ]
            ]
        );

        $this->addFeatureControls();

        $this->addTextTypographyControl(
            'vehica_big_feature',
            '.vehica-car-features-pills__single'
        );

        $this->addTextColorControl(
            'vehica_big_feature',
            '.vehica-car-features-pills__single'
        );

        $this->addBackgroundColorControl(
            'vehica_big_feature',
            '.vehica-car-features-pills__single'
        );

        $this->addBorderColorControl(
            'vehica_big_feature',
            '.vehica-car-features-pills__single'
        );

        $this->addPaddingControl(
            'vehica_big_feature',
            '.vehica-car-features-pills__single'
        );

        $this->addShowControl('icon', esc_html__('Display Icon'));

        $this->end_controls_section();
    }

    /**
     * @return bool
     */
    public function isInitialLimitEnabled()
    {
        return !empty($this->get_settings_for_display('limit_number'));
    }

    /**
     * @return int
     */
    public function getInitialLimit()
    {
        $limit = (int)$this->get_settings_for_display('limit');

        if (empty($limit)) {
            return 5;
        }

        return $limit;
    }

}