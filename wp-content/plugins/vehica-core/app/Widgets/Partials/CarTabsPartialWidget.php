<?php


namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Api\CarsApi;
use Vehica\Core\Car\CarFields;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\Cars\QueryCars;

/**
 * Trait CarTabsPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait CarTabsPartialWidget
{
    use WidgetPartial;

    /**
     * @var Collection
     */
    protected $cars;

    /**
     * @var Collection
     */
    protected $tabs;

    protected function addFeaturedControl()
    {
        $this->add_control(
            'featured',
            [
                'label' => esc_html__('Narrow results to featured listings', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => '0',
                'return_value' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function featured()
    {
        return !empty($this->get_settings_for_display('featured'));
    }

    protected function addTabsControls()
    {
        $taxonomiesList = [];
        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $taxonomiesList[$taxonomy->getKey()] = $taxonomy->getName();
        }
        $this->add_control(
            'taxonomy',
            [
                'label' => esc_html__('Taxonomy', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $taxonomiesList,
            ]
        );

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $repeater = new Repeater();
            $repeater->add_control(
                'term',
                [
                    'label' => esc_html__('Tab', 'vehica-core'),
                    'type' => SelectRemoteControl::TYPE,
                    'source' => $taxonomy->getApiEndpoint(),
                ]
            );

            $this->add_control(
                'vehica_tabs_' . $taxonomy->getId(),
                [
                    'label' => esc_html__('Tabs', 'vehica-core'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'prevent_empty' => false,
                    'default' => [
                        [
                            'term' => ''
                        ]
                    ],
                    'condition' => [
                        'taxonomy' => $taxonomy->getKey()
                    ]
                ]
            );
        }
    }

    protected function render()
    {
        $this->loadTemplate();
    }

    /**
     * @return Collection
     */
    public function getTabs()
    {
        if (!$this->tabs) {
            $this->prepareTabs();
        }

        return $this->tabs;
    }

    protected function prepareTabs()
    {
        $taxonomy = $this->getTaxonomy();
        if (!$taxonomy instanceof Taxonomy) {
            $this->tabs = Collection::make();

            return;
        }

        $tabs = $this->get_settings_for_display('vehica_tabs_' . $taxonomy->getId());
        if (empty($tabs)) {
            $this->tabs = Collection::make();

            return;
        }

        $this->tabs = Collection::make($tabs)->map(static function ($tab) {
            if (is_array($tab['term'])) {
                return false;
            }

            return Term::getById($tab['term']);
        })->filter(static function ($term) {
            return $term !== false;
        })->values();
    }

    /**
     * @return Taxonomy|false
     */
    protected function getTaxonomy()
    {
        $taxonomyKey = $this->get_settings_for_display('taxonomy');

        return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyKey) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getKey() === $taxonomyKey;
        });
    }

    /**
     * @return bool
     */
    public function hasTabs()
    {
        return $this->getTabs()->count() > 1;
    }

    protected function prepareCars()
    {
        $this->cars = $this->queryCars();

        CarFields::make($this->getCarFields(), $this->cars)->prepare();
    }

    /**
     * @return Collection
     */
    abstract protected function getCarFields();

    /**
     * @return Collection
     */
    protected function queryCars()
    {
        if (!$this->getTabs()->count()) {
            return Collection::make();
        }

        /* @var Term $tab */
        $tab = $this->getTabs()->first();
        $args = [
            $tab->getTaxonomy()->getRewrite() => $tab->getSlug(),
        ];

        if ($this->featured()) {
            $args[vehicaApp('featured_rewrite')] = '1';
        }

        $args['limit'] = $this->getCarsNumber();

        if ($this->getSortBy() === 'random') {
            $args[vehicaApp('sort_by_rewrite')] = 'random';
        } elseif ($this->getSortBy() === vehicaApp('oldest_rewrite')) {
            $args[vehicaApp('sort_by_rewrite')] = vehicaApp('oldest_rewrite');
        }

        $api = new CarsApi($args, $this->includeExcluded());
        $api->disableTermsCount();

        $results = $api->getResults();

        return $results['results'];
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        $sortBy = (string)$this->get_settings_for_display('order_by');
        if ($sortBy === 'oldest') {
            return vehicaApp('oldest_rewrite');
        }

        return $sortBy;
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        if (!$this->cars) {
            $this->prepareCars();
        }

        return $this->cars;
    }

    /**
     * @return int
     */
    abstract public function getCarsNumber();

    protected function addOrderByControl()
    {
        $this->add_control(
            'order_by',
            [
                'label' => esc_html__('Order By', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__('Newest', 'vehica-core'),
                    'oldest' => esc_html__('Oldest', 'vehica-core'),
                    'random' => esc_html__('Random', 'vehica-core')
                ],
                'default' => 'date'
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

    /**
     * @return bool
     */
    public function includeExcluded(): bool
    {
        return !empty($this->get_settings_for_display(QueryCars::INCLUDE_EXCLUDED));
    }

}