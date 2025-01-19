<?php


namespace Vehica\Widgets\General;

use Elementor\Controls_Manager;

/**
 * Class CurrencySwitcherGeneralWidget
 * @package Vehica\Widgets\General
 */
class CurrencySwitcherGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_currency_switcher_general_widget';
    const TEMPLATE = 'general/currency_switcher';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Currency Switcher', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'type' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTextColorControl(
            'vehica_text_color',
            '.vehica-currency-switcher--widget .vehica-currency-switcher__inner'
        );

        $this->addTextColorControl(
            'vehica_arrow_color',
            '.vehica-currency-switcher--widget .vehica-currency-switcher__inner i'
        );

        $this->end_controls_section();
    }

}