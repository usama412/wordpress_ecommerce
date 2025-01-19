<?php

namespace Vehica\Attribute;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Field\FieldsUser;

/**
 * Interface SimpleTextAttribute
 * @package Vehica\Attribute
 */
interface SimpleTextAttribute
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
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getSimpleTextValues(FieldsUser $fieldsUser);

}