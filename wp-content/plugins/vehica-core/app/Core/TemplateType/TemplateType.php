<?php

namespace Vehica\Core\TemplateType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class TemplateType
 * @package Vehica\Core\TemplateType
 */
class TemplateType
{
    const KEY = 'vehica_template_type';

    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $name;

    /**
     * TemplateType constructor.
     * @param string $key
     * @param string $name
     */
    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $key
     * @param string $name
     * @return TemplateType
     */
    public static function make($key, $name)
    {
        return new static($key, $name);
    }

}