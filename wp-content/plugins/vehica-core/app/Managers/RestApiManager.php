<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\AttachmentsField;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use WP_Post;

class RestApiManager extends Manager
{

    public function boot()
    {
        add_action('rest_api_init', [$this, 'registerFields']);

        add_action('rest_insert_' . Car::POST_TYPE, function (WP_Post $post, $request) {
            $meta = $request->get_param('meta');
            $car = new Car($post);

            vehicaApp('car_fields')
                ->filter(static function ($field) use ($meta) {
                    /* @var Field $field */
                    return !$field instanceof Taxonomy && isset($meta[$field->getKey()]);
                })
                ->each(static function ($field) use ($car, $meta) {
                    $field->save($car, $meta[$field->getKey()]);
                });
        }, 10, 2);
    }

    public function registerFields()
    {
        foreach (vehicaApp('car_fields') as $field) {
            if ($field instanceof Taxonomy) {
                continue;
            }

            register_rest_field(Car::POST_TYPE, $field->getKey(), [
                'get_callback' => function ($post) use ($field) {
                    if (!$field instanceof GalleryField && !$field instanceof AttachmentsField && !$field instanceof PriceField) {
                        return get_post_meta($post['id'], $field->getKey(), true);
                    }

                    if ($field instanceof PriceField) {
                        $car = Car::getById($post['id']);
                        if (!$car) {
                            return [];
                        }

                        return $field->getValue($car);
                    }


                    $ids = explode(',', get_post_meta($post['id'], $field->getKey(), true));
                    if ($field instanceof GalleryField) {
                        $images = [];

                        foreach ($ids as $id) {
                            $imageUrl = wp_get_attachment_image_url($id, 'full');
                            if ($imageUrl) {
                                $images[] = $imageUrl;
                            }
                        }

                        return $images;
                    }

                    $files = [];

                    foreach ($ids as $id) {
                        $file = wp_get_attachment_url($id);
                        if ($file) {
                            $files[] = $file;
                        }
                    }

                    return $files;
                },
            ]);
        }
    }

}