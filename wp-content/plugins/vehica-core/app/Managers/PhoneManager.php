<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;

/**
 * Class PhoneManager
 * @package Vehica\Managers
 */
class PhoneManager extends Manager
{

    public function boot()
    {
        add_action('wp_ajax_vehica_phone', [$this, 'phone']);
        add_action('wp_ajax_nopriv_vehica_phone', [$this, 'phone']);
    }

    public function phone()
    {
        if (!isset($_POST['userId'])) {
            die;
        }

        $userId = (int)$_POST['userId'];
        $user = User::getById($userId);
        if (!$user) {
            exit;
        }

        if (isset($_POST['carId'])) {
            $this->increasePhoneClicks();
        }

        echo json_encode([
            'label' => $user->getPhone(),
            'url' => 'tel:'.$user->getPhoneUrl(),
        ]);
        exit;
    }

    public function increasePhoneClicks()
    {
        $carId = (int)$_POST['carId'];
        if (empty($carId)) {
            return;
        }

        $car = Car::getById($carId);
        if (!$car instanceof Car) {
            return;
        }

        $car->increasePhoneClickNumber();
    }

}