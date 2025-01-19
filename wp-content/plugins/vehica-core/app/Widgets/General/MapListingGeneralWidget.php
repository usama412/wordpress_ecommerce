<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Exception;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Components\Card\Car\Card;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\LocationField;
use Vehica\Search\Field\LocationSearchField;
use Vehica\Search\Field\SearchField;
use Vehica\Widgets\Partials\SearchResultsPartialWidget;
use WP_Error;

/**
 * Class MapListingGeneralWidget
 * @package Vehica\Widgets\General
 */
class MapListingGeneralWidget extends GeneralWidget
{
    use SearchResultsPartialWidget;

    const NAME = 'vehica_map_listing_general_widget';
    const TEMPLATE = 'general/map_listing';


    /**
     * MapListingCarArchiveWidget constructor.
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

            wp_register_script('infobox', vehicaApp('assets') . 'js/infobox.min.js', ['google-maps'], false, true);

            wp_register_script('marker-with-label', vehicaApp('assets') . 'js/markerWithLabel.min.js', ['google-maps'], false, true);

            wp_register_script('spiderfier', vehicaApp('assets') . 'js/spiderfier.min.js', ['google-maps'], false, true);
        }
    }

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Inventory Map', 'vehica-core');
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

        $this->addLocationFieldControl();

        $this->addMarkerTypeControl();

        $this->addLimitControl();

        $this->addInitialCardControl();

        $this->addShowViewSelector();

        $this->addShowCardLabelsControl();

        $this->addShowMyLocationControl();

        $this->end_controls_section();

        $this->addSearchFieldsControls();

        $this->addSortBySection();
    }

    private function addShowMyLocationControl()
    {
        $this->add_control(
            'show_my_location',
            [
                'label' => esc_html__('Display "Set My Location" button', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );
    }

    /**
     * @return bool
     */
    public function showMyLocation()
    {
        return !empty($this->get_settings_for_display('show_my_location'));
    }

    /**
     * @return array
     */
    public function get_script_depends()
    {
        if (empty(vehicaApp('google_maps_api_key'))) {
            return [];
        }

        return ['google-maps', 'infobox', 'marker-with-label', 'spiderfier'];
    }

    /**
     * @param Collection $cars
     * @param LocationField $locationField
     * @return array
     */
    public function getMarkersData(Collection $cars, LocationField $locationField)
    {
        if ($this->getMarkerType() === 'regular') {
            return $cars->map(static function ($car) use ($locationField) {
                /* @var Car $car */
                return $car->getMarkerData($locationField);
            })->all();
        }

        $attribute = $this->getContentField();
        if (!$attribute) {
            return $cars->map(static function ($car) use ($locationField) {
                /* @var Car $car */
                return $car->getMarkerData($locationField);
            })->all();
        }

        return $cars->map(static function ($car) use ($locationField, $attribute) {
            /* @var Car $car */
            return $car->getMarkerData($locationField, $attribute);
        })->all();
    }

    private function addLocationFieldControl()
    {
        $locationFields = vehicaApp('location_fields');
        $count = count($locationFields);

        if (empty($count)) {
            $this->add_control(
                'create_location_field',
                [
                    'label' => esc_html__('You have not created any Location field yet.', 'vehica-core'),
                    'type' => Controls_Manager::HEADING
                ]
            );
            return;
        }

        if ($count === 1) {
            $this->add_control(
                'location_field',
                [
                    'label' => esc_html__('Location field', 'vehica-core'),
                    'type' => Controls_Manager::HIDDEN,
                    'default' => $locationFields->first()->getKey(),
                ]
            );
            return;
        }

        $options = [];
        foreach ($locationFields as $locationField) {
            /* @var LocationField $locationField */
            $options[$locationField->getKey()] = $locationField->getName();
        }

        $this->add_control(
            'location_field',
            [
                'label' => esc_html__('Location field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => !empty($options) ? key($options) : null
            ]
        );
    }

    /**
     * @return LocationField|false
     */
    public function getLocationField()
    {
        $key = $this->get_settings_for_display('location_field');
        if (empty($key)) {
            return vehicaApp('location_fields')->first();
        }

        return vehicaApp('location_fields')->find(static function ($locationField) use ($key) {
            /* @var LocationField $locationField */
            return $locationField->getKey() === $key;
        });
    }

    private function addMarkerTypeControl()
    {
        $this->add_control(
            'marker_type',
            [
                'label' => esc_html__('Marker Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => 'regular',
                'options' => [
                    'regular' => esc_html__('Regular', 'vehica-core'),
                    'content' => esc_html__('Content', 'vehica-core'),
                ]
            ]
        );

        $fields = [];

        foreach (vehicaApp('simple_text_car_fields') as $field) {
            /* @var Field $field */
            $fields[$field->getKey()] = $field->getName();
        }

        if (vehicaApp('price_fields')->count() > 0) {
            $default = vehicaApp('price_fields')->first()->getKey();
        } else {
            $default = !empty($fields) ? key($fields) : null;
        }

        $this->add_control(
            'marker_field',
            [
                'label' => esc_html__('Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $fields,
                'default' => $default,
                'condition' => [
                    'marker_type' => 'content'
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getMarkerType()
    {
        $type = (string)$this->get_settings_for_display('marker_type');

        if (empty($type)) {
            return 'regular';
        }

        return $type;
    }

    /**
     * @return SimpleTextAttribute|false
     */
    private function getContentField()
    {
        $key = (string)$this->get_settings_for_display('marker_field');

        return vehicaApp('car_fields')->find(static function ($field) use ($key) {
            /* @var Field $field */
            return $field->getKey() === $key && $field instanceof SimpleTextAttribute;
        });
    }

    /**
     * @return bool
     */
    public function hasContentField()
    {
        return $this->getMarkerType() === 'content' && $this->getContentField() !== false;
    }

    /**
     * @return string
     */
    public function getContentFieldKey()
    {
        return (string)$this->get_settings_for_display('marker_field');
    }

    /**
     * @param LocationSearchField $locationSearchField
     * @param Collection $searchFields
     * @return bool|mixed
     */
    public function excludeLocationFieldFromFiltersCount(LocationSearchField $locationSearchField, Collection $searchFields)
    {
        return $searchFields->find(static function ($searchField) use ($locationSearchField) {
            /* @var SearchField $searchField */
            return $searchField->getKey() === $locationSearchField->getKey();
        });
    }

    protected function addInitialCardControl()
    {
        $this->add_control(
            'vehica_initial_card',
            [
                'label' => esc_html__('Initial Card Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    Card::TYPE_V2 => esc_html__('Card', 'vehica-core'),
                    Card::TYPE_V5 => esc_html__('Row', 'vehica-core'),
                ],
                'default' => Card::TYPE_V5
            ]
        );
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if (is_post_type_archive(Car::POST_TYPE)) {
            return vehicaApp('car_archive_url');
        }

        global $post;
        $baseUrl = get_permalink($post);

        if (!$baseUrl || $baseUrl instanceof WP_Error) {
            return vehicaApp('car_archive_url');
        }

        return $baseUrl;
    }

    protected function render()
    {
        $this->additionalParams['base_url'] = $this->getBaseUrl();

        parent::render();
    }

}