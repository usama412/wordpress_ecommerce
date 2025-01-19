<?php

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class SocialShareGeneralWidget
 * @package Vehica\Widgets\General
 */
class SocialShareGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_social_share_general_widget';
    const TEMPLATE = 'general/social_share';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Social Share Buttons', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();

        $this->addStyleControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_social_share_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'vehica_show_facebook',
            [
                'label' => esc_html__('Show Facebook', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            'vehica_show_twitter',
            [
                'label' => esc_html__('Show Twitter', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->end_controls_section();
    }

    protected function addStyleControls()
    {
        $this->start_controls_section(
            self::NAME . '_style',
            [
                'tab' => Controls_Manager::TAB_STYLE,
                'label' => esc_html__('Social Share Buttons', 'vehica-core')
            ]
        );

        $this->add_responsive_control(
            'vehica_button_text_align',
            [
                'label' => esc_html__('Alignment', 'vehica-core'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'vehica-core'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'vehica-core'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'vehica-core'),
                        'icon' => 'eicon-text-align-right',
                    ]
                ],
                'default' => 'start',
                'selectors' => [
                    '{{WRAPPER}} .vehica-social-share' => 'justify-content: {{VALUE}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $this->loadTemplate();
    }

    /**
     * @return bool
     */
    public function showFacebook()
    {
        $show = $this->get_settings_for_display('vehica_show_facebook');
        return !empty($show);
    }

    /**
     * @return bool
     */
    public function showTwitter()
    {
        $show = $this->get_settings_for_display('vehica_show_twitter');
        return !empty($show);
    }

    /**
     * @return string
     */
    public function getCurrentUrl()
    {
        global $wp;
        return home_url($wp->request);
    }

}