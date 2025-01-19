<?php

namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;

/**
 * Trait SwiperPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait SwiperPartialWidget
{
    use WidgetPartial;

    /**
     * @return array
     */
    public function getSwiperConfig()
    {
        return [
            'prevNextButtons' => false,
            'loop' => true,
            'autoplay' => $this->isAutoplay(),
            'autoplayDelay' => $this->getAutoplayDelay(),
            'settings' => [
                'spaceBetween' => 22,
                'loopFillGroupWithBlank' => false,
            ]
        ];
    }

    /**
     * @param bool $isEnabledByDefault
     * @param int $delay
     */
    protected function addAutoplayControl($isEnabledByDefault = false, $delay = 3000)
    {
        $this->add_control(
            'swiper_autoplay',
            [
                'label' => esc_html__('Autoplay', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => $isEnabledByDefault ? '1' : '0'
            ]
        );

        $this->add_control(
            'swiper_autoplay_delay',
            [
                'label' => esc_html__('Delay', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => $delay,
                'condition' => [
                    'swiper_autoplay' => '1'
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    protected function isAutoplay()
    {
        $autoplay = $this->get_settings_for_display('swiper_autoplay');
        return !empty($autoplay);
    }

    /**
     * @return int
     */
    protected function getAutoplayDelay()
    {
        return (int)$this->get_settings_for_display('swiper_autoplay_delay');
    }

}