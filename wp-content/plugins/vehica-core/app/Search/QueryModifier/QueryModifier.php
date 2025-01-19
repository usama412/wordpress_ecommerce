<?php

namespace Vehica\Search\QueryModifier;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface QueryModifier
 * @package Vehica\Search\QueryModifier
 */
interface QueryModifier
{
    /**
     * @param array $queryParams
     * @param array $requestParams
     * @return array
     */
    public function modifyQuery($queryParams, $requestParams = []);

}