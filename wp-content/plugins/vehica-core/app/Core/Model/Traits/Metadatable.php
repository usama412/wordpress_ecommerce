<?php

namespace Vehica\Core\Model\Traits;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Trait Metadatable
 * @package Vehica\Core\Model\Traits
 */
trait Metadatable
{
    /**
     * @param string $key
     * @param bool $isSingle
     * @return mixed
     */
    abstract public function getMeta($key, $isSingle = true);

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    abstract public function setMeta($key, $value);

}