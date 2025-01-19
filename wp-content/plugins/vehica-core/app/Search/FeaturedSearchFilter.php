<?php


namespace Vehica\Search;


use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use WP_Query;

/**
 * Class FeaturedSearchFilter
 * @package Vehica\Search
 */
class FeaturedSearchFilter implements SearchFilter
{
    /**
     * @return string
     */
    private function getRewrite()
    {
        return vehicaApp('featured_rewrite');
    }

    /**
     * @param array $parameters
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        $rewrite = $this->getRewrite();

        if (!isset($parameters[$rewrite]) || empty($parameters[$rewrite])) {
            return false;
        }

        $query = new WP_Query([
            'post_type' => Car::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => '-1',
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'vehica_featured',
                    'value' => '1'
                ]
            ]
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