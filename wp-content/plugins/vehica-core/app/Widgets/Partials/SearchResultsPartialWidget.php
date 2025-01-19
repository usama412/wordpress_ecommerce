<?php


namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Api\CarsApi;
use Vehica\Components\Card\Car\Card;
use Vehica\Core\Car\CarFields;
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
use Vehica\Model\Term\Term;
use Vehica\Search\Field\DateSearchField;
use Vehica\Search\Field\LocationSearchField;
use Vehica\Search\Field\NumberSearchField;
use Vehica\Search\Field\PriceSearchField;
use Vehica\Search\Field\SearchField;
use Vehica\Search\Field\TaxonomySearchField;
use Vehica\Search\Field\TextSearchField;
use Vehica\Search\QueryModifier\LimitQueryModifier;
use Vehica\Search\Searchable;
use Vehica\Search\SearchControl;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use Vehica\Widgets\Controls\SelectRemoteControl;

/**
 * Trait SearchResultsPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait SearchResultsPartialWidget
{
    use CarCardPartialWidget;

    /**
     * @var array
     */
    protected $additionalParams = [];


    /**
     * @var array
     */
    protected $initialFilters = [];

    /**
     * @var array
     */
    protected $results;

    /**
     * @var CarsApi
     */
    protected $carsApi;

    protected function addLimitControl()
    {
        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Results Per Page', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 12,
            ]
        );
    }


    /**
     * @return array
     */
    public function getResults()
    {
        if ($this->results === null) {
            $this->prepareResults();
        }

        return $this->results;
    }

    public function getCars()
    {
        if ($this->results === null) {
            $this->prepareResults();
        }

        return $this->results['results'];
    }

    public function prepareResults()
    {
        /** @noinspection AdditionOperationOnArraysInspection */
        $params = $this->additionalParams + $this->getSearchParams();

        $this->prepareInitialFilters($params);

        $this->carsApi = new CarsApi($params);

        $this->results = $this->carsApi->getResults();

        CarFields::make(Collection::make(), $this->results['results'])->prepare();
    }

    /**
     * @return array
     */
    private function getSearchParams()
    {
        $params = [];
        $params[LimitQueryModifier::PARAM] = $this->getResultsLimit();
        $params[vehicaApp('sort_by_rewrite')] = $this->getInitialSortBy();

        vehicaApp('search_url_modifiers')->each(static function ($urlModifier) use (&$params) {
            /* @var UrlModifier $urlModifier */
            $params = array_merge($params, $urlModifier->getSearchParamsFromUrl());
        });

        foreach (vehicaApp('taxonomy_url') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $value = get_query_var($taxonomy->getKey());
            if (!empty($value)) {
                $params[$taxonomy->getRewrite()] = explode(',', $value);
            }
        }

        return $params;
    }

    private function prepareInitialFilters($params)
    {
        vehicaApp('search_filters')->each(function ($searchFilter) use ($params) {
            /* @var SearchFilter $searchFilter */
            $filters = $searchFilter->getInitialSearchParams($params);

            if (!$filters) {
                return;
            }

            $this->initialFilters = array_merge($this->initialFilters, $filters);
        });
    }

    /**
     * @return int
     */
    public function getResultsLimit()
    {
        return (int)$this->get_settings_for_display('limit');
    }

    /**
     * @return array
     */
    public function getInitialFilters()
    {
        return $this->initialFilters;
    }

    /**
     * @return array
     */
    public function getAdditionalSettings()
    {
        return [
            'base_url' => get_post_type_archive_link(Car::POST_TYPE)
        ];
    }

    /**
     * @return Card
     */
    public function getCarCard()
    {
        if ($this->isCardV1()) {
            return $this->getCardV1();
        }

        if ($this->isCardV2()) {
            return $this->getCardV2();
        }

        if ($this->isCardV3()) {
            return $this->getCardV3();
        }

        if ($this->isCardV5()) {
            return $this->getCardV5();
        }

        return $this->getCardV1();
    }

    protected function addShowViewSelector()
    {
        $this->add_control(
            'vehica_show_view_selector',
            [
                'label' => esc_html__('Display View Selector', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showViewSelector()
    {
        $show = $this->get_settings_for_display('vehica_show_view_selector');

        return !empty($show);
    }

    /**
     * @return string
     */
    public function getInitialCard()
    {
        if (isset($_GET['view'])) {
            return strpos($_GET['view'], 'vehica_card_') !== false ? $_GET['view'] : 'vehica_card_' . $_GET['view'];
        }

        if (isset($_COOKIE['vehica_results_view'])) {
            return strpos($_COOKIE['vehica_results_view'], 'vehica_card_') !== false ? $_COOKIE['vehica_results_view'] : 'vehica_card_' . $_COOKIE['vehica_results_view'];
        }

        $card = (string)$this->get_settings_for_display('vehica_initial_card');

        if (empty($card)) {
            return Card::TYPE_V3;
        }

        return $card;
    }

    /**
     * @return array
     */
    public function getCardConfig()
    {
        if ($this->isCardV1()) {
            return $this->getCardV1Config();
        }

        if ($this->isCardV2()) {
            return $this->getCardV2Config();
        }

        if ($this->isCardV3()) {
            return $this->getCardV3Config();
        }

        if ($this->isCardV5()) {
            return $this->getCardV5Config();
        }

        return $this->getCardV2Config();
    }

    /**
     * @return bool
     */
    public function isCardV1()
    {
        return $this->getInitialCard() === Card::TYPE_V1;
    }

    /**
     * @return bool
     */
    public function isCardV2()
    {
        return $this->getInitialCard() === Card::TYPE_V2;
    }

    /**
     * @return bool
     */
    public function isCardV3()
    {
        return $this->getInitialCard() === Card::TYPE_V3;
    }

    /**
     * @return bool
     */
    public function isCardV5()
    {
        return $this->getInitialCard() === Card::TYPE_V5;
    }

    /**
     * @return int
     */
    public function getInitialPage()
    {
        if (!isset($_GET[vehicaApp('page_rewrite')])) {
            return 1;
        }

        $page = (int)$_GET[vehicaApp('page_rewrite')];
        if (empty($page)) {
            return 1;
        }

        return $page;
    }

    /**
     * @return array
     */
    public function get_script_depends()
    {
        if (empty(vehicaApp('google_maps_api_key'))) {
            return [];
        }

        return ['google-maps'];
    }


    private function addSortBySection()
    {
        $this->start_controls_section(
            'sort_by_content',
            [
                'label' => esc_html__('Sort By', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addSortByControls();

        $this->end_controls_section();
    }

    /**
     * @var Collection
     */
    protected $primaryFieldTerms;

    protected function addPrimaryFieldControls()
    {
        $taxonomies = [];
        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $taxonomies[$taxonomy->getKey()] = $taxonomy->getName();
        }

        $this->add_control(
            'vehica_primary_field',
            [
                'label' => esc_html__('Primary Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $taxonomies
            ]
        );

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $fields = new Repeater();
            $fields->add_control(
                'term',
                [
                    'label' => esc_html__('Term', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                ]
            );

            $this->add_control(
                'vehica_primary_field_terms_' . $taxonomy->getKey(),
                [
                    'label' => esc_html__('Terms', 'vehica-core'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $fields->get_controls(),
                    'prevent_empty' => false,
                    'condition' => [
                        'vehica_primary_field' => $taxonomy->getKey()
                    ],
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function getInitialSortBy()
    {
        $sortByRewrite = vehicaApp('sort_by_rewrite');

        if (!empty($_GET[$sortByRewrite])) {
            return trim($_GET[$sortByRewrite]);
        }

        return $this->getDefaultSortBy();
    }

    /**
     * @return string
     */
    public function getDefaultSortBy()
    {
        $sortBy = (string)$this->get_settings('vehica_sort_by_initial');

        if (empty($sortBy)) {
            return vehicaApp('newest_rewrite');
        }

        return $sortBy;
    }

    /**
     * @return bool
     */
    public function showSortBy()
    {
        $show = $this->get_settings_for_display('vehica_show_sort_by');

        return !empty($show);
    }

    protected function addSortByControls()
    {
        $options = vehicaApp('sort_by_options');

        $this->add_control(
            'vehica_sort_by_initial',
            [
                'label' => esc_html__('Initial Sort By', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'default' => vehicaApp('newest_rewrite'),
                'options' => $options
            ]
        );

        $this->add_control(
            'vehica_show_sort_by',
            [
                'label' => esc_html__('Show Sort By Control', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $fields = new Repeater();
        $fields->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => vehicaApp('newest_rewrite')
            ]
        );

        $fields->add_control(
            'label',
            [
                'label' => esc_html__('Custom Label', 'vehica-core'),
                'type' => Controls_Manager::TEXT
            ]
        );

        $this->add_control(
            'vehica_sort_by_options',
            [
                'type' => Controls_Manager::REPEATER,
                'label' => esc_html__('Sort By', 'vehica-core'),
                'fields' => $fields->get_controls(),
                'condition' => [
                    'vehica_show_sort_by' => '1'
                ],
                'default' => vehicaApp('sort_by_default_options'),
            ]
        );
    }

    /**
     * @return string
     */
    public function getInitialSortByLabel()
    {
        $sortBy = $this->getInitialSortBy();

        foreach ($this->getSortByOptions() as $sortByOption) {
            if ($sortBy === $sortByOption['key']) {
                return $sortByOption['name'];
            }
        }

        return vehicaApp('date_listed_newest_string');
    }

    /**
     * @return array
     */
    public function getSortByOptions()
    {
        $selectedSortByOptions = $this->get_settings_for_display('vehica_sort_by_options');

        if (!is_array($selectedSortByOptions)) {
            return [];
        }

        $sortByOptions = Collection::make(vehicaApp('sort_by_options'))->map(static function ($sortByName, $sortByKey) {
            return [
                'name' => $sortByName,
                'key' => $sortByKey,
            ];
        });

        return Collection::make($selectedSortByOptions)->map(static function ($sortByOption) use ($sortByOptions) {
            $sortByKey = $sortByOption['type'];

            $option = $sortByOptions->find(static function ($sortByOpt) use ($sortByKey) {
                return $sortByOpt['key'] === $sortByKey;
            });

            if (!$option) {
                return false;
            }

            if (!empty($sortByOption['label'])) {
                $option['name'] = $sortByOption['label'];
            }

            return $option;
        })->filter(static function ($sortByOption) {
            return $sortByOption !== false;
        })->all();
    }


    protected function addShowKeywordControl()
    {
        $this->add_control(
            'vehica_show_keyword',
            [
                'label' => esc_html__('Display Keyword', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showKeyword()
    {
        $show = $this->get_settings_for_display('vehica_show_keyword');

        return !empty($show);
    }

    /**
     * @return string
     */
    public function getInitialKeywordValue()
    {
        $rewrite = vehicaApp('keyword_rewrite');

        if (!isset($_GET[$rewrite]) || empty($rewrite)) {
            return '';
        }

        return (string)trim($_GET[$rewrite]);
    }


    /**
     * @return int
     */
    public function getCarsCount()
    {
        if ($this->results === null) {
            $this->prepareResults();
        }

        return isset($this->results['resultsCount']) ? $this->results['resultsCount'] : 0;
    }

    /**
     * @return string
     */
    public function getFormattedCarsCount()
    {
        return number_format(
            $this->getCarsCount(),
            0,
            vehicaApp('decimal_separator'),
            vehicaApp('thousands_separator')
        );
    }

    /**
     * @return Taxonomy|false
     */
    public function getPrimaryFieldTaxonomy()
    {
        $taxonomyKey = $this->getPrimaryFieldTaxonomyKey();

        return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyKey) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey() === $taxonomyKey;
        });
    }

    /**
     * @return string
     */
    public function getPrimaryFieldTaxonomyKey()
    {
        return (string)$this->get_settings_for_display('vehica_primary_field');
    }

    /**
     * @return Collection
     */
    public function getPrimaryFieldTerms()
    {
        if ($this->primaryFieldTerms === null) {
            $this->preparePrimaryFieldTerms();
        }

        return $this->primaryFieldTerms;
    }

    /**
     * @return bool
     */
    public function hasPrimaryFieldTerms()
    {
        return $this->getPrimaryFieldTerms()->isNotEmpty();
    }

    protected function preparePrimaryFieldTerms()
    {
        $taxonomy = $this->getPrimaryFieldTaxonomy();
        if (!$taxonomy instanceof Taxonomy) {
            $this->primaryFieldTerms = Collection::make();

            return;
        }

        $termsData = $this->get_settings_for_display('vehica_primary_field_terms_' . $taxonomy->getKey());
        $termIds = Collection::make($termsData)->map(static function ($term) use ($taxonomy) {
            return apply_filters('wpml_object_id', (int)$term['term'], $taxonomy->getKey());
        })->all();

        $terms = Term::getTerms($taxonomy, $termIds);

        $this->primaryFieldTerms = Collection::make($termIds)->map(static function ($termId) use ($terms) {
            return $terms->find(static function ($term) use ($termId) {
                /* @var Term $term */
                return $term->getId() === $termId;
            });
        })->filter(static function ($term) {
            return $term !== false;
        });
    }

    /**
     * @return array
     */
    public function getTermsCount()
    {
        if ($this->results === null) {
            $this->prepareResults();
        }

        return isset($this->results['terms']) ? $this->results['terms'] : [];
    }

    /**
     * @return Collection
     */
    public function getSearchFields()
    {
        $searchFieldsData = $this->get_settings_for_display('vehica_search_fields');

        if (!is_array($searchFieldsData) || empty($searchFieldsData)) {
            return Collection::make();
        }

        /** @noinspection DuplicatedCode */
        return Collection::make($searchFieldsData)->map(static function ($searchFieldData) {
            $key = $searchFieldData[SearchField::FIELD];
            $searchable = vehicaApp('car_fields')->find(static function ($field) use ($key) {
                /* @var Field $field */
                return $field->getKey() === $key;
            });

            if (!$searchable instanceof Searchable) {
                return false;
            }

            if ($searchable instanceof LocationField && empty(vehicaApp('google_maps_api_key'))) {
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
    private function getSearchFieldsList()
    {
        $searchFieldsList = [];

        vehicaApp('search_fields')->each(static function ($searchField) use (&$searchFieldsList) {
            /* @var SearchField $searchField */
            $searchFieldsList[$searchField->getKey()] = $searchField->getName();
        });

        return $searchFieldsList;
    }


    protected function addSearchFieldsControls()
    {
        $this->start_controls_section(
            'vehica_search_form_fields',
            [
                'label' => esc_html__('Search Fields', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addPrimaryFieldControls();

        $this->addShowKeywordControl();

        $fields = new Repeater();
        $searchFieldsList = $this->getSearchFieldsList();

        $fields->add_control(
            SearchField::FIELD,
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

        $this->addDateFields($fields);

        $this->addLocationFields($fields);

        $this->addTaxonomyFields($fields);

        $this->add_control(
            'vehica_search_fields',
            [
                'type' => Controls_Manager::REPEATER,
                'label' => esc_html__('Fields', 'vehica-core'),
                'fields' => $fields->get_controls(),
                'prevent_empty' => false,
                'default' => $this->getDefaultFields(),
                'title_field' => '{{{ vehicaSetFieldTitle(search_field) }}}'
            ]
        );

        $this->end_controls_section();
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
                'options' => PriceSearchField::getControlsList(),
                'default' => PriceSearchField::getDefaultControl(),
                'condition' => [
                    SearchField::FIELD => $priceFieldsKeys
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
                        SearchControl::TYPE_RADIO,
                    ]
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
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_RADIO,
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

        vehicaApp('currencies')->each(static function ($currency) use ($fields, $priceFieldsKeys) {
            /* @var Currency $currency */
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
                            SearchControl::TYPE_RADIO,
                            SearchControl::TYPE_CHECKBOX,
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
                'options' => NumberSearchField::getControlsList(),
                'default' => NumberSearchField::getDefaultControl(),
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys
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
                    NumberSearchField::GREATER_THAN => esc_html__('Greater Than', 'vehica-core'),
                    NumberSearchField::LESS_THAN => esc_html__('Less Than', 'vehica-core'),
                ],
                'default' => NumberSearchField::EQUAL,
                'condition' => [
                    SearchField::FIELD => $numberFieldWithoutPriceKeys,
                    NumberSearchField::CONTROL => [
                        SearchControl::TYPE_SELECT,
                        SearchControl::TYPE_RADIO,
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
                        SearchControl::TYPE_CHECKBOX,
                        SearchControl::TYPE_RADIO,
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
    }

    /**
     * @param Repeater $fields
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
                'default' => '30',
                'description' => __('Initial Radius Value works only if you add value that is available in "Radius Values" field (above)', 'vehica-core'),
                'condition' => [
                    SearchField::FIELD => $locationFieldKeys,
                    LocationSearchField::SHOW_RADIUS => '1'
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
                'options' => TaxonomySearchField::getControlsList(),
                'default' => TaxonomySearchField::getDefaultControl(),
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
                        SearchControl::TYPE_RADIO,
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
     * @return array
     */
    protected function getDefaultFields()
    {
        return vehicaApp('search_fields')->filter(static function ($field) {
            return !$field instanceof TextField;
        })->map(static function ($field) {
            /* @var Field $field */
            return [
                SearchField::FIELD => $field->getKey()
            ];
        })->all();
    }

    /**
     * @return array
     */
    public function getTaxonomySearchFieldIds()
    {
        $taxonomySearchFieldIds = $this->getSearchFields()->filter(static function ($searchField) {
            return $searchField instanceof TaxonomySearchField;
        })->map(static function ($taxonomySearchField) {
            /* @var TaxonomySearchField $taxonomySearchField */
            return $taxonomySearchField->getId();
        })->all();

        $field = $this->getPrimaryFieldTaxonomy();
        if (!$field) {
            return $taxonomySearchFieldIds;
        }

        $taxonomySearchFieldIds[] = $field->getId();

        return $taxonomySearchFieldIds;
    }


    protected function addShowResultsNumberControl()
    {
        $this->add_control(
            'vehica_show_results_number',
            [
                'label' => esc_html__('Display Number of Results', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '1',
                'return_value' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showResultsNumber()
    {
        $show = $this->get_settings_for_display('vehica_show_results_number');

        return !empty($show);
    }

    /**
     * @param int $fieldsNumber
     *
     * @return string
     */
    public function getAdvancedButtonClasses($fieldsNumber)
    {
        $classes = [];

        if ($fieldsNumber > 10) {
            $classes[] = 'vehica-results__advanced-button--show-desktop';
        }

        if ($fieldsNumber > 8) {
            $classes[] = 'vehica-results__advanced-button--show-tablet';
        }

        return implode(' ', $classes);
    }

}