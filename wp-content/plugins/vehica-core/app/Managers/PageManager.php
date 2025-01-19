<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Page;

/**
 * Class PageManager
 * @package Vehica\Managers
 */
class PageManager extends Manager
{

    public function boot()
    {
        add_action('wp_insert_post', [$this, 'addElementorSupport'], 10, 2);
    }

    /**
     * @param int $postId
     * @param \WP_Post $post
     */
    public function addElementorSupport($postId, $post)
    {
        if ($post->post_type !== Page::POST_TYPE) {
            return;
        }

        update_post_meta($postId, '_elementor_edit_mode', true);
    }

}