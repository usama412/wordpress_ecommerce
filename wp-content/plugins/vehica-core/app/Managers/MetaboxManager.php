<?php

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Config\Config;
use WP_Post;

/**
 * Class MetaboxManager
 * @package Vehica\Managers
 */
class MetaboxManager extends Manager
{
    public function boot()
    {
        add_action('add_meta_boxes', [$this, 'addMetaBoxes'], 10, 2);
    }

    /**
     * @param string $postType
     * @param WP_Post|null $post
     */
    public function addMetaBoxes($postType, $post)
    {
        if ($post === null || empty($post->post_status)) {
            return;
        }

        $metaboxes = apply_filters('vehica_metaboxes', []);
        Collection::make($metaboxes)->each(static function ($metabox) use ($postType, $post) {
            if ($postType === Config::POST_TYPE) {
                $configType = get_post_meta($post->ID, Config::TYPE, true);
            } else {
                $configType = '';
            }

            if (!in_array($postType, $metabox['post_types'], true) && !in_array($configType, $metabox['config'], true)) {
                return;
            }

            $id = vehicaApp('prefix') . $metabox['key'];
            add_meta_box($id, $metabox['name'], static function () use ($metabox) {
                $path = vehicaApp('metaboxes_path') . $metabox['key'] . '.php';
                if (file_exists($path)) {
                    global $post;
                    /** @noinspection PhpUnusedLocalVariableInspection */
                    $vehicaPost = BasePost::getByPost($post);

                    /** @noinspection PhpIncludeInspection */
                    require $path;
                }
            }, null, $metabox['context'], $metabox['priority']);
        });
    }

}