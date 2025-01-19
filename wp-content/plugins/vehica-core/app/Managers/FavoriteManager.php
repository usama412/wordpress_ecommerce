<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;

/**
 * Class FavoriteManager
 * @package Vehica\Managers
 */
class FavoriteManager extends Manager
{

    public function boot()
    {
        add_action('wp_ajax_nopriv_vehica_favorite', [$this, 'favorite']);
        add_action('wp_ajax_vehica_favorite', [$this, 'favorite']);
    }

    public function favorite()
    {
        if (!isset($_POST['nonce'], $_POST['carId']) || !is_user_logged_in() || !wp_verify_nonce($_POST['nonce'], 'vehica_favorite')) {
            die;
        }

        $carId = (int)$_POST['carId'];
        $car = Car::getById($carId);
        if (!$car) {
            die;
        }

        vehicaApp('current_user')->addFavorite($car);

        die;
    }

}