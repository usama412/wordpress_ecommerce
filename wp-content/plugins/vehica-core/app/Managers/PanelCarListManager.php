<?php


namespace Vehica\Managers;


use Vehica\Components\Panel\CarList;
use Vehica\Core\Manager;

/**
 * Class PanelCarListManager
 * @package Vehica\Managers
 */
class PanelCarListManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_car_list', [$this, 'cars']);
    }

    public function cars()
    {
        global $vehicaCarList;
        /** @noinspection AdditionOperationOnArraysInspection */
        $vehicaCarList = CarList::make($_GET + $_POST);

        get_template_part('templates/general/panel/car_list_partial');
    }

}