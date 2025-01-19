<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class UtilsManager
 * @package Vehica\Managers
 */
class UtilsManager extends Manager
{

    public function boot()
    {
        add_filter('vehica/phone', [$this, 'phoneUrl']);

        add_filter('body_class', [$this, 'bodyClass']);
    }

    /**
     * @param array $classes
     *
     * @return array
     */
    public function bodyClass($classes)
    {
        if (vehicaApp('sticky_menu')) {
            $classes[] = 'vehica-menu-sticky';
        }

        return $classes;
    }

    /**
     * @param string $phone
     *
     * @return string
     */
    public function phoneUrl($phone)
    {
        return trim(str_replace([' ', '-', '(', ')'], '', $phone));
    }

}