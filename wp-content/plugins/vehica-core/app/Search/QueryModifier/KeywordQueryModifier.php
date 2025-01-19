<?php


namespace Vehica\Search\QueryModifier;


use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use WP_Query;

/**
 * Class KeywordQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class KeywordQueryModifier implements UrlModifier, SearchFilter
{
    const PARAM = 'keyword';

    /**
     * @param array $parameters
     *
     * @return false|string
     */
    public function getArchiveUrlPartial($parameters)
    {
        $rewrite = $this->getRewrite();

        if (empty($parameters[$rewrite])) {
            return false;
        }

        return $rewrite . '=' . urlencode($parameters[$rewrite]);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $rewrite = $this->getRewrite();

        if ( ! isset($_GET[$rewrite])) {
            return [];
        }

        return [
            self::PARAM => trim($_GET[$rewrite])
        ];
    }

    /**
     * @return string
     */
    public function getRewrite()
    {
        $rewrite = vehicaApp('keyword_rewrite');

        if (empty($rewrite)) {
            return 'keyword';
        }

        return $rewrite;
    }

    public function setRewrite($rewrite)
    {
        // TODO: Implement setRewrite() method.
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return 'vehica_keyword';
    }

    /**
     * @param array $parameters
     *
     * @return array|false
     */
    public function getSearchedCarsIds($parameters)
    {
        if ( ! isset($parameters[$this->getRewrite()])) {
            return false;
        }

        $query = new WP_Query([
            'post_type'              => Car::POST_TYPE,
            'post_status'            => PostStatus::PUBLISH,
            'posts_per_page'         => '-1',
            'fields'                 => 'ids',
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
            's'                      => $parameters[$this->getRewrite()]
        ]);

        return $query->posts;
    }

    /**
     * @param array $parameters
     *
     * @return array[]|false
     */
    public function getInitialSearchParams($parameters)
    {
        $rewrite = vehicaApp('keyword_rewrite');

        if ( ! isset($parameters[$rewrite])) {
            return false;
        }

        return [
            [
                'id'      => 'vehica_keyword',
                'key'     => $this->getKey(),
                'rewrite' => $this->getRewrite(),
                'name'    => 'Keyword',
                'values'  => [
                    [
                        'key'   => 'vehica_keyword',
                        'name'  => $parameters[$rewrite],
                        'value' => $parameters[$rewrite]
                    ]
                ],
                'type'    => 'keyword',
            ]
        ];
    }

}