<?php

namespace Vehica\Widgets\Car\Single;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Template\CarSingleTemplate;
use Vehica\Model\User\User;
use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;
use Vehica\Model\Post\Template\Template;

/**
 * Class SinglePostWidget
 * @package Vehica\Widgets\Car\Single
 */
abstract class SingleCarWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::CAR_SINGLE
        ];
    }

    public function prepareCar()
    {
        global $post;
        CarSingleTemplate::make($post)->preparePreview();
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--car-single';
    }

    protected function render()
    {
        $postType = get_post_type();
        if ($postType === Template::POST_TYPE || $postType === 'elementor_library') {
            $this->prepareCar();
        }
    }

    public function before_render()
    {
        parent::before_render();

        $postType = get_post_type();
        if ($postType === Template::POST_TYPE || $postType === 'elementor_library') {
            $this->prepareCar();
        }
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        return vehicaApp('car_config')->getAttributes();
    }

    /**
     * @return Car|false
     */
    public function getCar()
    {
        global $vehicaCar;
        if (!$vehicaCar) {
            return false;
        }

        return $vehicaCar;
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        $car = $this->getCar();

        if (!$car) {
            return false;
        }

        return $car->getUser();
    }

}