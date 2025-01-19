<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class AddressGeneralWidget
 * @package Vehica\Widgets\General
 */
class AddressGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_address_general_widget';
    const TEMPLATE = 'general/address';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Address', 'vehica-core');
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
            'vehica_address',
            '.vehica-address'
        );

        $this->addTextColorControl(
            'vehica_address',
            '.vehica-address a'
        );

        $this->end_controls_section();
    }

}