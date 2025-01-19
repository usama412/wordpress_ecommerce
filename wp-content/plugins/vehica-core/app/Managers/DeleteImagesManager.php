<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use WP_Post;
use WP_Query;

/**
 * Class DeleteImagesManager
 * @package Vehica\Managers
 */
class DeleteImagesManager extends Manager
{

    public function boot()
    {
        add_action('before_delete_post', function ($postId, WP_Post $post) {
            if ($post->post_type !== Car::POST_TYPE || !vehicaApp('settings_config')->deleteImagesWithCar()) {
                return;
            }

            $this->deleteImages(Car::make($post));
        }, 10, 2);

        add_action('admin_post_vehica/images/cleanUp', [$this, 'cleanUp']);
    }

    /**
     * @param Car $car
     */
    public function deleteImages(Car $car)
    {
        foreach (vehicaApp('gallery_fields') as $galleryField) {
            /* @var GalleryField $galleryField */
            foreach ($galleryField->getValue($car) as $imageId) {
                wp_delete_attachment($imageId);
            }
        }
    }

    public function cleanUp()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $imageIds = [];

        foreach (Car::getAll() as $car) {
            foreach (vehicaApp('gallery_fields') as $galleryField) {
                /* @var GalleryField $galleryField */
                $imageIds = array_merge($imageIds, $galleryField->getValue($car));
            }
        }

        $imageIds = array_unique($imageIds);
        $query = new WP_Query([
            'post__not_in' => $imageIds,
            'meta_key' => 'vehica_source',
            'meta_value' => 'panel',
            'posts_per_page' => -1,
            'post_type' => 'attachment',
            'post_status' => 'any'
        ]);

        foreach ($query->posts as $image) {
            $type = get_post_meta($image->ID, 'type', true);
            if ($type !== 'user_profile') {
                wp_delete_attachment($image->ID);
            }
        }

        wp_redirect(admin_url('admin.php?page=vehica_panel_advanced'));
        exit;
    }

    /**
     * @return int
     */
    public static function count()
    {
        $imageIds = [];

        foreach (Car::getAll() as $car) {
            foreach (vehicaApp('gallery_fields') as $galleryField) {
                /* @var GalleryField $galleryField */
                /** @noinspection AdditionOperationOnArraysInspection */
                $imageIds = array_merge($imageIds, $galleryField->getValue($car));
            }
        }

        $imageIds = array_unique($imageIds);

        $query = new WP_Query([
            'post__not_in' => $imageIds,
            'meta_key' => 'vehica_source',
            'meta_value' => 'panel',
            'posts_per_page' => -1,
            'post_type' => 'attachment',
            'post_status' => 'any',
            'fields' => 'ids',
        ]);

        $count = 0;

        foreach ($query->posts as $id) {
            $type = get_post_meta($id, 'type', true);
            if ($type !== 'user_profile') {

                $count++;
            }
        }

        return $count;
    }

}