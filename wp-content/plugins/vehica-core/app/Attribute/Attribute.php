<?php

namespace Vehica\Attribute;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Field\FieldsUser;

/**
 * Interface Attribute
 * @package Vehica\Attribute
 */
interface Attribute
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getKey();

    /**
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getAttributeValues(FieldsUser $fieldsUser);

}