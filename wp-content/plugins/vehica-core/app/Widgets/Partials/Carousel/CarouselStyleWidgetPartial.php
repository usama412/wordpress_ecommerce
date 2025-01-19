<?php

namespace Vehica\Widgets\Partials\Carousel;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Partials\WidgetPartial;
use Elementor\Controls_Manager;

/**
 * Trait CarouselStyle
 * @package Vehica\Widgets\Partials\Carousel
 */
trait CarouselStyleWidgetPartial
{
    use WidgetPartial;

    public function addCarouselStyle()
    {
        $this->start_controls_section(
            'vehica_carousel_style',
            [
                'label' => esc_html__('Carousel', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addCarouselControl();

        $this->addCarouselSlidesPerViewControl();

        $this->addCarouselEffectControl();

        $this->addCarouselSpaceBetweenSlidesControl();

        $this->addCarouselCenterSlidesMobileControl();

        $this->addCarouselNavigationControls();

        $this->addCarouselSpeedControl();

        $this->addCarouselAutoPlayControls();

        $this->end_controls_section();
    }

    public function addCarouselControl()
    {
        $this->add_control(
            'vehica_carousel',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '1'
            ]
        );
    }

    public function addCarouselSlidesPerViewControl()
    {
        $this->add_responsive_control(
            'vehica_carousel_slides_per_view',
            [
                'label' => esc_html__('Columns', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '25%' => '4',
                    '33.33%' => '3',
                    '50%' => '2',
                    '100%' => '1',
                ],
                'devices' => ['desktop', 'tablet', 'mobile'],
                'desktop_default' => '33.33%',
                'tablet_default' => '50%',
                'mobile_default' => '100%',
                'selectors' => [
                    '{{WRAPPER}} .da-carousel__slide' => 'width: {{VALUE}};'
                ],
                'frontend_available' => true,
            ]
        );
    }

    public function addCarouselEffectControl()
    {
        $this->add_control(
            'vehica_carousel_effect',
            [
                'label' => esc_html__('Effect', 'vehica'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'slide' => esc_html__('Slide', 'vehica-core'),
                    'fade' => esc_html__('Fade', 'vehica-core')
                ],
                'default' => 'slide'
            ]
        );
    }

    public function addCarouselCenterSlidesMobileControl()
    {
        $this->add_control(
            'vehica_carousel_mobile_center_slides',
            [
                'label' => esc_html__('Center cards on mobile', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );
    }

    public function addCarouselSpaceBetweenSlidesControl()
    {
        $this->add_responsive_control(
            'vehica_carousel_slides_space',
            [
                'label' => esc_html__('Space between elements', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 64,
                    ],
                ],
                'desktop_default' => [
                    'size' => 32,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => 16,
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'size' => 8,
                    'unit' => 'px',
                ],
                'frontend_available' => true,
            ]
        );
    }

    public function addCarouselNavigationControls()
    {
        $this->add_control(
            'vehica_carousel_pagination',
            [
                'label' => esc_html__('Pagination', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_html__('Bullets', 'vehica-core'),
                    'fraction' => esc_html__('Fraction', 'vehica-core'),
                    'progressbar' => esc_html__('Progressbar', 'vehica-core')
                ],
                'default' => 'bullets',
            ]
        );

        $this->add_control(
            'vehica_carousel_pagination_dynamic_bullets',
            [
                'label' => esc_html__('Dynamic bullets', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
                'condition' => [
                    'vehica_carousel_pagination' => 'bullets'
                ]
            ]
        );
    }

    public function addCarouselSpeedControl()
    {
        $this->add_control(
            'vehica_carousel_speed',
            [
                'label' => esc_html__('Transition speed (ms)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300
            ]
        );
    }

    public function addCarouselAutoPlayControls()
    {
        $this->add_control(
            'vehica_carousel_autoplay',
            [
                'label' => esc_html__('Autoplay', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );

        $this->add_control(
            'vehica_carousel_autoplay_delay',
            [
                'label' => esc_html__('Autoplay delay (ms)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2500,
                'condition' => [
                    'vehica_carousel_autoplay' => '1'
                ]
            ]
        );
    }

}