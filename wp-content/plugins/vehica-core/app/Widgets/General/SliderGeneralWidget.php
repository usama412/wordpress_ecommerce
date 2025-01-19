<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Core\Collection;

/**
 * Class SliderGeneralWidget
 * @package Vehica\Widgets\General
 */
class SliderGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_slider_general_widget';
    const TEMPLATE = 'general/slider';
    const SLIDES = 'slides';
    const DISPLAY_MASK = 'display_mask';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Slider', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'general_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addSlidesControl();

        $this->addDisplayMaskControl();

        $this->addHeightControl();

        $this->addContentMarginControl();

        $this->addButtonMarginControl();

        $this->addArrowsControl();

        $this->addBulletsControl();

        $this->addEffectControl();

        $this->addSpeedControl();

        $this->addDelayControl();

        $this->end_controls_section();

        $this->start_controls_section(
            'general_style',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addHeadingColorControl();

        $this->addHeadingTypographyControl();

        $this->end_controls_section();
    }

    private function addButtonMarginControl()
    {
        $this->add_responsive_control(
            'button_margin',
            [
                'label' => esc_html__('Button Margin Top', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-slider__button' => 'margin-top: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );
    }

    private function addHeadingColorControl()
    {
        $this->add_control(
            'heading_color',
            [
                'label' => esc_html__('Heading Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-slider__title' => 'color: {{VALUE}};'
                ]
            ]
        );
    }

    private function addHeadingTypographyControl()
    {
        $this->addTextTypographyControl(
            'heading',
            '.vehica-slider__title',
            [
                'label' => esc_html__('Heading Typography', 'vehica-core'),
            ]
        );
    }

    private function addBulletsControl()
    {
        $this->add_control(
            'show_bullets',
            [
                'label' => esc_html__('Display Bullets', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );
    }

    /**
     * @return bool
     */
    public function showBullets()
    {
        return !empty($this->get_settings_for_display('show_bullets'));
    }

    private function addArrowsControl()
    {
        $this->add_control(
            'show_arrows',
            [
                'label' => esc_html__('Display Arrows (Desktop/Tablet)', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );
    }

    /**
     * @return bool
     */
    public function showArrows()
    {
        return !empty($this->get_settings_for_display('show_arrows'));
    }

    /**
     * @return bool
     */
    public function displayMask()
    {
        return !empty($this->get_settings_for_display(self::DISPLAY_MASK));
    }

    private function addDisplayMaskControl()
    {
        $this->add_control(
            self::DISPLAY_MASK,
            [
                'label' => esc_html__('Display Mask', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    private function addContentMarginControl()
    {
        $this->add_responsive_control(
            'vehica_content_margin_top',
            [
                'label' => esc_html__('Content Margin Top', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-swiper-slide' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );
    }

    private function addHeightControl()
    {
        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Height', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 400,
                        'max' => 2000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-slider__content' => 'min-height: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );
    }

    /**
     * @return array
     */
    public function getSliderConfig()
    {
        return [
            'effect' => $this->getEffect(),
            'delay' => $this->getDelay(),
            'speed' => $this->getSpeed(),
            'slideCount' => count($this->getSlides()),
        ];
    }

    private function addEffectControl()
    {
        $this->add_control(
            'effect',
            [
                'label' => esc_html__('Transition Effect', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'fade',
                'options' => [
                    'slide' => esc_html__('Slide', 'vehica-core'),
                    'fade' => esc_html__('Fade', 'vehica-core'),
                    'cube' => esc_html__('Cube', 'vehica-core'),
                    'coverflow' => esc_html__('Coverflow', 'vehica-core'),
                    'flip' => esc_html__('Flip', 'vehica-core'),
                ]
            ]
        );
    }

    /**
     * @return string
     */
    private function getEffect()
    {
        $effect = $this->get_settings_for_display('effect');

        if (empty($effect)) {
            return 'fade';
        }

        return $effect;
    }

    private function addSpeedControl()
    {
        $this->add_control(
            'speed',
            [
                'label' => esc_html__('Transition Speed (ms)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 300
            ]
        );
    }

    /**
     * @return int
     */
    private function getSpeed()
    {
        $speed = (int)$this->get_settings_for_display('speed');

        if (empty($speed)) {
            return 300;
        }

        return $speed;
    }

    private function addDelayControl()
    {
        $this->add_control(
            'delay',
            [
                'label' => esc_html__('Delay (ms)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 4000,
            ]
        );
    }

    /**
     * @return int
     */
    private function getDelay()
    {
        $delay = (int)$this->get_settings_for_display('delay');

        if (empty($delay)) {
            return 4000;
        }

        return $delay;
    }

    private function addSlidesControl()
    {
        $slide = new Repeater();

        $slide->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => 'Welcome!'
            ]
        );

        $slide->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'vehica-core'),
                'type' => Controls_Manager::MEDIA
            ]
        );

        $slide->add_responsive_control(
            'image_position',
            [
                'label' => esc_html__('Image Position', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'center center' => esc_html__('Center', 'vehica-core'),
                    'left center' => esc_html__('Left', 'vehica-core'),
                    'right center' => esc_html__('Right', 'vehica-core'),
                ],
                'default' => 'center center',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-position: {{VALUE}};'
                ],
                'frontend_available' => true,
            ]
        );

        $slide->add_control(
            'show_button',
            [
                'label' => esc_html__('Display Button on Tablet and Desktop', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
                'label_block' => true,
            ]
        );

        $slide->add_control(
            'button_url',
            [
                'label' => esc_html__('Button Url', 'vehica-url'),
                'type' => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_button' => '1'
                ]
            ]
        );

        $slide->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::TEXT,
                'default' => vehicaApp('view_string'),
                'condition' => [
                    'show_button' => '1'
                ]
            ]
        );

        $this->add_control(
            'slides',
            [
                'label' => esc_html__('Slides', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $slide->get_controls(),
                'prevent_empty' => false,
                'title_field' => '',
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getSlides()
    {
        $slides = $this->get_settings_for_display('slides');

        if (!is_array($slides) || empty($slides)) {
            return Collection::make();
        }

        return Collection::make($slides)->map(static function ($slide) {
            return $slide;
        });
    }

}