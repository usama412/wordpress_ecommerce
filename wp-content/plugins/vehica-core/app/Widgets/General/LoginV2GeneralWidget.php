<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class LoginV2GeneralWidget
 * @package Vehica\Widgets\General
 */
class LoginV2GeneralWidget extends LoginGeneralWidget
{
    const NAME = 'vehica_login_v2_general_widget';
    const TEMPLATE = 'general/login/login_v2';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Login Form', 'vehica-core');
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
            'no_options',
            [
                'label' => esc_html__('No settings', 'vehica-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->end_controls_section();
    }
    
}