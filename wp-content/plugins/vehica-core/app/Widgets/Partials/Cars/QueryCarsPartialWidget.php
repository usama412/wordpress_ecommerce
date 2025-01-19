<?php

namespace Vehica\Widgets\Partials\Cars;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Field\Fields\Price\Price;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\User\User;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\WidgetPartial;
use WP_Query;

/**
 * Trait QueryCarsPartialWidget
 * @package Vehica\Widgets\Partials\Cars
 */
trait QueryCarsPartialWidget
{
    use WidgetPartial;

    /**
     * @var Collection|null
     */
    protected $cars;

    /**
     * @var array
     */
    protected $taxQuery = [];

    protected function addQueryCarsLimitControl()
    {
        $this->add_control(
            QueryCars::LIMIT,
            [
                'label' => esc_html__('Number of Listings to Display', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'label_block' => true
            ]
        );
    }

    protected function addQueryCarsOffsetControl()
    {
        $this->add_control(
            QueryCars::OFFSET,
            [
                'label' => esc_html__('Offset', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0
            ]
        );
    }

    protected function addQueryCarsIncludeControl()
    {
        $this->add_control(
            QueryCars::INCLUDED,
            [
                'label' => esc_html__('Include Specific Listings', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Car::getApiEndpoint(),
                'multiple' => true
            ]
        );
    }

    protected function addQueryCarsExcludeControl()
    {
        $this->add_control(
            QueryCars::EXCLUDED,
            [
                'label' => esc_html__('Exclude Specific Listings', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Car::getApiEndpoint(),
                'multiple' => true
            ]
        );
    }

    protected function addIncludeExcludedControl()
    {
        $this->add_control(
            QueryCars::INCLUDE_EXCLUDED,
            [
                'label' => esc_html__('Display listings that are hidden on Search Result ( /wp-admin/ > Vehica Panel > "Exclude From Search Results")', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );
    }

    protected function addUsersControl()
    {
        $this->add_control(
            QueryCars::USERS,
            [
                'label' => esc_html__('Users', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'multiple' => true,
                'source' => User::getApiEndpoint(),
            ]
        );
    }

    /**
     * @return array
     */
    protected function getUsers()
    {
        $users = $this->get_settings_for_display(QueryCars::USERS);

        if (!is_array($users) || empty($users)) {
            return [];
        }

        return $users;
    }

    /**
     * @return bool
     */
    protected function includeExcluded()
    {
        return !empty($this->get_settings_for_display(QueryCars::INCLUDE_EXCLUDED));
    }

    protected function addQueryCarsTaxonomyControls()
    {
        vehicaApp('taxonomies')->each(function ($taxonomy) {
            /* @var Taxonomy $taxonomy */
            $this->add_control(
                $taxonomy->getKey() . '_' . QueryCars::QUERY_TERMS,
                [
                    'label' => $taxonomy->getName(),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                    'multiple' => true,
                ]
            );
        });
    }

    protected function addQueryCarsSortByControl()
    {
        $this->add_control(
            QueryCars::SORT_BY,
            [
                'label' => esc_html__('Sort By', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getSortByOptions(),
                'default' => QueryCars::SORT_BY_DATE
            ]
        );
    }

    /**
     * @return array
     */
    private function getSortByOptions()
    {
        $options = [
            QueryCars::SORT_BY_NAME => esc_html__('Name', 'vehica-core'),
            QueryCars::SORT_BY_ID => esc_html__('ID', 'vehica-core'),
            QueryCars::SORT_BY_DATE => esc_html__('Date', 'vehica-core'),
            QueryCars::SORT_BY_RANDOM => esc_html__('Random', 'vehica-core'),
            QueryCars::SORT_BY_INCLUDE_ORDER => esc_html__('Include Order', 'vehica-core')
        ];

        foreach (vehicaApp('price_fields') as $priceField) {
            /* @var PriceField $priceField */
            $options[$priceField->getKey() . '_low_to_high'] = sprintf(esc_html__('%s - Low to High', 'vehica-core'), $priceField->getName());
            $options[$priceField->getKey() . '_high_to_low'] = sprintf(esc_html__('%s - High to Low', 'vehica-core'), $priceField->getName());
        }

        foreach (vehicaApp('number_fields') as $numberField) {
            /* @var NumberField $numberField */
            $options[$numberField->getKey() . '_low_to_high'] = sprintf(esc_html__('%s - Low to High', 'vehica-core'), $numberField->getName());
            $options[$numberField->getKey() . '_high_to_low'] = sprintf(esc_html__('%s - High to Low', 'vehica-core'), $numberField->getName());
        }

        return $options;
    }

    /**
     * @param array $exclude
     */
    protected function addQueryCarsControls($exclude = [])
    {
        if (!in_array(QueryCars::LIMIT, $exclude, true)) {
            $this->addQueryCarsLimitControl();
        }

        if (!in_array(QueryCars::SORT_BY, $exclude, true)) {
            $this->addQueryCarsSortByControl();
        }

        if (!in_array(QueryCars::USERS, $exclude, true)) {
            $this->addUsersControl();
        }

        if (!in_array(QueryCars::INCLUDE_EXCLUDED, $exclude, true)) {
            $this->addIncludeExcludedControl();
        }

        if (!in_array(QueryCars::INCLUDED, $exclude, true)) {
            $this->addQueryCarsIncludeControl();
        }

        if (!in_array(QueryCars::EXCLUDED, $exclude, true)) {
            $this->addQueryCarsExcludeControl();
        }

        if (!in_array(QueryCars::QUERY_TERMS, $exclude, true)) {
            $this->addQueryCarsTaxonomyControls();
        }
    }

    /**
     * @return array
     */
    protected function getExcludedCars()
    {
        $output = $this->includeExcluded() ? [] : vehicaApp('cars_excluded_from_search');
        $excludedCars = $this->get_settings_for_display(QueryCars::EXCLUDED);

        if (!is_array($excludedCars)) {
            return $output;
        }

        return array_merge($excludedCars, $output);
    }

    /**
     * @return array
     */
    protected function getIncludedCars()
    {
        $includedCars = $this->get_settings_for_display(QueryCars::INCLUDED);

        if (!is_array($includedCars)) {
            return [];
        }

        return $includedCars;
    }


    /**
     * @return int
     */
    protected function getCarsLimit()
    {
        $limit = (int)$this->get_settings_for_display(QueryCars::LIMIT);

        if ($limit === 0) {
            return 6;
        }

        return $limit;
    }

    /**
     * @return string
     */
    protected function getSortCarsBy()
    {
        $sortBy = (string)$this->get_settings_for_display(QueryCars::SORT_BY);

        if (empty($sortBy)) {
            return QueryCars::SORT_BY_DATE;
        }

        return $sortBy;
    }

    /**
     * @return bool
     */
    protected function sortCarsByName()
    {
        return $this->getSortCarsBy() === QueryCars::SORT_BY_NAME;
    }

    /**
     * @return bool
     */
    protected function sortCarsById()
    {
        return $this->getSortCarsBy() === QueryCars::SORT_BY_ID;
    }

    /**
     * @return bool
     */
    protected function sortCarsByRandom()
    {
        return $this->getSortCarsBy() === QueryCars::SORT_BY_RANDOM;
    }

    protected function sortByIncludeOrder()
    {
        return $this->getSortCarsBy() === QueryCars::SORT_BY_INCLUDE_ORDER;
    }


    /**
     * @return bool
     */
    protected function sortCarsByDate()
    {
        return $this->getSortCarsBy() === QueryCars::SORT_BY_DATE;
    }

    protected function addIsUserControl()
    {
        $this->add_control(
            QueryCars::IS_USER,
            [
                'type' => Controls_Manager::HIDDEN,
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    protected function isUser()
    {
        $isUser = $this->get_settings_for_display(QueryCars::IS_USER);
        return !empty($isUser);
    }

    /**
     * @return array
     */
    protected function getQueryArgs()
    {
        $args = [
            'post__in' => $this->getIncludedCars(),
            'post__not_in' => $this->getExcludedCars(),
            'posts_per_page' => $this->getCarsLimit(),
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'author__in' => $this->getUsers(),
        ];

        if ($this->isUser() && method_exists($this, 'getUser')) {
            $user = $this->getUser();
            if ($user instanceof User) {
                $args['author'] = $user->getId();
            }
        }

        if (!empty($this->get_settings_for_display('featured'))) {
            $args['meta_key'] = Car::FEATURED;
            $args['meta_value'] = '1';
        }

        if ($this->sortCarsByName()) {
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
        } elseif ($this->sortCarsById()) {
            $args['orderby'] = 'ID';
            $args['order'] = 'DESC';
        } elseif ($this->sortCarsByRandom()) {
            $args['order'] = '';
            $args['orderby'] = 'rand';
        } elseif ($this->sortCarsByDate()) {
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
        } elseif ($this->sortByPrice()) {
            $args['meta_key'] = $this->getSortByPriceFieldKey();
            $args['orderby'] = 'meta_value_num';
            $args['order'] = $this->getSortByPriceOrder();
        } elseif ($this->sortByNumberField()) {
            $args['meta_key'] = $this->getSortByNumberField()->getKey();
            $args['orderby'] = 'meta_value_num';
            $args['order'] = $this->getSortByNumberOrder();
        } elseif ($this->sortByIncludeOrder()) {
            $args['orderby'] = 'post__in';
        }

        $taxQuery = $this->getTaxQuery();
        if ($taxQuery) {
            $args['tax_query'] = $taxQuery;
        }

        return $args;
    }

    /**
     * @return string
     */
    private function getSortByPriceOrder()
    {
        $sortBy = $this->getSortCarsBy();

        return strpos($sortBy, 'high_to_low') === false ? 'ASC' : 'DESC';
    }

    /**
     * @return string
     */
    private function getSortByNumberOrder()
    {
        return $this->getSortByPriceOrder();
    }

    /**
     * @return bool
     */
    private function sortByPrice()
    {
        return $this->getSortByPriceField() !== false;
    }

    /**
     * @return PriceField|false
     */
    private function getSortByPriceField()
    {
        $sortBy = $this->getSortCarsBy();

        return vehicaApp('price_fields')->find(static function ($priceField) use ($sortBy) {
            /* @var PriceField $priceField */
            return $priceField->getKey() . '_low_to_high' === $sortBy || $priceField->getKey() . '_high_to_low' === $sortBy;
        });
    }

    /**
     * @return NumberField|false
     */
    private function getSortByNumberField()
    {
        $sortBy = $this->getSortCarsBy();

        return vehicaApp('number_fields')->find(static function ($numberField) use ($sortBy) {
            /* @var NumberField $numberField */
            return $numberField->getKey() . '_low_to_high' === $sortBy || $numberField->getKey() . '_high_to_low' === $sortBy;
        });
    }

    /**
     * @return bool
     */
    private function sortByNumberField()
    {
        return $this->getSortByNumberField() !== false;
    }

    /**
     * @return string
     */
    private function getSortByPriceFieldKey()
    {
        $priceField = $this->getSortByPriceField();

        if (!$priceField) {
            return '';
        }

        return Price::make($priceField, vehicaApp('current_currency'))->getKey();
    }

    protected function prepareCars()
    {
        $args = $this->getQueryArgs();
        $query = new WP_Query($args);
        $this->cars = Collection::make($query->posts)->map(static function ($car) {
            return new Car($car);
        });
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        if ($this->cars === null) {
            $this->prepareCars();
        }

        return $this->cars;
    }

    /**
     * @return bool
     */
    public function hasCars()
    {
        return $this->getCars()->isNotEmpty();
    }

    /**
     * @return array|bool
     */
    protected function getTaxQuery()
    {
        $taxQuery = ['relation' => 'AND'];

        foreach ($this->taxQuery as $query) {
            $taxQuery[] = $query;
        }

        vehicaApp('taxonomies')->each(function ($taxonomy) use (&$taxQuery) {
            /* @var Taxonomy $taxonomy */
            $terms = $this->get_settings_for_display($taxonomy->getKey() . '_car_query_terms');
            if (is_array($terms) && !empty($terms)) {
                $taxQuery[] = [
                    'taxonomy' => $taxonomy->getKey(),
                    'field' => 'term_id',
                    'terms' => $terms
                ];
            }
        });

        if (count($taxQuery) < 2) {
            return false;
        }

        return $taxQuery;
    }

}