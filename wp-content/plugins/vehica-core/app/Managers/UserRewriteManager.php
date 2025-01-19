<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class UserRewriteManager
 * @package Vehica\Managers
 */
class UserRewriteManager extends Manager
{

    public function boot()
    {
        add_filter('init', [$this, 'rewrite'], 20);

        add_filter('author_link', [$this, 'link'], 10, 2);
    }

    public function rewrite()
    {
        $userRoleRewrites = [
            vehicaApp('user_rewrite'),
            'author'
        ];

        add_rewrite_tag('%author_role%', '(' . implode('|', $userRoleRewrites) . ')');

        global $wp_rewrite;
        $wp_rewrite->author_base = '%author_role%';
    }

    /**
     * @param string $link
     * @return string
     */
    public function link($link)
    {
        return str_replace('%author_role%', vehicaApp('user_rewrite'), $link);
    }

}