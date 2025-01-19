<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Post;
use WP_Post;

/**
 * Class SocialTagsManager
 * @package Vehica\Managers
 */
class SocialTagsManager extends Manager
{

    public function boot()
    {
        add_action('wp_head', [$this, 'display']);
    }

    public function display()
    {
        if (is_singular(Post::POST_TYPE)) {
            $this->singlePostTags();
            return;
        }

        if (is_singular(Car::POST_TYPE)) {
            $this->singleCarTags();
        }
    }

    public function singlePostTags()
    {
        global $post;
        if (!$post instanceof WP_Post) {
            return;
        }

        global $vehicaPost;
        $vehicaPost = new Post($post);

        if (vehicaApp('settings_config')->hasFacebookApi()) {
            ?>
            <meta
                    property="og:app_id"
                    content="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookApi()); ?>"
            />
            <?php
        }
        ?>
        <meta property="og:url" content="<?php echo esc_url($vehicaPost->getUrl()) ?>"/>
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="<?php echo esc_attr($vehicaPost->getName()); ?>"/>
        <meta property="og:description" content="<?php echo esc_attr($vehicaPost->getExcerpt()); ?>"/>
        <?php
        if ($vehicaPost->hasImage('full')) {
            ?>
            <meta
                    property="og:image"
                    content="<?php echo esc_url(wp_get_attachment_image_url($vehicaPost->getImageId(), 'full')) ?>"
            />
            <?php
        }
    }

    public function singleCarTags()
    {
        global $post;
        if (!$post instanceof WP_Post) {
            return;
        }

        global $vehicaCar;
        $vehicaCar = new Car($post);

        if (vehicaApp('settings_config')->hasFacebookApi()) {
            ?>
            <meta
                    property="og:app_id"
                    content="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookApi()); ?>"
            />
            <?php
        }
        ?>
        <meta property="og:url" content="<?php echo esc_url($vehicaCar->getUrl()) ?>"/>
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="<?php echo esc_attr($vehicaCar->getName()); ?>"/>
        <meta property="og:description" content="<?php echo esc_attr($vehicaCar->getExcerpt()); ?>"/>
        <?php
        $galleryField = vehicaApp('car_fields')->find(static function ($field) {
            return $field instanceof GalleryField;
        });
        if ($galleryField instanceof GalleryField) {
            foreach ($galleryField->getValue($vehicaCar) as $imageId) {
                ?>
                <meta
                        property="og:image"
                        content="<?php echo esc_url(wp_get_attachment_image_url($imageId, 'full')) ?>"
                />
                <?php
            }
        }
    }

}