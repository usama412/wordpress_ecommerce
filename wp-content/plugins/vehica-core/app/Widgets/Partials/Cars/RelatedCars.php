<?php

namespace Vehica\Widgets\Partials\Cars;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use Vehica\Model\User\User;
use WP_Query;

/**
 * Trait RelatedCars
 * @package Vehica\Widgets\Partials\Cars
 */
trait RelatedCars
{
    /**
     * @var Collection
     */
    protected $cars;

    protected function addRelatedByControls()
    {
        $this->add_control(
            'vehica_cars_related_by_taxonomies',
            [
                'label' => esc_html__('Related by', 'vehica-core'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => vehicaApp('car_fields')->filter(static function ($field) {
                    return $field instanceof Taxonomy;
                })->toList()
            ]
        );

        $this->add_control(
            'vehica_cars_related_by_user',
            [
                'label' => esc_html__('Only from car owner', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );
    }

    /**
     * @return Collection
     */
    protected function getRelatedByTaxonomies()
    {
        $taxonomyIds = $this->get_settings_for_display('vehica_cars_related_by_taxonomies');
        if (!is_array($taxonomyIds) || empty($taxonomyIds)) {
            return Collection::make();
        }

        return Collection::make($taxonomyIds)->map(static function ($taxonomyId) {
            $taxonomyId = (int)$taxonomyId;
            return vehicaApp('car_fields')->find(static function ($field) use ($taxonomyId) {
                /* @var Field $field */
                return $field instanceof Taxonomy && $field->getId() === $taxonomyId;
            });
        })->filter(static function ($taxonomy) {
            return $taxonomy !== false;
        });
    }

    protected function prepareRelatedCars()
    {
        $taxQuery = [];
        $car = $this->getCar();

        if (!$car instanceof Car) {
            $this->cars = Collection::make();
            return;
        }

        $args = $this->getQueryArgs();

        if (isset($args['post__not_in']) && is_array($args['post__not_in'])) {
            $args['post__not_in'][] = $car->getId();
        } else {
            $args['post__not_in'] = [$car->getId()];
        }

        $relatedByUser = $this->get_settings_for_display('vehica_cars_related_by_user');
        if (!empty($relatedByUser)) {
            $user = $this->getUser();
            if ($user instanceof User) {
                $args['author'] = $user->getId();
            }
        }

        $this->getRelatedByTaxonomies()->each(static function ($taxonomy) use ($car, &$taxQuery) {
            $terms = $car->getTerms($taxonomy)->map(static function ($term) {
                /* @var Term $term */
                return $term->getId();
            })->all();
            if (empty($terms)) {
                return;
            }

            /* @var Taxonomy $taxonomy */
            $taxQuery[] = [
                [
                    'taxonomy' => $taxonomy->getKey(),
                    'field' => 'term_id',
                    'terms' => $terms
                ]
            ];
        });

        $args['tax_query'] = $taxQuery;

        $query = new WP_Query($args);
        $this->cars = Collection::make($query->posts)->map(static function ($car) {
            return new Car($car);
        });
    }

    /**
     * @return bool
     */
    public function hasRelatedCars()
    {
        if ($this->cars === null) {
            $this->prepareRelatedCars();
        }

        return $this->cars->isNotEmpty();
    }

    /**
     * @return Collection
     */
    public function getRelatedCars()
    {
        if ($this->cars === null) {
            $this->prepareRelatedCars();
        }

        return $this->cars;
    }

}