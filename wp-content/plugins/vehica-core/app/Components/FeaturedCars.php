<?php


namespace Vehica\Components;


use Vehica\Core\Collection;
use Vehica\Model\Post\Car;

/**
 * Class FeaturedCars
 * @package Vehica\Components
 */
class FeaturedCars
{
    /**
     * @var Collection
     */
    private $cars;

    /**
     * @var Car|false
     */
    private $mainCar;

    public function __construct(Collection $cars)
    {
        $this->cars = $cars;

        $this->mainCar = $this->cars->first();
    }

    /**
     * @return Car|false
     */
    public function getMainCar()
    {
        return $this->mainCar;
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        return $this->cars;
    }

}