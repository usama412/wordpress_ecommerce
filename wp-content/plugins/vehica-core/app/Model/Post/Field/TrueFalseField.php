<?php

namespace Vehica\Model\Post\Field;


use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;

/**
 * Class TrueFalseField
 * @package Vehica\Model\Post\Field
 */
class TrueFalseField extends Field
{
    const KEY = 'true_false';
    const INITIAL_VALUE = 'vehica_initial_value';
    const TRUE = 1;
    const FALSE = 0;

    /**
     * @var array
     */
    protected $settings = [
        self::INITIAL_VALUE,
        self::NAME,
    ];

    /**
     * @param int $initialValue
     */
    public function setInitialValue($initialValue)
    {
        $initialValue = (int)$initialValue;
        $this->setMeta(self::INITIAL_VALUE, $initialValue);
    }

    /**
     * @return int
     */
    public function getInitialValue()
    {
        return (int)$this->getMeta(self::INITIAL_VALUE);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param mixed $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        $value = (int)$value;
        $fieldsUser->setMeta($this->getKey(), $value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Attribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new Attribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return bool
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        $value = $fieldsUser->getMeta($this->getKey());

        if ($value === '') {
            $value = $this->getInitialValue();
        }

        return !empty($value);
    }

    /**
     * @return bool
     */
    public function isInitialValueTrue()
    {
        return $this->getInitialValue() === self::TRUE;
    }

    /**
     * @return bool
     */
    public function isInitialValueFalse()
    {
        return $this->getInitialValue() === self::FALSE;
    }

}