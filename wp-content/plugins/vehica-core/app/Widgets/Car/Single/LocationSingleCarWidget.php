<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Exception;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\LocationField;

/**
 * Class LocationSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class LocationSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_location_single_car_widget';
    const TEMPLATE = 'car/single/location';
    const LOCATION_FIELD = 'location_field';
    const SHOW_LABEL = 'show_label';
    const LABEL = 'label';
    const ZOOM = 'zoom';
    const ICON = 'icon';

    /**
     * LocationSingleCarWidget constructor.
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
        return esc_html__('Listing Location', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'location_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        if (vehicaApp('location_fields')->count() === 0) {
            $this->add_control(
                'location_no_field',
                [
                    'label' => esc_html__('Create a custom field location field first.', 'vehica-core'),
                    'type' => Controls_Manager::HEADING
                ]
            );
        } elseif (vehicaApp('location_fields')->count() === 1) {
            $this->add_control(
                self::LOCATION_FIELD,
                [
                    'type' => Controls_Manager::HIDDEN,
                    'default' => vehicaApp('location_fields')->first()->getKey(),
                ]
            );
        } else {
            $locationFields = vehicaApp('location_fields_list');

            $this->add_control(
                self::LOCATION_FIELD,
                [
                    'label' => esc_html__('Location Field', 'vehica-core'),
                    'type' => Controls_Manager::SELECT2,
                    'options' => $locationFields,
                    'default' => key($locationFields),
                    'multiple' => true,
                ]
            );
        }

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

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @param Car $car
     * @return array
     */
    public function getLocations(Car $car)
    {
        $values = [];

        foreach ($this->getLocationFields() as $locationField) {
            $value = $locationField->getValue($car);

            if (!empty($value['address'])) {
                $values[] = $value;
            }
        }

        return $values;
    }

    /**
     * @return Collection|LocationField[]
     */
    private function getLocationFields()
    {
        $fieldKeys = $this->get_settings_for_display(self::LOCATION_FIELD);
        if (!is_array($fieldKeys)) {
            $fieldKeys = [$fieldKeys];
        }

        return vehicaApp('location_fields')->filter(static function ($locationField) use ($fieldKeys) {
            /* @var LocationField $locationField */
            return in_array($locationField->getKey(), $fieldKeys, true);
        });
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
        if (!empty($label)) {
            return $label;
        }

        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->getLocationFields() as $locationField) {
            return $locationField->getName();
        }

        return '';
    }

    /**
     * @return LocationField|false
     */
    public function getLocationField()
    {
        return $this->getLocationFields()->first();
    }

}