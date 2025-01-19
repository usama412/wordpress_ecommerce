<?php

namespace Vehica\Search;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface UrlModifier
 * @package Vehica\Search
 */
interface UrlModifier
{
    /**
     * @param array $parameters
     * @return string|false
     */
    public function getArchiveUrlPartial($parameters);

    /**
     * @return array
     */
    public function getSearchParamsFromUrl();

    /**
     * @return string
     */
    public function getRewrite();

    /**
     * @param string $rewrite
     */
    public function setRewrite($rewrite);

    /**
     * @return string
     */
    public function getKey();

}