<?php


namespace Vehica\Managers;


use Vehica\Api\CarsApi;
use Vehica\Components\Card\Car\CardV1;
use Vehica\Core\Manager;

/**
 * Class CarsCarouselAjaxManager
 * @package Vehica\Managers
 */
class CarsCarouselAjaxManager extends Manager
{

    public function boot()
    {
        add_action('wp_ajax_vehica_car_carousel', [$this, 'cars']);
        add_action('wp_ajax_nopriv_vehica_car_carousel', [$this, 'cars']);
    }

    public function cars()
    {
        if (!isset($_POST['cardConfig'], $_POST['queryParams'])) {
            return;
        }

        $cardConfig = $_POST['cardConfig'];
        $queryParams = $_POST['queryParams'];

        if (!is_array($cardConfig) || !is_array($queryParams)) {
            return;
        }

        global $vehicaCarCard;
        $vehicaCarCard = CardV1::create($cardConfig);
        $api = CarsApi::make($queryParams, !empty($queryParams['includeExcluded']));
        $api->disableTermsCount();

        global $vehicaCurrentCar;
        foreach ($api->getCars() as $vehicaCurrentCar) {
            get_template_part('templates/shared/car_carousel');
        }

        die;
    }

}