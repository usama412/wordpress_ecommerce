<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class SocialUserWidget
 * @package Vehica\Widgets\User
 */
class SocialUserWidget extends UserWidget
{
    const NAME = 'vehica_social_user_widget';
    const TEMPLATE = 'user/social';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Socials', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'user_socials_style',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'icons_align',
            [
                'label' => esc_html__('Align', 'vehica-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'vehica-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vehica-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'vehica-core'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'vehica-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'right',
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-icons-wrapper.vehica-social-icons-wrapper--profile' => 'text-align: {{VALUE}};',
                ],
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'color',
            [
                'label' => esc_html__('Icon Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-icon a' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => esc_html__('Background Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-icon a' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__('Icon Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-icon a:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'background_color_hover',
            [
                'label' => esc_html__('Background Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-icon a:hover' => 'background-color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}