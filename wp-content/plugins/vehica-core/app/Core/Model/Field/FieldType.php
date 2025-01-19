<?php

namespace Vehica\Field;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class FieldType
 * @package Vehica\Field
 */
class FieldType implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $name;

    /**
     * FieldType constructor.
     * @param string $class
     * @param string $key
     * @param string $name
     */
    public function __construct($class, $key, $name)
    {
        $this->class = $class;
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
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
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'key' => $this->key,
            'name' => $this->name,
        ];
    }

}