<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class LogoGeneralWidget
 * @package Vehica\Widgets\General
 */
class LogoGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_logo_general_widget';
    const TEMPLATE = 'general/logo';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Logo', 'vehica-core');
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
            'vehica_type',
            [
                'label' => esc_html__('Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'vehica-core'),
                    'inverse' => esc_html__('Inverse', 'vehica-core'),
                ],
                'default' => 'default'
            ]
        );

        $this->addImageSizeControl('vehica_image_size');

        $this->addTextAlignControl(
            'vehica_logo',
            '.vehica-logo-widget'
        );

        $this->end_controls_section();
    }

    /**
     * @return string|false
     */
    public function getLogoUrl()
    {
        return wp_get_attachment_image_url(
            $this->getImageId(),
            $this->getImageSize()
        );
    }

    /**
     * @return int
     */
    private function getImageId()
    {
        if ($this->getLogoType() === 'inverse' && !empty(vehicaApp('inverse_logo_id'))) {
            return vehicaApp('inverse_logo_id');
        }

        return vehicaApp('logo_id');
    }

    /**
     * @return mixed
     */
    private function getLogoType()
    {
        return $this->get_settings_for_display('vehica_type');
    }

}