<?php

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Manager;
use Vehica\Model\Post\Page;
use WP_Query;

/**
 * Class PostArchiveManager
 * @package Vehica\Managers
 */
class PostArchiveManager extends Manager
{

    public function boot()
    {
        add_action('pre_get_posts', [$this, 'preparePosts']);
        add_action('template_redirect', [$this, 'checkBlog']);
    }

    public function checkBlog()
    {
        if (!is_home()) {
            return;
        }

        $role = get_query_var('author_role');
        if (empty($role)) {
            return;
        }

        $blog = vehicaApp('blog_page');
        if (!$blog instanceof Page) {
            return;
        }

        wp_redirect($blog->getUrl());
        exit;
    }

    /**
     * @param WP_Query $query
     */
    public function preparePosts($query)
    {
        if (!$query->is_main_query() || !$query->is_archive() || is_admin()) {
            return;
        }

        $query->set('posts_per_page', 20);
    }

}