<?php

namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;

/**
 * Trait SwiperSettingsPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait SwiperSettingsPartialWidget
{

    protected function addAutoPlayControls()
    {
        $this->add_control(
            'vehica_swiper_autoplay',
            [
                'label' => esc_html__('Autoplay', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );

        $this->add_control(
            'vehica_swiper_autoplay_delay',
            [
                'label' => esc_html__('Delay', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 3000,
                'condition' => [
                    'vehica_swiper_autoplay' => '1'
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    protected function isAutoPlayEnabled()
    {
        $autoplay = $this->get_settings_for_display('vehica_swiper_autoplay');
        return !empty($autoplay);
    }

    /**
     * @return int
     */
    protected function getAutoPlayDelay()
    {
        return (int)$this->get_settings_for_display('vehica_swiper_autoplay_delay');
    }

}