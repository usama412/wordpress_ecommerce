<?php

namespace Vehica\Core\Model\Traits;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Trait Keyable
 * @package Vehica\Core\Model\Traits
 */
trait Keyable
{
    /**
     * int
     */
    abstract public function getId();

    /**
     * @return string
     */
    public function getKey()
    {
        return vehicaApp('prefix') . $this->getId();
    }

}