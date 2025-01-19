<?php


namespace Vehica\Search\QueryModifier;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class FeaturedQueryModifier
 * @package Vehica\Search\QueryModifier
 */
class FeaturedQueryModifier implements QueryModifier
{
    const PARAM = 'featured';

    public function modifyQuery($queryParams, $requestParams = [])
    {
        if (!isset($requestParams[self::PARAM])) {
            return $queryParams;
        }


    }

}