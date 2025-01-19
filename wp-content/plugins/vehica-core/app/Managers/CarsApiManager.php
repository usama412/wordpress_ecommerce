<?php /** @noinspection AdditionOperationOnArraysInspection */

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Api\CarsApi;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Components\Card\Car\Card;
use Vehica\Core\Car\CarFields;
use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Field\Fields\Price\Currency;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\AttachmentsField;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use WP_REST_Request;

/**
 * Class CarsApiManager
 * @package Vehica\Managers
 */
class CarsApiManager extends Manager
{

    public function boot()
    {
        add_action('rest_api_init', function () {
            register_rest_route('vehica/v1', '/cars/', [
                'methods' => ['GET', 'POST'],
                'callback' => [$this, 'createEndpoint'],
                'permission_callback' => '__return_true',
            ]);
        });

        add_action('wp_ajax_vehica_car_results', [$this, 'cars']);
        add_action('wp_ajax_nopriv_vehica_car_results', [$this, 'cars']);
    }

    /**
     * @return CarsApi
     */
    private function getApi()
    {
        if (isset($_POST['baseUrl'])) {
            $params = ['base_url' => $_POST['baseUrl']] + $_POST + $_GET;
        } else {
            $params = $_POST + $_GET;
        }

        $api = new CarsApi($params);

        $this->prepareApi($api);

        return $api;
    }

    public function cars()
    {
        if ($this->isMapMode()) {
            echo json_encode($this->getMapResponse());
        } else {
            echo json_encode($this->getRegularResponse());
        }
        exit;
    }

    /**
     * @return bool
     */
    private function isMapMode()
    {
        return !empty($_POST['mapMode']);
    }

    private function getRegularResponse()
    {
        $results = $this->getApi()->getResults();

        global $vehicaCurrentCar, $vehicaCarCard;

        $config = $_POST['cardConfig'];
        if (!is_array($config)) {
            $config = [];
        }

        $vehicaCarCard = Card::create($config);

        CarFields::make($vehicaCarCard->getFields(), $results['results'])->prepare();

        ob_start();

        if ($vehicaCarCard->getType() === Card::TYPE_V3) {
            foreach ($results['results'] as $vehicaCurrentCar) {
                $vehicaCarCard->loadTemplate($vehicaCurrentCar);
            }
        } else {
            foreach ($results['results'] as $vehicaCurrentCar) { ?>
                <div class="vehica-inventory-v1__results__card">
                    <?php $vehicaCarCard->loadTemplate($vehicaCurrentCar); ?>
                </div>
                <?php
            }
        }

        $results['results'] = ob_get_clean();

        return $results;
    }

    private function getMapResponse()
    {
        $results = $this->getApi()->getResults();

        global $vehicaCurrentCar, $vehicaCarCard;

        $vehicaCarCard = Card::create($_POST['cardConfig']);

        CarFields::make($vehicaCarCard->getFields(), $results['results'])->prepare();

        $results['markers'] = $this->getMarkers($results['results']);

        ob_start();

        if ($vehicaCarCard->getType() === Card::TYPE_V5) {
            foreach ($results['results'] as $vehicaCurrentCar) {
                $vehicaCarCard->loadTemplate($vehicaCurrentCar);
            }
        } else {
            foreach ($results['results'] as $vehicaCurrentCar) { ?>
                <div class="vehica-inventory-v1__results__card">
                    <?php $vehicaCarCard->loadTemplate($vehicaCurrentCar); ?>
                </div>
                <?php
            }
        }

        $results['results'] = ob_get_clean();

        return $results;

    }

    /**
     * @param  array  $cars
     * @return array
     */
    private function getMarkers($cars)
    {
        $markers = [];

        if (!empty($_POST['markerContentFieldKey'])) {
            $field = vehicaApp('car_fields')->find(static function ($attribute) {
                /* @var Field $attribute */
                return $attribute->getKey() === $_POST['markerContentFieldKey'] && $attribute instanceof SimpleTextAttribute;
            });
        } else {
            $field = false;
        }

        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach (vehicaApp('location_fields') as $locationField) {
            foreach ($cars as $vehicaCurrentCar) {
                /* @var Car $vehicaCurrentCar */
                if ($field) {
                    $markers[] = $vehicaCurrentCar->getMarkerData($locationField, $field);
                } else {
                    $markers[] = $vehicaCurrentCar->getMarkerData($locationField);
                }
            }
            break;
        }

        return $markers;
    }

    private function prepareFieldIds()
    {
        if (vehicaApp()->has('car_json_fields')) {
            return;
        }

        $fieldIds = [];

        if (isset($_GET['carFieldIds']) && is_array($_GET['carFieldIds'])) {
            $fieldIds = $_GET['carFieldIds'];
        } elseif (isset($_POST['carFieldIds']) && is_array($_POST['carFieldIds'])) {
            $fieldIds = $_POST['carFieldIds'];
        }

        if (!empty($fieldIds)) {
            vehicaApp()->bind('car_json_fields', Collection::make($fieldIds)->map(static function ($fieldId) {
                return (int)$fieldId;
            })->all());
        }
    }

    /**
     * @param  WP_REST_Request  $request
     *
     * @return array
     */
    public function createEndpoint(WP_REST_Request $request)
    {
        $this->prepareFieldIds();

        return (new CarsApi($request->get_params()))->getResults();
    }

    /**
     * @param  CarsApi  $api
     */
    private function prepareApi(CarsApi $api)
    {
        if (!empty($_POST['disableCars'])) {
            $api->disableCars();
        }

        if (isset($_POST['taxonomyTermsCountIds']) && is_array($_POST['taxonomyTermsCountIds'])) {
            $taxonomies = Collection::make($_POST['taxonomyTermsCountIds'])->map(static function ($taxonomyId) {
                $taxonomyId = (int)$taxonomyId;

                return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyId) {
                    /* @var Taxonomy $taxonomy */
                    return $taxonomy->getId() === $taxonomyId;
                });
            })->filter(static function ($taxonomy) {
                return $taxonomy !== false;
            });

            $api->setTaxonomiesTermsCount($taxonomies);
        }
    }

}