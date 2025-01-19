<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;

class LiteSpeedManager extends Manager
{

    public function boot()
    {
        add_filter( 'litespeed_vary_cookies', function ( $list ) {
            $list[] = 'vehicaCurrentCurrency';
            $list[] = 'vehica_compare';

            return $list;
        } );
    }

}