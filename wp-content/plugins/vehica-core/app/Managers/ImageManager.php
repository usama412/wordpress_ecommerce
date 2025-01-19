<?php

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Image;

/**
 * Class ImageManager
 * @package Vehica\Managers
 */
class ImageManager extends Manager
{

    public function boot()
    {
        add_action('after_setup_theme', [$this, 'registerSizes']);
        add_action('admin_post_vehica_gallery_info', [$this, 'galleryInfo']);

        add_action('admin_post_vehica/gallery/fetch', [$this, 'fetchGallery']);
    }

    public function fetchGallery()
    {
        if (!isset($_POST['carId'], $_POST['fieldId']) || empty($_POST['carId']) || empty($_POST['fieldId'])) {
            return;
        }

        $carId = (int)$_POST['carId'];
        $fieldId = (int)$_POST['fieldId'];

        $value = get_post_meta($carId, 'vehica_' . $fieldId, true);
        if (empty($value)) {
            echo json_encode([]);
            return;
        }

        $ids = explode(',', get_post_meta($carId, 'vehica_' . $fieldId, true));
        if (empty($ids)) {
            echo json_encode([]);
            return;
        }

        echo json_encode(Collection::make($ids)
            ->map(static function ($id) {
                $id = (int)$id;
                if (empty($id)) {
                    return false;
                }

                $url = wp_get_attachment_image_url($id, 'vehica_default_0.2');
                if (empty($url)) {
                    $url = wp_get_attachment_image_url($id, 'full');
                }

                return [
                    'id' => $id,
                    'url' => $url,
                ];
            })
            ->filter(static function ($image) {
                return $image !== false && !empty($image['url']);
            })
            ->values()
            ->all());
    }

    public function galleryInfo()
    {
        if (empty($_POST['gallery']) || !is_array($_POST['gallery'])) {
            return;
        }

        echo json_encode(Image::getImages($_POST['gallery'])->map(static function ($image) {
            /* @var Image $image */
            return [
                'mcID' => $image->getId(),
                'name' => $image->getName(),
                'url' => $image->getSrc(),
            ];
        })->all());
    }

    public function registerSizes()
    {
        /** @noinspection PhpIncludeInspection */
        Collection::make(require vehicaApp('path') . '/config/imagesizes.php')->each(static function ($imageSize) {
            add_image_size($imageSize['key'], $imageSize['width'], $imageSize['height'], $imageSize['crop']);
        });
    }
}