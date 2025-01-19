<?php

namespace Vehica\Core\Model\Interfaces;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Interface Listable
 * @package Vehica\Core\Model\Interfaces
 */
interface Listable
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

}