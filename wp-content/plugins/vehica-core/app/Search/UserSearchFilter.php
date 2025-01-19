<?php

namespace Vehica\Search;


use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;

/**
 * Class UserSearchFilter
 * @package Vehica\Search
 */
class UserSearchFilter implements SearchFilter
{
    /**
     * @param array $parameters
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        if (!isset($parameters['user_id'])) {
            return false;
        }

        $query = new \WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'author' => (int)$parameters['user_id']
        ]);

        return $query->posts;
    }

    /**
     * @param array $parameters
     * @return false
     */
    public function getInitialSearchParams($parameters)
    {
        return false;
    }

}