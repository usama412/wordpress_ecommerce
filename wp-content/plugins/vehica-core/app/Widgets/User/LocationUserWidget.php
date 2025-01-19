<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class LocationUserWidget
 * @package Vehica\Widgets\User
 */
class LocationUserWidget extends UserWidget
{
    const NAME = 'vehica_location_user_widget';
    const TEMPLATE = 'user/location';
    const SHOW_LABEL = 'show_label';
    const LABEL = 'label';
    const ZOOM = 'zoom';
    const ICON = 'icon';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Location', 'vehica-core');
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
            self::SHOW_LABEL,
            [
                'label' => esc_html__('Display Label', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            self::LABEL,
            [
                'label' => esc_html__('Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    self::SHOW_LABEL => '1'
                ]
            ]
        );

        $this->add_responsive_control(
            'height',
            [
                'label' => esc_html__('Height', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 640,
                ],
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 1440,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .vehica-car__location' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            self::ZOOM,
            [
                'label' => esc_html__('Zoom', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => vehicaApp('map_zoom'),
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            self::ICON,
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @return int
     */
    public function getZoom()
    {
        $data = $this->get_settings_for_display('zoom');
        return isset($data['size']) ? (int)$data['size'] : 10;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $icon = $this->get_settings_for_display(self::ICON);

        return isset($icon['url']) ? $icon['url'] : '';
    }

    /**
     * @return bool
     */
    public function showLabel()
    {
        $show = (int)$this->get_settings_for_display(self::SHOW_LABEL);
        return !empty($show);
    }

    /**
     * @return array
     */
    public function get_script_depends()
    {
        return ['google-maps'];
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = $this->get_settings_for_display(self::LABEL);

        if (empty($label)) {
            return '';
        }

        return $label;
    }

}