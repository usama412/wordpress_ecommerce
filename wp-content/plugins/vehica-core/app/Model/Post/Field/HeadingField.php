<?php

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Model\Field\FieldsUser;

/**
 * Class HeadingField
 * @package Vehica\Model\Post\Field
 */
class HeadingField extends Field
{
    const KEY = 'heading';

    public function save(FieldsUser $fieldsUser, $value)
    {
    }

    public function getAttribute(FieldsUser $fieldsUser)
    {
        return false;
    }

    public function getValue(FieldsUser $fieldsUser)
    {
        return false;
    }

}