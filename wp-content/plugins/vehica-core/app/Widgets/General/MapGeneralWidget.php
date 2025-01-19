<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Exception;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class MapGeneralWidget
 * @package Vehica\Widgets\General
 */
class MapGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_map_general_widget';
    const TEMPLATE = 'general/map';

    /**
     * MapGeneralWidget constructor.
     * @param array $data
     * @param null $args
     * @throws Exception
     */
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);

        if (!empty(vehicaApp('google_maps_api_key'))) {
            wp_register_script(
                'google-maps',
                '//maps.googleapis.com/maps/api/js?key=' . vehicaApp('google_maps_api_key') . '&libraries=places&callback=mapLoaded',
                [],
                false,
                true
            );
        }
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Map', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'vehica_map',
            [
                'label' => esc_html__('Map', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'address',
            [
                'label' => esc_html__('Location', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'render_type' => 'ui',
                'default' => esc_html__('New York', 'vehica-core')
            ]
        );

        $position = vehicaApp('map_initial_position');

        $this->add_control(
            'lat',
            [
                'label' => esc_html__('Lat', 'vehica-core'),
                'type' => Controls_Manager::HIDDEN,
                'default' => isset($position['lat']) ? $position['lat'] : '40.848531'
            ]
        );

        $this->add_control(
            'lng',
            [
                'label' => esc_html__('Lng', 'vehica-core'),
                'type' => Controls_Manager::HIDDEN,
                'default' => isset($position['lng']) ? $position['lng'] : '-73.912534'
            ]
        );

        $this->add_control(
            'zoom',
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
            'icon',
            [
                'label' => esc_html__('Icon', 'vehica-core'),
                'type' => Controls_Manager::MEDIA,
            ]
        );

        $this->addMarkerTypeControl();

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
                    '{{WRAPPER}} .vehica-map' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->addInfoWindowControls();

        $this->addBorderRadiusControl('map', '.vehica-map');

        $this->end_controls_tabs();
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        $icon = $this->get_settings_for_display('icon');

        return isset($icon['url']) ? $icon['url'] : '';
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->get_settings_for_display('address');
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
     * @return double
     */
    public function getLat()
    {
        return (double)$this->get_settings_for_display('lat');
    }

    /**
     * @return double
     */
    public function getLng()
    {
        return (double)$this->get_settings_for_display('lng');
    }

    /**
     * @return array
     */
    public function get_script_depends()
    {
        return ['google-maps'];
    }

    private function addInfoWindowControls()
    {
        $this->add_control(
            'show_info_window',
            [
                'label' => esc_html__('Display Info Window', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );

        $this->add_control(
            'info_window_text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'condition' => [
                    'show_info_window' => '1',
                ]
            ]
        );

        $this->add_control(
            'info_window_allow_close',
            [
                'label' => esc_html__('Display X (close window icon)', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );

        $this->add_control(
            'hide_info_window_close',
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => 'display: none !important;',
                'condition' => [
                    'info_window_allow_close!' => '1'
                ],
                'selectors' => [
                    '{{WRAPPER}} button' => '{{VALUE}};'
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    public function showInfoWindow()
    {
        return !empty($this->get_settings_for_display('show_info_window'));
    }

    /**
     * @return string
     */
    public function getInfoWindowText()
    {
        return (string)$this->get_settings_for_display('info_window_text');
    }

    /**
     * @return bool
     */
    public function hasInfoWindowText()
    {
        return !empty($this->getInfoWindowText());
    }

    private function addMarkerTypeControl()
    {
        $this->add_control(
            'marker_type',
            [
                'label' => esc_html__('Marker Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'big' => esc_html__('Big', 'vehica-core'),
                    'small' => esc_html__('Small', 'vehica-core'),
                ],
                'default' => 'big',
            ]
        );
    }

    /**
     * @return string
     */
    public function getMarkerType()
    {
        $type = $this->get_settings_for_display('marker_type');

        if (empty($type)) {
            return 'big';
        }

        return $type;
    }

}