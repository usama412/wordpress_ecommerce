<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class EmailGeneralWidget
 * @package Vehica\Widgets\General
 */
class EmailGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_email_general_widget';
    const TEMPLATE = 'general/email';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Email', 'vehica-core');
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
            'vehica_show_icon',
            [
                'label' => esc_html__('Display Icon', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->addTextAlignControl(
            'vehica_email',
            '.vehica-email'
        );

        $this->addTextColorControl(
            'vehica_email',
            '.vehica-email a'
        );

        $this->end_controls_section();
    }

    /**
     * @return bool
     */
    public function showIcon()
    {
        $show = $this->get_settings_for_display('vehica_show_icon');
        return !empty($show);
    }

}