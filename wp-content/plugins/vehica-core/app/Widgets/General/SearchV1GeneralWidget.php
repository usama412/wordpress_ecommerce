<?php /** @noinspection DuplicatedCode */

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Api\CarsApi;
use Vehica\Core\Collection;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\DateTimeField;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\LocationField;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Field\TextField;
use Vehica\Model\Post\Page;
use Vehica\Model\Term\Term;
use Vehica\Search\Field\DateSearchField;
use Vehica\Search\Field\LocationSearchField;
use Vehica\Search\Field\NumberSearchField;
use Vehica\Search\Field\PriceSearchField;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Field\TaxonomySearchField;
use Vehica\Search\Field\TextSearchField;
use Vehica\Search\Searchable;
use Vehica\Search\SearchControl;
use Vehica\Search\SearchFilter;
use Vehica\Widgets\Controls\SelectRemoteControl;
use WP_Error;
use WP_Term;

/**
 * Class SearchV1GeneralWidget
 * @package Vehica\Widgets\General
 */
class SearchV1GeneralWidget extends GeneralWidget
{
    /**
     * @var bool
     */
    protected $results;

    const NAME = 'vehica_search_general_widget';
    const TEMPLATE = 'general/search/search_v1';
    const SEARCH_BUTTON_TEXT = 'vehica_search_button_text';
    const MAIN_FIELDS = 'vehica_main_fields';
    const SHOW_CARS_NUMBER = 'vehica_show_cars_number';
    const SEARCH_FORM_STYLE = 'vehica_search_form_style';
    const MAIN_FIELD = 'vehica_search_main_field';
    const MAIN_FIELD_TERMS = 'vehica_search_main_field_terms';
    const MAIN_FIELD_SHOW_ALL_OPTION = 'vehica_search_main_field_show_all_option';
    const MAIN_FIELD_ALL_OPTION_TEXT = 'vehica_search_main_field_all_option_text';
    const MAIN_FIELD_INITIAL_VALUE = 'vehica_search_main_field_initial_value';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Search Form V1', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_search_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            self::SEARCH_BUTTON_TEXT,
            [
                'label' => esc_html__('Search Button Text', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('search_string'),
            ]
        );

