<?php

namespace Vehica\Search\QueryModifier;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class LimitQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class LimitQueryModifier implements QueryModifier
{
    const PARAM = 'limit';

    /**
     * @param array $queryParams
     * @param array $requestParams
     * @return array
     */
    public function modifyQuery($queryParams, $requestParams = [])
    {
        if (!isset($requestParams[self::PARAM])) {
            $queryParams['posts_per_page'] = -1;
            return $queryParams;
        }

        $limit = (int)$requestParams[self::PARAM];
        if (!empty($limit)) {
            $queryParams['posts_per_page'] = $limit;
        }

        return $queryParams;
    }

}