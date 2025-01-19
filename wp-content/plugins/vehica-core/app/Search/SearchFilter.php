<?php

namespace Vehica\Search;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface SearchFilter
 * @package Vehica\Search
 */
interface SearchFilter
{
    /**
     * @param array $parameters
     * @return array|false
     */
    public function getSearchedCarsIds($parameters);

    /**
     * @param array $parameters
     * @return false
     */
    public function getInitialSearchParams($parameters);

}