        $this->add_control(
            self::SHOW_CARS_NUMBER,
            [
                'label' => esc_html__('Show Number of Cars', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );

        $this->addResultsPageControl();

        $this->end_controls_section();

        $this->start_controls_section(
            'vehica_search_fields',
            [
                'label' => esc_html__('Search Fields', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addMainFieldsControls();

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return (string)$this->get_settings_for_display(self::SEARCH_FORM_STYLE);
    }

    /**
     * @return bool
     */
    public function showCarsNumber()
    {
        $showCarsNumber = (int)$this->get_settings_for_display(self::SHOW_CARS_NUMBER);
        return !empty($showCarsNumber);
    }

    /**
     * @return string
     */
    public function getAdvancedSearchLink()
    {
        return get_post_type_archive_link(Car::POST_TYPE);
    }

    /**
     * @return string
     */
    public function getAdvancedSearchLinkText()
    {
        return vehicaApp('advanced_search_link_string');
    }

    /**
     * @return string
     */
    public function getSearchButtonText()
    {
        $searchButtonText = (string)$this->get_settings_for_display(self::SEARCH_BUTTON_TEXT);

        if (empty($searchButtonText)) {
            return vehicaApp('search_string');
        }

        return $searchButtonText;
    }

    /**
     * @return array
     */
    private function getSearchFieldsList()
    {
        $searchFieldsList = [];

        vehicaApp('search_fields')->each(static function ($searchField) use (&$searchFieldsList) {
            /* @var SearchField $searchField */
            $searchFieldsList[$searchField->getKey()] = $searchField->getName();
        });

        return $searchFieldsList;
    }

    protected function addMainFieldsControls()
    {
        $taxonomiesList = [
            'not_set' => esc_html__('Not Set', 'vehica-core'),
        ];

        vehicaApp('taxonomies')->each(static function ($taxonomy) use (&$taxonomiesList) {
            /* @var Taxonomy $taxonomy */
            $taxonomiesList[$taxonomy->getKey()] = $taxonomy->getName();
        });

        $this->add_control(
            self::MAIN_FIELD,
            [
                'label' => esc_html__('Main Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $taxonomiesList,
                'default' => 'not_set',
            ]
        );

        $this->add_control(
            self::MAIN_FIELD_SHOW_ALL_OPTION,
            [
                'label' => esc_html__('Show "All" option', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
            ]
        );

        $this->add_control(
            self::MAIN_FIELD_ALL_OPTION_TEXT,
            [
                'label' => esc_html__('"All" option text', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('all_string'),
                'condition' => [
                    self::MAIN_FIELD . '!' => 'not_set',
                    self::MAIN_FIELD_SHOW_ALL_OPTION => '1',
                ]
            ]
        );

        vehicaApp('taxonomies')->each(function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            $options = new Repeater();
            $options->add_control(
                'id',
                [
                    'label' => esc_html__('Term', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint()
                ]
            );

            $this->add_control(
                self::MAIN_FIELD_TERMS . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Options', 'vehica-core'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $options->get_controls(),
                    'prevent_empty' => false,
                    'condition' => [
                        self::MAIN_FIELD => $taxonomy->getKey(),
                    ]
                ]
            );

            $this->add_control(
                self::MAIN_FIELD_INITIAL_VALUE . '_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Initial Value', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                    'multiple' => false,
                    'condition' => [
                        self::MAIN_FIELD => $taxonomy->getKey(),
                    ]
                ]
            );
        });

        $fields = new Repeater();
        $searchFieldsList = $this->getSearchFieldsList();

        $fields->add_control(
            'search_field',
            [
                'label' => esc_html__('Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $searchFieldsList,
                'default' => count($searchFieldsList) ? key($searchFieldsList) : ''
            ]
        );

        $this->addNumberFields($fields);

        $this->addPriceFields($fields);

        $this->addTextFields($fields);

        $this->addLocationFields($fields);

        $this->addDateFields($fields);

        $this->addTaxonomyFields($fields);

        $this->add_control(
            self::MAIN_FIELDS,
            [
                'type' => Controls_Manager::REPEATER,
                'label' => esc_html__('Fields', 'vehica-core'),
                'fields' => $fields->get_controls(),
                'prevent_empty' => false,
                'default' => $this->getDefaultFields(),
                'title_field' => '{{{ vehicaSetFieldTitle(search_field) }}}',
            ]
        );
    }

    /**
     * @param Repeater $fields
     * @noinspection DuplicatedCode
     */
    protected function addDateFields(Repeater $fields)
    {
        $dateFieldKeys = vehicaApp('date_time_fields')->map(static function ($dateField) {
            /* @var DateTimeField $dateField */
            return $dateField->getKey();
        })->all();

        $fields->add_control(
            DateSearchField::PLACEHOLDER_FROM,
            [
                'label' => esc_html__('Placeholder From', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    SearchField::FIELD => $dateFieldKeys
                ]
            ]
        );

        $fields->add_control(
            DateSearchField::PLACEHOLDER_TO,
            [
                'label' => esc_html__('Placeholder To', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    SearchField::FIELD => $dateFieldKeys
                ]
            ]
        );
    }

    /**
     * @param Repeater $fields
     */
    protected function addLocationFields(Repeater $fields)
    {
        $locationFieldKeys = vehicaApp('location_fields')->map(static function ($locationField) {
            /* @var LocationField $locationField */
            return $locationField->getKey();
        })->all();

        $fields->add_control(
            LocationSearchField::PLACEHOLDER,
            [
                'label' => esc_html__('Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::ASK_FOR_LOCATION,
            [
                'label' => esc_html__('Automatically ask for current user location', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::SHOW_MY_LOCATION_BUTTON,
            [
                'label' => esc_html__('Display "My Location" button', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::SHOW_RADIUS,
            [
                'label' => esc_html__('Display Radius Control', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::RADIUS_PLACEHOLDER,
            [
                'label' => esc_html__('Radius Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys,
                    LocationSearchField::SHOW_RADIUS => '1'
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::RADIUS_UNITS,
            [
                'label' => esc_html__('Radius Unit', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'miles' => esc_html__('miles', 'vehica-core'),
                    'km' => esc_html__('km', 'vehica-core'),
                ],
                'default' => 'miles',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys,
                    LocationSearchField::SHOW_RADIUS => '1'
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::RADIUS_VALUES,
            [
                'label' => esc_html__('Radius Values', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '10,20,30,50,75,100,200,500',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys,
                    LocationSearchField::SHOW_RADIUS => '1'
                ]
            ]
        );

        $fields->add_control(
            LocationSearchField::DEFAULT_RADIUS,
            [
                'label' => esc_html__('Initial Radius Value', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'description' => __('Initial Radius Value works only if you add value that is available in "Radius Values" field (above)', 'vehica-core'),
                'default' => '30',
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys,
                    LocationSearchField::SHOW_RADIUS => '1'
                ]
            ]
        );
    }

    /**
     * @return array
     */
    protected function getDefaultFields()
    {
        $taxonomiesCount = 0;
        $priceFieldCount = 0;

        return vehicaApp('search_fields')->map(static function ($field) use (&$taxonomiesCount, &$priceFieldCount) {
            if (!$field instanceof PriceField && !$field instanceof Taxonomy) {
                return false;
            }

            if ($field instanceof Taxonomy) {
                if ($taxonomiesCount > 1) {
                    return false;
                }

                $taxonomiesCount++;
            } else {
                if ($priceFieldCount > 0) {
                    return false;
                }

                $priceFieldCount++;
            }

            return [
                SearchField::FIELD => $field->getKey()
            ];
        })->filter(static function ($field) {
            return $field !== false;
        })->all();
    }

    /**
     * @param Repeater $fields
     */
    protected function addPriceFields(Repeater $fields)
    {
        $priceFieldsKeys = vehicaApp('search_fields')->filter(static function ($searchField) {
            return $searchField instanceof PriceField;
        })->map(static function ($priceField) {
            /* @var PriceField $priceField */
            return $priceField->getKey();
        })->all();

        $fields->add_control(
            PriceSearchField::CONTROL,
            [
                'label' => esc_html__('Control', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    SearchControl::TYPE_INPUT_FROM_TO => esc_html__('Text from/to', 'vehica-core'),
                    SearchControl::TYPE_SELECT_FROM_TO => esc_html__('Select from/to', 'vehica-core'),
                    SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
                ],
                'default' => PriceSearchField::getDefaultControl(),
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys
                ]
            ]
        );

        $fields->add_control(
            PriceSearchField::PLACEHOLDER,
            [
                'label' => esc_html__('Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('any_string'),
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys,
                    PriceSearchField::CONTROL => [
                        SearchControl::TYPE_RADIO,
                        SearchControl::TYPE_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            PriceSearchField::PLACEHOLDER_FROM,
            [
                'label' => esc_html__('Placeholder From', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('min_string'),
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys,
                    PriceSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO,
                    ]
                ]
            ]
        );

        $fields->add_control(
            PriceSearchField::PLACEHOLDER_TO,
            [
                'label' => esc_html__('Placeholder To', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('max_string'),
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys,
                    PriceSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO,
                    ]
                ]
            ]
        );

        $fields->add_control(
            PriceSearchField::COMPARE_VALUE_TYPE,
            [
                'label' => esc_html__('Values type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    PriceSearchField::GREATER_THAN => esc_html__('Greater than', 'vehica-core'),
                    PriceSearchField::LESS_THAN => esc_html__('Less than', 'vehica-core')
                ],
                'default' => PriceSearchField::GREATER_THAN,
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys,
                    PriceSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                    ]
                ]
            ]
        );

        vehicaApp('currencies')->each(static function ($currency) use ($fields, $priceFieldsKeys) {
            /* @var Currency $currency */

            $fields->add_control(
                'price_default_value_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Default Value (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'price_default_value_from_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Default Value Min (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT_FROM_TO,
                            SearchControl::TYPE_INPUT_FROM_TO
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'price_default_value_to_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Default Value Max (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT_FROM_TO,
                            SearchControl::TYPE_INPUT_FROM_TO
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'number_values_from_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Values from (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT_FROM_TO
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'number_values_to_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Values to (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT_FROM_TO
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'number_values_' . $currency->getKey(),
                [
                    'label' => sprintf(esc_html__('Values (%s)', 'vehica-core'), $currency->getSign()),
                    'type' => Controls_Manager::TEXT,
                    'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                    'condition' => [
                        SearchField::FIELD => $priceFieldsKeys,
                        PriceSearchField::CONTROL => [
                            SearchControl::TYPE_SELECT,
                            SearchControl::TYPE_MULTI_SELECT,
                        ]
                    ]
                ]
            );
        });
    }

    /**
     * @param Repeater $fields
     */
    protected function addNumberFields(Repeater $fields)
    {
        $numberFieldKeys = vehicaApp('search_fields')->filter(static function ($searchField) {
            return $searchField instanceof NumberField;
        })->map(static function ($numberField) {
            /* @var NumberField $numberField */
            return $numberField->getKey();
        })->all();

        $numberFieldWithoutPriceKeys = vehicaApp('search_fields')->filter(static function ($searchField) {
            return $searchField instanceof NumberField && !$searchField instanceof PriceField;
        })->map(static function ($numberField) {
            /* @var NumberField $numberField */
            return $numberField->getKey();
        })->all();

        $fields->add_control(
            NumberSearchField::CONTROL,
            [
                'label' => esc_html__('Control', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    SearchControl::TYPE_INPUT_FROM_TO => esc_html__('Text from/to', 'vehica-core'),
                    SearchControl::TYPE_SELECT_FROM_TO => esc_html__('Select from/to', 'vehica-core'),
                    SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
                ],
                'default' => NumberSearchField::getDefaultControl(),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys
                ]
            ]
        );

        $fields->add_control(
            'vehica_number_default_value',
            [
                'label' => esc_html__('Default Value', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            'vehica_number_default_values',
            [
                'label' => esc_html__('Default Values', 'vehica-core'),
                'placeholder' => esc_html__('100,200,300', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_MULTI_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            'vehica_number_default_value_from',
            [
                'label' => esc_html__('Default Value (Min)', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO
                    ]
                ]
            ]
        );

        $fields->add_control(
            'vehica_number_default_value_to',
            [
                'label' => esc_html__('Default Value (Max)', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::PLACEHOLDER,
            [
                'label' => esc_html__('Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('any_string'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_RADIO,
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_MULTI_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::COMPARE_VALUE_TYPE,
            [
                'label' => esc_html__('Values type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    NumberSearchField::EQUAL => esc_html__('Equal', 'vehica-core'),
                    NumberSearchField::GREATER_THAN => esc_html__('Greater than', 'vehica-core'),
                    NumberSearchField::LESS_THAN => esc_html__('Less than', 'vehica-core')
                ],
                'default' => NumberSearchField::EQUAL,
                'condition' => [
                    SearchField::FIELD => $numberFieldKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::NUMBER_VALUES,
            [
                'label' => esc_html__('Values', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_MULTI_SELECT,
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::ADD_GREATER_THAN,
            [
                'label' => esc_html__('Add greater than value', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => SearchField::FIELD,
                            'operator' => 'in',
                            'value' => $numberFieldWithoutPriceKeys
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => NumberSearchField::CONTROL,
                                    'operator' => 'in',
                                    'value' => [
                                        SearchControl::TYPE_CHECKBOX,
                                        SearchControl::TYPE_MULTI_SELECT,
                                    ],
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => NumberSearchField::CONTROL,
                                            'operator' => 'in',
                                            'value' => [
                                                SearchControl::TYPE_RADIO,
                                                SearchControl::TYPE_SELECT,
                                            ]
                                        ],
                                        [
                                            'name' => NumberSearchField::COMPARE_VALUE_TYPE,
                                            'operator' => '!=',
                                            'value' => NumberSearchField::GREATER_THAN
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::NUMBER_VALUES_FROM,
            [
                'label' => esc_html__('Values from', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT_FROM_TO
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::NUMBER_VALUES_TO,
            [
                'label' => esc_html__('Values to', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Separated by commas (e.g. 1000,2000,5000)', 'vehica-core'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT_FROM_TO
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::PLACEHOLDER_FROM,
            [
                'label' => esc_html__('Placeholder From', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('min_string'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO,
                    ]
                ]
            ]
        );

        $fields->add_control(
            NumberSearchField::PLACEHOLDER_TO,
            [
                'label' => esc_html__('Placeholder To', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('max_string'),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_INPUT_FROM_TO,
                        SearchControl::TYPE_SELECT_FROM_TO,
                    ]
                ]
            ]
        );
    }

    /**
     * @param Repeater $fields
     */
    protected function addTextFields(Repeater $fields)
    {
        $textFieldKeys = vehicaApp('search_fields')->filter(static function ($searchField) {
            return $searchField instanceof TextField;
        })->map(static function ($textField) {
            /* @var TextField $textField */
            return $textField->getKey();
        })->all();

        $fields->add_control(
            TextSearchField::PLACEHOLDER,
            [
                'label' => esc_html__('Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'condition' => [
                    SearchField::FIELD => $textFieldKeys
                ]
            ]
        );

        $fields->add_control(
            'vehica_text_default_value',
            [
                'label' => esc_html__('Default Value', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    SearchField::FIELD => $textFieldKeys
                ]
            ]
        );
    }

    /**
     * @param Repeater $fields
     */
    protected function addTaxonomyFields(Repeater $fields)
    {
        $taxonomyKeys = vehicaApp('taxonomies_keys');
        $taxonomyKeysWithParents = vehicaApp('taxonomies')->filter(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->hasParentTaxonomy();
        })->map(static function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey();
        })->all();

        $fields->add_control(
            TaxonomySearchField::CONTROL,
            [
                'label' => esc_html__('Control', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => SearchControl::TYPE_SELECT,
                'options' => [
                    SearchControl::TYPE_SELECT => esc_html__('Select', 'vehica-core'),
                    SearchControl::TYPE_MULTI_SELECT => esc_html__('Select Multiple', 'vehica-core'),
                    SearchControl::TYPE_CHECKBOX => esc_html__('Popup Checkbox', 'vehica-core'),
                ],
                'condition' => [
                    TaxonomySearchField::FIELD => $taxonomyKeys
                ]
            ]
        );

        $fields->add_control(
            TaxonomySearchField::SEARCHABLE,
            [
                'label' => esc_html__('Searchable', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
                'condition' => [
                    TaxonomySearchField::FIELD => $taxonomyKeys,
                    TaxonomySearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_MULTI_SELECT,
                    ]
                ]
            ]
        );

        vehicaApp('taxonomies')->each(static function ($taxonomy) use ($fields) {
            /* @var Taxonomy $taxonomy */
            $fields->add_control(
                'vehica_default_value_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Default Value', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                    'multiple' => false,
                    'placeholder' => esc_html__('Select Default Value', 'vehica-core'),
                    'condition' => [
                        TaxonomySearchField::FIELD => $taxonomy->getKey(),
                        TaxonomySearchField::CONTROL => [
                            SearchControl::TYPE_SELECT,
                        ]
                    ]
                ]
            );

            $fields->add_control(
                'vehica_default_values_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Default Values', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                    'multiple' => true,
                    'placeholder' => esc_html__('Select Default Values', 'vehica-core'),
                    'condition' => [
                        TaxonomySearchField::FIELD => $taxonomy->getKey(),
                        TaxonomySearchField::CONTROL => [
                            SearchControl::TYPE_MULTI_SELECT,
                            SearchControl::TYPE_CHECKBOX,
                        ]
                    ]
                ]
            );
        });

        $fields->add_control(
            TaxonomySearchField::SHOW_TERMS_COUNT,
            [
                'label' => esc_html__('Show count', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1',
                'condition' => [
                    TaxonomySearchField::FIELD => $taxonomyKeys
                ]
            ]
        );

        $fields->add_control(
            TaxonomySearchField::PLACEHOLDER,
            [
                'label' => esc_html__('Placeholder', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('any_string'),
                'condition' => [
                    TaxonomySearchField::FIELD => $taxonomyKeys,
                    TaxonomySearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_MULTI_SELECT,
                        SearchControl::TYPE_CHECKBOX,
                    ]
                ]
            ]
        );

        $fields->add_control(
            TaxonomySearchField::WHEN_TERM_HAS_NO_CARS,
            [
                'label' => esc_html__('When term has no cars', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    TaxonomySearchField::DISABLE_TERM => esc_html__('Disable', 'vehica-core'),
                    TaxonomySearchField::HIDE_TERM => esc_html__('Hide', 'vehica-core'),
                ],
                'default' => TaxonomySearchField::DISABLE_TERM,
                'condition' => [
                    SearchField::FIELD => $taxonomyKeys,
                    TaxonomySearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_MULTI_SELECT,
                        SearchControl::TYPE_CHECKBOX,
                        SearchControl::TYPE_RADIO
                    ]
                ]
            ]
        );

        $fields->add_control(
            TaxonomySearchField::TERMS_ORDER,
            [
                'label' => esc_html__('Terms order', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    TaxonomySearchField::TERMS_ORDER_NAME => esc_html__('Name', 'vehica-core'),
                    TaxonomySearchField::TERMS_ORDER_COUNT => esc_html__('Count', 'vehica-core'),
                    TaxonomySearchField::TERMS_ORDER_DISABLE => esc_html__('Advanced: Terms IDs', 'vehica-core'),
                ],
                'default' => TaxonomySearchField::TERMS_ORDER_NAME,
                'condition' => [
                    SearchField::FIELD => $taxonomyKeys
                ]
            ]
        );

        vehicaApp('taxonomies')->each(static function ($taxonomy) use (&$fields) {
            /* @var Taxonomy $taxonomy */
            $fields->add_control(
                'terms_in_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Terms', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'multiple' => true,
                    'separator' => 'after',
                    'source' => $taxonomy->getApiEndpoint(),
                    'condition' => [
                        SearchField::FIELD => $taxonomy->getKey()
                    ]
                ]
            );

            $fields->add_control(
                'terms_in_ids_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Advanced: Terms IDs', 'vehica-core'),
                    'sub_heading' => esc_html__('Advanced: Terms IDs', 'vehica-core'),
                    'label_block' => true,
                    'separator' => 'before',
                    'description' => 'If you need custom order of dropdown options you can to it by typing Terms IDs and selecting Terms Order: Advanced: Terms IDs above. Read <a target="_blank" href="https://support.vehica.com/support/solutions/articles/101000377067">this article</a> where to find term ID.',
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => 'e.g. 2325, 2320, 2338',
                    'condition' => [
                        SearchField::FIELD => $taxonomy->getKey()
                    ]
                ]
            );
        });
    }

    /**
     * @return Collection
     */
    public function getSearchFields()
    {
        $searchFieldsData = $this->get_settings_for_display(self::MAIN_FIELDS);
        if (empty($searchFieldsData) || !is_array($searchFieldsData)) {
            return Collection::make();
        }

        return Collection::make($searchFieldsData)->map(static function ($searchFieldData) {
            $key = $searchFieldData[SearchField::FIELD];

            $searchable = vehicaApp('car_fields')->find(static function ($field) use ($key) {
                /* @var Field $field */
                return $field->getKey() === $key;
            });

            if (!$searchable instanceof Searchable) {
                return false;
            }

            return $searchable->getSearchField($searchFieldData);
        })->filter(static function ($field) {
            return $field !== false;
        });
    }

    /**
     * @return array
     */
    public function getTermsCount()
    {
        if ($this->results === null) {
            $this->prepareResultsData();
        }

        return $this->results['terms'];
    }

    /**
     * @return int
     */
    public function getResultsCount()
    {
        if ($this->results === null) {
            $this->prepareResultsData();
        }

        return $this->results['resultsCount'];
    }

    /**
     * @return array
     */
    public function getInitialFilters()
    {
        $filters = [];
        $params = $this->getDefaultParams();

        vehicaApp('search_filters')->each(static function ($searchFilter) use ($params, &$filters) {
            /* @var SearchFilter $searchFilter */
            $currentFilters = $searchFilter->getInitialSearchParams($params);
            if ($currentFilters) {
                $filters = array_merge($filters, $currentFilters);
            }
        });

        return $filters;
    }

    /**
     * @return array
     */
    protected function getInitialMainFieldParam()
    {
        if (!$this->hasMainSearchField()) {
            return [];
        }

        $mainField = $this->getMainField();
        $key = self::MAIN_FIELD_INITIAL_VALUE . '_' . $mainField->getKey();
        $termId = (int)$this->get_settings_for_display($key);
        if (empty($termId)) {
            return [];
        }

        $term = Term::getById($termId);
        if (!$term) {
            return [];
        }

        return [$mainField->getRewrite() => $term->getSlug()];
    }

    protected function getDefaultParams()
    {
        $params = [];
        $searchFieldsData = $this->get_settings_for_display(self::MAIN_FIELDS);
        if (empty($searchFieldsData) || !is_array($searchFieldsData)) {
            return Collection::make();
        }

        Collection::make($searchFieldsData)->each(function ($searchFieldData) use (&$params) {
            $key = $searchFieldData[SearchField::FIELD];
            $field = vehicaApp('car_fields')->find(static function ($field) use ($key) {
                /* @var Field $field */
                return $field->getKey() === $key;
            });

            if ($field instanceof Taxonomy) {
                $params = array_merge($params, $this->getTaxonomyDefaultParams($field, $searchFieldData));
            } elseif ($field instanceof NumberField && !$field instanceof PriceField) {
                $params = array_merge($params, $this->getNumberFieldDefaultParams($field, $searchFieldData));
            } elseif ($field instanceof PriceField) {
                $params = array_merge($params, $this->getPriceFieldDefaultParams($field, $searchFieldData));
            } elseif ($field instanceof TextField) {
                $params = array_merge($params, $this->getTextFieldDefaultParams($field, $searchFieldData));
            }
        });

        $params = array_merge($params, $this->getInitialMainFieldParam());

        return $params;
    }

    /**
     * @param PriceField $priceField
     * @param array $searchFieldData
     * @return array
     */
    protected function getPriceFieldDefaultParams(PriceField $priceField, array $searchFieldData)
    {
        $currency = vehicaApp('current_currency');
        if (!$currency instanceof Currency) {
            return [];
        }

        $searchField = $priceField->getSearchField($searchFieldData);
        if ($searchField->isSelectControl()) {
            $key = 'price_default_value_' . $currency->getKey();
            $value = $priceField->toNumberType($searchFieldData[$key]);
            if (empty($value)) {
                return [];
            }

            if ($searchField->isCompareValueGreaterThan()) {
                $rewrite = $priceField->getRewriteFrom();
            } else {
                $rewrite = $priceField->getRewriteTo();
            }

            return [$rewrite => $value];
        }

        if ($searchField->isSelectFromToControl() || $searchField->isTextFromToControl()) {
            $values = [];
            $keyFrom = 'price_default_value_from_' . $currency->getKey();
            $keyTo = 'price_default_value_to_' . $currency->getKey();
            $valueFrom = $priceField->toNumberType($searchFieldData[$keyFrom]);
            $valueTo = $priceField->toNumberType($searchFieldData[$keyTo]);

            if (!empty($valueFrom)) {
                $values[$priceField->getRewriteFrom()] = $valueFrom;
            }

            if (!empty($valueTo)) {
                $values[$priceField->getRewriteTo()] = $valueTo;
            }

            return $values;
        }

        return [];
    }

    /**
     * @param NumberField $numberField
     * @param array $searchFieldData
     * @return array
     */
    protected function getNumberFieldDefaultParams(NumberField $numberField, array $searchFieldData)
    {
        $searchField = $numberField->getSearchField($searchFieldData);
        if ($searchField->isSelectControl()) {
            $isGreaterThan = false;
            $value = $searchFieldData['vehica_number_default_value'];
            if (strpos($value, '+') !== false) {
                $isGreaterThan = true;
            }
            $value = $numberField->toNumberType($value);
            if (empty($value)) {
                return [];
            }

            if ($isGreaterThan || $searchField->isCompareValueGreaterThan()) {
                $rewrite = $numberField->getRewriteFrom();
            } elseif ($searchField->isCompareValueLessThan()) {
                $rewrite = $numberField->getRewriteTo();
            } else {
                $rewrite = $numberField->getRewrite();
            }

            return [$rewrite => $value];
        }

        if ($searchField->isSelectFromToControl() || $searchField->isTextFromToControl()) {
            $values = [];
            $valueFrom = $numberField->toNumberType($searchFieldData['vehica_number_default_value_from']);
            $valueTo = $numberField->toNumberType($searchFieldData['vehica_number_default_value_to']);
            if (!empty($valueFrom)) {
                $values[$numberField->getRewriteFrom()] = $valueFrom;
            }

            if (!empty($valueTo)) {
                $values[$numberField->getRewriteTo()] = $valueTo;
            }

            return $values;
        }

        if ($searchField->isSelectMultiControl()) {
            $allValues = Collection::make(explode(',', $searchFieldData['vehica_number_default_values']));
            $values = $allValues->map(static function ($value) use ($numberField) {
                if (strpos($value, '+') !== false) {
                    return false;
                }
                return $numberField->toNumberType($value);
            })->filter(static function ($value) {
                return !empty($value);
            })->all();

            $finalValues = [];

            if (!empty($values)) {
                $finalValues[$numberField->getRewrite()] = $values;
            }

            $valueFrom = $allValues->find(static function ($value) {
                return strpos($value, '+') !== false;
            });

            if ($valueFrom) {
                $valueFrom = $numberField->toNumberType($valueFrom);
                $finalValues[$numberField->getRewriteFrom()] = $valueFrom;
            }

            return $finalValues;
        }

        return [];
    }

    /**
     * @param Taxonomy $taxonomy
     * @param array $searchFieldData
     * @return array
     */
    protected function getTaxonomyDefaultParams(Taxonomy $taxonomy, array $searchFieldData)
    {
        $searchField = $taxonomy->getSearchField($searchFieldData);
        if ($searchField->isSelectControl()) {
            $termId = (int)$searchFieldData['vehica_default_value_' . $taxonomy->getKey()];
            if (empty($termId)) {
                return [];
            }

            $term = get_term($termId, $taxonomy->getKey());
            if (!$term instanceof WP_Term) {
                return [];
            }

            return [$taxonomy->getRewrite() => $term->slug];
        }

        $termIds = $searchFieldData['vehica_default_values_' . $taxonomy->getKey()];
        if (!is_array($termIds) || empty($termIds)) {
            return [];
        }

        $terms = get_terms([
            'taxonomy' => $taxonomy->getKey(),
            'hide_empty' => false,
            'include' => $termIds
        ]);

        if (!is_array($terms)) {
            return [];
        }

        return [$taxonomy->getRewrite() => Collection::make($terms)->map(static function ($term) {
            /* @var WP_Term $term */
            return $term->slug;
        })->all()];
    }

    /**
     * @param TextField $textField
     * @param array $searchFieldData
     * @return array
     */
    protected function getTextFieldDefaultParams(TextField $textField, array $searchFieldData)
    {
        $value = trim($searchFieldData['vehica_text_default_value']);

        if (empty($value)) {
            return [];
        }

        return [$textField->getRewrite() => $value];
    }

    protected function prepareResultsData()
    {
        $params = $this->getDefaultParams();
        $api = new CarsApi($params);
        $api->setTaxonomiesTermsCount($this->getTaxonomiesTermsCount());
        $api->disableCars();

        $this->results = $api->getResults();
    }

    /**
     * @return Taxonomy|false
     */
    protected function getMainField()
    {
        $fieldKey = $this->get_settings_for_display(self::MAIN_FIELD);
        return vehicaApp('taxonomy_' . $fieldKey);
    }

    /**
     * @return Collection
     */
    protected function getTaxonomiesTermsCount()
    {
        $taxonomyIds = $this->getTaxonomyTermsCountIds();
        return vehicaApp('taxonomies')->filter(static function ($taxonomy) use ($taxonomyIds) {
            /* @var Taxonomy $taxonomy */
            return in_array($taxonomy->getId(), $taxonomyIds, true);
        });
    }

    /**
     * @return array
     */
    public function getTaxonomyTermsCountIds()
    {
        $taxonomyIds = [];

        if ($this->hasMainSearchField()) {
            $mainField = $this->getMainField();
            if ($mainField) {
                $taxonomyIds[] = $mainField->getId();
            }
        }

        $searchFieldsData = $this->get_settings_for_display(self::MAIN_FIELDS);
        if (empty($searchFieldsData) || !is_array($searchFieldsData)) {
            return $taxonomyIds;
        }

        Collection::make($searchFieldsData)->each(static function ($searchFieldData) use (&$taxonomyIds) {
            $key = $searchFieldData[SearchField::FIELD];

            $taxonomy = vehicaApp('car_fields')->find(static function ($field) use ($key) {
                /* @var Field $field */
                return $field->getKey() === $key && $field instanceof Taxonomy;
            });

            if ($taxonomy instanceof Taxonomy) {
                $taxonomyIds[] = $taxonomy->getId();
            }
        });

        return $taxonomyIds;
    }

    /**
     * @return TaxonomySearchField|false
     */
    public function getMainSearchField()
    {
        $mainField = $this->getMainField();
        if (!$mainField instanceof Taxonomy) {
            return false;
        }

        return $mainField->getSearchField([
            TaxonomySearchField::CONTROL => SearchControl::TYPE_RADIO
        ]);
    }

    /**
     * @return array
     */
    public function getMainFieldTerms()
    {
        $mainField = $this->getMainField();
        if (!$mainField) {
            return [];
        }

        $key = self::MAIN_FIELD_TERMS . $mainField->getKey();
        $terms = $this->get_settings_for_display($key);

        if (!is_array($terms) || empty($terms)) {
            return $mainField->getTerms([
                'number' => 2
            ])->all();
        }

        return Collection::make($terms)->map(static function ($term) {
            return Term::getById($term['id']);
        })->filter(static function ($term) {
            return $term !== false;
        })->all();
    }

    /**
     * @return bool
     */
    public function showMainFieldAllOption()
    {
        $showAllOption = (int)$this->get_settings_for_display(self::MAIN_FIELD_SHOW_ALL_OPTION);
        return !empty($showAllOption);
    }

    /**
     * @return string
     */
    public function getMainFieldAllOptionText()
    {
        $allText = (string)$this->get_settings_for_display(self::MAIN_FIELD_ALL_OPTION_TEXT);

        if (empty($allText)) {
            return vehicaApp('all_string');
        }

        return $allText;
    }

    /**
     * @return bool
     */
    public function hasMainSearchField()
    {
        return $this->getMainSearchField() !== false;
    }

    /**
     * @return array|string[]
     */
    public function get_script_depends()
    {
        if (empty(vehicaApp('google_maps_api_key'))) {
            return [];
        }

        return ['google-maps'];
    }

    protected function addResultsPageControl()
    {
        $this->add_control(
            'results_page',
            [
                'label' => esc_html__('Results Page', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Page::getApiEndpoint(),
                'placeholder' => esc_html__('Listing Archive', 'vehica-core'),
            ]
        );
    }

    /**
     * @return string
     */
    public function getResultsPageUrl()
    {
        $pageId = $this->get_settings_for_display('results_page');
        if (empty($pageId)) {
            return vehicaApp('car_archive_url');
        }

        $url = get_permalink($pageId);
        if (!$url || $url instanceof WP_Error) {
            return vehicaApp('car_archive_url');
        }

        return $url;
    }

}