<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class ErrorManager
 * @package Vehica\Managers
 */
class ErrorManager extends Manager
{

    public function boot()
    {
        add_action('template_redirect', static function () {
            if (is_404() && vehicaApp('404_page')) {
                wp_redirect(vehicaApp('404_page')->getUrl());
                exit();
            }
        });
    }

}