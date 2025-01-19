<?php

namespace Vehica\Model\Post\Template;


use Vehica\Model\Post\Car;
use Vehica\Widgets\WidgetCategory;

/**
 * Class CarSingleTemplate
 * @package Vehica\Model\Post\Template
 */
class CarSingleTemplate extends Template
{
    const PREVIEW_CAR = 'vehica_car_preview';

    public function preparePreview()
    {
        $previewCarId = (int)$this->document->get_settings(self::PREVIEW_CAR);

        global $vehicaCar;
        $vehicaCar = Car::getById($previewCarId);

        if (!$vehicaCar instanceof Car) {
            $vehicaCar = Car::first();
        }

        if (!$vehicaCar instanceof Car) {
            return;
        }

        /* @var Car $vehicaCar */
        global $vehicaUser;
        $vehicaUser = $vehicaCar->getUser();
    }

    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return WidgetCategory::CAR_SINGLE;
    }

    public function prepare()
    {
        $this->setMeta('_elementor_edit_mode', 'builder');
        $this->setMeta('_elementor_data', json_decode('[{"id":"754d27f","elType":"section","settings":[],"elements":[{"id":"a58a800","elType":"column","settings":{"_column_size":100},"elements":[{"id":"3fd1d88","elType":"widget","settings":[],"elements":[],"widgetType":"vehica_car_field_single_car_widget"}],"isInner":false}],"isInner":false}]', true));
    }

}