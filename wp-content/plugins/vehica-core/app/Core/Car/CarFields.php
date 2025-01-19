<?php

namespace Vehica\Core\Car;


use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Image;
use Vehica\Model\Term\Term;
use WP_Query;

/**
 * Class CarFields
 *
 * @package Vehica\Core\Car
 */
class CarFields
{
    /**
     * @var Collection
     */
    protected $fields;

    /**
     * @var Collection
     */
    protected $cars;

    /**
     * CarFields constructor.
     *
     * @param Collection $fields
     * @param Collection $cars
     */
    protected function __construct(Collection $fields, Collection $cars)
    {
        $this->fields = $fields;
        $this->cars = $cars;
    }

    /**
     * @param Collection $fields
     * @param Collection $cars
     *
     * @return CarFields
     */
    public static function make(Collection $fields, Collection $cars)
    {
        return new self($fields, $cars);
    }

    public function prepare()
    {
        $this->prepareJsonFieldIds();

        $this->prepareTaxonomyFields();
    }

    private function prepareJsonFieldIds()
    {
        if (vehicaApp()->has('car_json_fields')) {
            return;
        }

        vehicaApp()->bind('car_json_fields', $this->fields->map(static function ($field) {
            /* @var Field $field */
            return $field->getId();
        })->all());
    }

    protected function prepareTaxonomyFields()
    {
        $this->fields->filter(static function ($field) {
            return $field instanceof Taxonomy;
        })->each(function ($taxonomy) {
            $this->prepareTaxonomyField($taxonomy);
        });
    }

    /**
     * @param Taxonomy $taxonomy
     */
    protected function prepareTaxonomyField(Taxonomy $taxonomy)
    {
        $terms = $taxonomy->getPostsTerms($this->cars);
        $this->cars->each(static function ($car) use ($taxonomy, $terms) {
            /* @var Car $car */
            $carTerms = $terms->filter(static function ($term) use ($car) {
                /* @var Term $term */
                return $term->getObjectId() === $car->getId();
            })->all();

            $key = $taxonomy->getKey() . '_' . $car->getId();
            vehicaApp()->bind($key, $carTerms);
        });
    }

}