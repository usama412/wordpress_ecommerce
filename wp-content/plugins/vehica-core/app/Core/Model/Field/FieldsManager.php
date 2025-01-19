<?php

namespace Vehica\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\Attribute;
use Vehica\Core\Collection;

/**
 * Interface CustomFieldsManager
 * @package Vehica\Field
 */
interface FieldsManager
{
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
     * @param array $fields
     */
    public function setFields($fields);

    /**
     * @return array
     */
    public function getFieldSections();

    /**
     * @return Collection
     */
    public function getAttributeIds();

    /**
     * @return Collection
     */
    public function getAttributes();

    /**
     * @return Collection
     */
    public function getFields();

    /**
     * @return bool
     */
    public function hasFields();

    /**
     * @return bool
     */
    public function supportTaxonomies();

    /**
     * @param $id
     * @return Attribute|false
     */
    public static function getAttribute($id);
}