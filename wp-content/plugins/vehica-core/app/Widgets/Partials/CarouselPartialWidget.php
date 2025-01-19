<?php

namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;

/**
 * Trait CarouselPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait CarouselPartialWidget
{
    use SwiperPartialWidget;

    protected function addVisibleItemsControl()
    {
        $this->add_responsive_control(
            'vehica_visible_items',
            [
                'label' => esc_html__('Slides Per View', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1' => esc_html__('1', 'vehica-core'),
                    '2' => esc_html__('2', 'vehica-core'),
                    '3' => esc_html__('3', 'vehica-core'),
                    '4' => esc_html__('4', 'vehica-core'),
                    '5' => esc_html__('5', 'vehica-core'),
                    '6' => esc_html__('6', 'vehica-core'),
                ],
                'default' => '3',
                'default_tablet' => '2',
                'default_mobile' => '1',
                'frontend_available' => true,
            ]
        );
    }

    /**
     * @param int $default
     */
    protected function addCarouselMaxWidthControl($default = 826)
    {
        $this->add_responsive_control(
            'vehica_carousel_max_width',
            [
                'label' => esc_html__('Max Width', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => $default,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-carousel__swiper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
                'render_type' => 'template',
                'frontend_available' => true,
            ]
        );
    }

    /**
     * @param array $exclude
     */
    protected function addCarouselStyleSection($exclude = [])
    {
        $this->start_controls_section(
            'vehica_carousel_style',
            [
                'label' => esc_html__('Carousel', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->addCarouselStyleControls($exclude);

        $this->end_controls_section();
    }

    /**
     * @param array $exclude
     */
    protected function addCarouselStyleControls($exclude = [])
    {
        if (!is_array($exclude)) {
            $exclude = [$exclude];
        }

        if (!in_array('vehica_carousel_max_width', $exclude, true)) {
            $this->addCarouselMaxWidthControl();
        }

        $this->add_control(
            'vehica_carousel_bullets',
            [
                'label' => esc_html__('Bullets', 'vehica-core'),
                'type' => Controls_Manager::HEADING
            ]
        );

        $this->add_responsive_control(
            'vehica_carousel_bullets_offset_top',
            [
                'label' => esc_html__('Bullets Offset Top (px)', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 64,
                ],
                'desktop_default' => [
                    'size' => '64',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'size' => '32',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-swiper-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'vehica_carousel_bullets_size',
            [
                'label' => esc_html__('Bullet Size', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 48,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 8,
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-swiper-pagination' => 'height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};width: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addBackgroundColorControl(
            'vehica_user_list_bullet_active',
            '.swiper-pagination-bullet-active',
            esc_html__('Active Bullet Color', 'vehica-core')
        );

        $this->addBackgroundColorControl(
            'vehica_carousel_bullet',
            '.swiper-pagination-bullet',
            esc_html__('Other Bullets Color', 'vehica-core')
        );

    }

    /**
     * @return int
     */
    abstract protected function getItemsNumber();

    /**
     * @return string
     */
    public function getVisibilityClass()
    {
        $slidesPerView = $this->getSlidesPerView();
        $mobileSlidesPerView = $this->getMobileSlidesPerView();
        $tabletSlidesPerView = $this->getTabletSlidesPerView();

        $itemsNUmber = $this->getItemsNumber();
        $class = [];

        if ($itemsNUmber <= $slidesPerView) {
            $class[] = 'vehica-hide-desktop';
        }

        if ($itemsNUmber <= $tabletSlidesPerView) {
            $class[] = 'vehica-hide-tablet';
        }

        if ($itemsNUmber <= $mobileSlidesPerView) {
            $class[] = 'vehica-hide-mobile';
        }

        return implode(' ', $class);
    }

}