<?php

namespace Vehica\Core\Model\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;

/**
 * Interface FieldsUser
 * @package Vehica\Core\Model\Field;
 */
interface FieldsUser
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getId();

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setMeta($key, $value);

    /**
     * @param string $key
     * @return mixed
     */
    public function getMeta($key);

    /**
     * @return Collection
     */
    public function getAttributes();

}