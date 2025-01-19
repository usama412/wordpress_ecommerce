<?php

namespace Vehica\Search\QueryModifier;

use Vehica\Search\UrlModifier;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class OffsetQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class OffsetQueryModifier implements QueryModifier, UrlModifier
{
    const PARAM = 'offset';

    /**
     * @param array $queryParams
     * @param array $requestParams
     * @return array
     */
    public function modifyQuery($queryParams, $requestParams = [])
    {
        if (isset($requestParams[vehicaApp('page_rewrite')])) {
            $queryParams['paged'] = (int)$requestParams[vehicaApp('page_rewrite')];
            return $queryParams;
        }

        if (!isset($requestParams[self::PARAM])) {
            $queryParams['offset'] = 0;
            return $queryParams;
        }

        $offset = (int)$requestParams[self::PARAM];
        if (!empty($offset)) {
            $queryParams['offset'] = $offset;
        }

        return $queryParams;
    }

    /**
     * @param array $queryParams
     * @return int
     */
    private function getPageNumber($queryParams)
    {
        if (!isset($queryParams[self::PARAM], $queryParams[LimitQueryModifier::PARAM])) {
            return 1;
        }

        $limit = (int)$queryParams[LimitQueryModifier::PARAM];
        $offset = (int)$queryParams[self::PARAM];

        if (empty($offset)) {
            return 1;
        }

        return ceil($offset / $limit) + 1;
    }

    /**
     * @param array $parameters
     * @return false|string
     */
    public function getArchiveUrlPartial($parameters)
    {
        return vehicaApp('page_rewrite') . '=' . $this->getPageNumber($parameters);
    }

    /**
     * @return array
     */
    public function getSearchParamsFromUrl()
    {
        $rewrite = vehicaApp('page_rewrite');

        if (!isset($_GET[$rewrite])) {
            return [];
        }

        return [
            vehicaApp('page_rewrite') => trim($_GET[$rewrite])
        ];
    }

    /**
     * @return string
     */
    public function getRewrite()
    {
        $rewrite = vehicaApp('page_rewrite');

        if (!$rewrite) {
            return 'page';
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
        return 'vehica_page';
    }
}