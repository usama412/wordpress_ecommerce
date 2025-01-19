<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class UserImageSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserImageSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_image_single_car_widget';
    const TEMPLATE = 'car/single/user_image';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Image', 'vehica-core');
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

        $this->addImageSizeControl('vehica_100_100');

        $this->addMaxWidthControl();

        $this->addHeightControl();

        $this->addAlignControl();

        $this->addBorderRadiusControl('image', '.vehica-user-image__image-radius');

        $this->end_controls_section();
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
                        'min' => 0,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-image-v2' => 'height: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'padding_top',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '0',
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-image-v2' => 'padding-top:0 !important;'
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'height[size]',
                            'operator' => '!==',
                            'value' => ''
                        ],
                        [
                            'name' => 'height[size]',
                            'operator' => '!==',
                            'value' => '0'
                        ],
                    ]
                ]
            ]
        );
    }

    private function addAlignControl()
    {
        $this->add_responsive_control(
            'vehica_align',
            [
                'label' => esc_html__('Align', 'vehica-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-image-v2__align' => 'justify-content: {{VALUE}};'
                ],
                'frontend_available' => true,
            ]
        );
    }

    private function addMaxWidthControl()
    {
        $this->add_responsive_control(
            'vehica_width',
            [
                'label' => esc_html__('Width', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-image-v2' => 'width: {{SIZE}}{{UNIT}};'
                ],
                'default' => [
                    'size' => '200',
                    'unit' => 'px'
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                    ],
                ],
                'frontend_available' => true,
            ]
        );
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }
}