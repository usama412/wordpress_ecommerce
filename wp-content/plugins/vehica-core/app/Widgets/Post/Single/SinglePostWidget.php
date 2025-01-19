<?php

namespace Vehica\Widgets\Post\Single;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Post;
use Vehica\Model\Post\Template\PostSingleTemplate;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\User\User;
use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;

/**
 * Class SinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
abstract class SinglePostWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::POST_SINGLE
        ];
    }

    public function preparePost()
    {
        global $post;
        PostSingleTemplate::make($post)->preparePreview();
    }

    protected function render()
    {
        $postType = get_post_type();
        if ($postType === Template::POST_TYPE || $postType === 'elementor_library') {
            $this->preparePost();
        }
    }

    public function before_render()
    {
        parent::before_render();

        $postType = get_post_type();
        if ($postType === Template::POST_TYPE || $postType === 'elementor_library') {
            $this->preparePost();
        }
    }

    /**
     * @return Post|false
     */
    public function getPost()
    {
        global $vehicaPost;

        if (!$vehicaPost) {
            return false;
        }

        return $vehicaPost;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        $post = $this->getPost();

        if (!$post) {
            return 0;
        }

        return $post->getId();
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        $post = $this->getPost();

        if (!$post) {
            return false;
        }

        return $post->getUser();
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--post-single';
    }

}