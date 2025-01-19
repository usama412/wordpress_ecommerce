<?php

namespace Vehica\Attribute;

if ( ! defined('ABSPATH')) {
    exit;
}

/**
 * Class SimpleTextValue
 * @package Vehica\Attribute
 */
class SimpleTextValue
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string|false
     */
    public $link;

    /**
     * SimpleTextValue constructor.
     *
     * @param string $value
     * @param bool $link
     */
    public function __construct($value, $link = false)
    {
        $this->value = $value;
        $this->link  = $link;
    }

    /**
     * @return bool
     */
    public function isLink()
    {
        return $this->link ? true : false;
    }

    /**
     * @return bool|string
     */
    public function getLink()
    {
        return $this->link ?: '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

}