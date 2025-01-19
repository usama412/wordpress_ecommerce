<?php

namespace Vehica\Core\PostType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface PostTypable
 * @package Vehica\Core\PostType
 */
interface PostTypable
{
    /**
     * @return PostTypeData
     */
    public function getPostTypeData();

}