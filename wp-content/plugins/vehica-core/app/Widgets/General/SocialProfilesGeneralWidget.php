<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class SocialProfilesGeneralWidget
 * @package Vehica\Widgets\General
 */
class SocialProfilesGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_social_profiles_general_widget';
    const TEMPLATE = 'general/social_profiles';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Social Profiles', 'vehica-core');
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
            'vehica_style',
            [
                'label' => esc_html__('Style', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'v1' => esc_html__('Style 1', 'vehica-core'),
                    'v2' => esc_html__('Style 2', 'vehica-core'),
                ],
                'default' => 'v1'
            ]
        );

        $this->addTextAlignControl(
            'vehica_social_profiles',
            '.vehica-social-profiles'
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    private function getStyle()
    {
        $style = (string)$this->get_settings_for_display('vehica_style');

        if (empty($style)) {
            return 'v1';
        }

        return $style;
    }

    /**
     * @return bool
     */
    public function isStyleV1()
    {
        return $this->getStyle() === 'v1';
    }

    /**
     * @return bool
     */
    public function isStyleV2()
    {
        return $this->getStyle() === 'v2';
    }

}