<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class ShortInfoGeneralWidget
 * @package Vehica\Widgets\General
 */
class ShortInfoGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_short_info_general_widget';
    const TEMPLATE = 'general/short_info';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Short Info', 'vehica-core');
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

        $this->addTextAlignControl(
            'vehica_short_info',
            '.vehica-short-info'
        );

        $this->addTextColorControl(
            'vehica_short_info',
            '.vehica-short-info'
        );

        $this->end_controls_section();
    }

}