<?php


namespace Vehica\Managers;


use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Page;

/**
 * Class CompareManager
 * @package Vehica\Managers
 */
class CompareManager extends Manager
{
    const COMPARE = 'vehica_compare';

    public function boot()
    {
        add_action('admin_post_vehica/compare/update', [$this, 'update']);
        add_action('admin_post_nopriv_vehica/compare/update', [$this, 'update']);

        add_action('wp_footer', [$this, 'compareArea']);
    }

    public function update()
    {
        if (empty($_POST['carIds']) || !is_array($_POST['carIds'])) {
            $this->setCarIds([]);
            echo json_encode([]);
            return;
        }

        $carIds = Collection::make($_POST['carIds'])->map(static function ($carId) {
            return (int)$carId;
        })->values()->all();

        $this->setCarIds($carIds);

        echo json_encode(self::getCars($carIds));
    }

    /**
     * @param array $carIds
     * @return array
     */
    public static function getCars($carIds = [])
    {
        if (empty($carIds)) {
            $carIds = self::getCarIds();
        }

        return Collection::make($carIds)->map(static function ($carId) {
            return Car::getById($carId);
        })->filter(static function ($car) {
            return $car !== false;
        })->map(static function ($car) {
            /* @var Car $car */
            return [
                'id' => $car->getId(),
                'name' => $car->getName(),
                'url' => $car->getUrl(),
                'image' => $car->getImageUrl('medium'),
            ];
        })->values()->all();
    }

    public function add()
    {
        if (empty($_POST['carId'])) {
            return;
        }

        $carIds = self::getCarIds();
        $carId = (int)$_POST['carId'];

        if (!in_array($carId, $carIds, true)) {
            $carIds[] = $carId;
        }

        $this->setCarIds($carIds);
    }

    public function remove()
    {
        if (empty($_POST['carId'])) {
            return;
        }

        $carIds = self::getCarIds();
        $carId = (int)$_POST['carId'];

        if (($key = array_search($carId, $carIds, true)) !== false) {
            unset($carIds[$key]);
        }

        $this->setCarIds($carIds);
    }

    /**
     * @param array $carIds
     */
    private function setCarIds($carIds)
    {
        /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
        /** @noinspection SecureCookiesTransferInspection */
        setcookie(self::COMPARE, json_encode($carIds), time() + (86400 * 30), '/');
    }

    /**
     * @return array
     */
    public static function getCarIds()
    {
        if (empty($_COOKIE[self::COMPARE])) {
            return [];
        }

        $carIds = json_decode($_COOKIE[self::COMPARE], true);
        if (!is_array($carIds) || empty($carIds)) {
            return [];
        }

        return Collection::make($carIds)->map(static function ($carId) {
            return (int)$carId;
        })->all();
    }

    public function compareArea()
    {
        if (!vehicaApp('is_compare_enabled')) {
            return;
        }

        global $post;

        if (
            vehicaApp('compare_mode') === 2
            || is_post_type_archive(Car::POST_TYPE)
            || ($post && $post->ID === vehicaApp('settings_config')->getComparePageId())
            || ($post && $post->post_type === Page::POST_TYPE && Page::getByPost($post)->isCompareEnabled())
        ) {
            get_template_part('templates/compare/compare');
        }
    }

}