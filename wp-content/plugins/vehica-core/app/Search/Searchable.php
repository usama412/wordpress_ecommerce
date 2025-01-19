<?php

namespace Vehica\Search;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Search\Field\SearchField;

/**
 * Interface Searchable
 * @package Vehica\Search
 */
interface Searchable
{
    /**
     * @param array $config
     * @return SearchField
     */
    public function getSearchField($config);

}