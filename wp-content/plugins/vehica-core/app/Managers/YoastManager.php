<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use WPSEO_Options;
use Yoast\WP\SEO\Values\Open_Graph\Images;

/**
 * Class YoastManager
 * @package Vehica\Managers
 */
class YoastManager extends Manager
{

    public function boot()
    {
        add_action('admin_init', [$this, 'check']);

        add_filter('wpseo_sitemap_urlimages', [$this, 'images'], 10, 2);

        add_filter('wpseo_add_opengraph_images', [$this, 'carImages']);
    }

    public function carImages(Images $object)
    {
        if (!is_singular(Car::POST_TYPE)) {
            return;
        }

        global $post;
        if (!$post || $post->post_type !== Car::POST_TYPE) {
            return;
        }

        $car = new Car($post);

        foreach (vehicaApp('gallery_fields') as $galleryField) {
            /* @var GalleryField $galleryField */
            foreach ($galleryField->getValue($car) as $imageId) {
                $object->add_image_by_id($imageId);
            }
        }
    }

    public function check()
    {
        if (!class_exists(WPSEO_Options::class)) {
            return;
        }

        WPSEO_Options::set('disable-author', 0);
    }

    /**
     * @param array $images
     * @param int $postId
     * @return array
     */
    public function images($images, $postId)
    {
        $post = get_post($postId);
        if (!$post || $post->post_type !== Car::POST_TYPE) {
            return $images;
        }

        $car = new Car($post);

        foreach (vehicaApp('gallery_fields') as $galleryField) {
            /* @var GalleryField $galleryField */
            foreach ($galleryField->getValue($car) as $imageId) {
                $image = wp_get_attachment_image_url($imageId, 'full');
                if ($image) {
                    $images[] = [
                        'src' => $image,
                    ];
                }
            }
        }

        return $images;
    }

}