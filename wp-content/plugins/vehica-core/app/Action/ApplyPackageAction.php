<?php


namespace Vehica\Action;


use DateTime;
use Vehica\Model\Post\Car;
use Vehica\Panel\Package;

/**
 * Class ApplyPackageAction
 *
 * @package Vehica\Action
 */
class ApplyPackageAction
{
    /**
     * @param Package $package
     * @param Car $car
     *
     * @return bool
     */
    public function apply(Package $package, Car $car)
    {
        if ($package->getNumber() <= 0) {
            return false;
        }

        $this->setExpireDate($package, $car);

        $this->setExpireFeaturedDate($package, $car);

        $car->getUser()->decreasePackage($package->getKey());

        return true;
    }

    /**
     * @param Package $package
     * @param Car $car
     */
    private function setExpireDate(Package $package, Car $car)
    {
        $expireDays = $package->getExpire();
        if ($expireDays <= 0) {
            return;
        }

        $date = new DateTime();
        $date->modify('+' . $expireDays . ' days');

        $car->setExpireDate($date->format('Y-m-d H:i:s'));
    }

    /**
     * @param Package $package
     * @param Car $car
     */
    private function setExpireFeaturedDate(Package $package, Car $car)
    {
        $featuredExpireDays = $package->getFeaturedExpire();
        if ($featuredExpireDays <= 0) {
            return;
        }

        $date = new DateTime();
        $date->modify('+' . $featuredExpireDays . ' days');

        $car->setFeatured();

        $car->setFeaturedExpireDate($date->format('Y-m-d H:i:s'));
    }

}