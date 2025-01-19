<?php

namespace Vehica\Core\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Model\Post\Field\Field;

/**
 * Class Attribute
 * @package Vehica\Core\Field
 */
class Attribute implements \JsonSerializable
{
    /**
     * @var Field
     */
    protected $field;

    /**
     * @var FieldsUser
     */
    protected $fieldsUser;

    /**
     * Attribute constructor.
     * @param Field $field
     * @param FieldsUser $fieldsUser
     */
    public function __construct(Field $field, FieldsUser $fieldsUser)
    {
        $this->field = $field;
        $this->fieldsUser = $fieldsUser;
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return FieldsUser
     */
    public function getFieldsUser()
    {
        return $this->fieldsUser;
    }

    /**
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $value = $this->field->getValue($this->fieldsUser);
        return array_merge([
            'id' => $this->field->getId(),
            'name' => $this->field->getName(),
            'type' => $this->field->getType(),
            'value' => $value,
            'displayValue' => $this->field->getDisplayValue($value)
        ], $this->getJsonData());
    }

    /**
     * @return Collection
     */
    public function getDisplayValues()
    {
        $value = $this->field->getValue($this->fieldsUser);

        if (empty($value)) {
            return Collection::make();
        }

        $displayValue = $this->field->getDisplayValue($value);

        if (!is_array($displayValue)) {
            $displayValue = [$displayValue];
        }

        return Collection::make($displayValue);
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [];
    }

}
