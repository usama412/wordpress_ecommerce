<?php


namespace Vehica\Search\Field;


use Vehica\Model\Post\Field\DateTimeField;

/**
 * Class DateSearchField
 * @package Vehica\Search\Field
 */
class DateSearchField extends SearchField
{
    const TYPE = 'date';
    const PLACEHOLDER_FROM = 'date_placeholder_from';
    const PLACEHOLDER_TO = 'date_placeholder_to';

    /**
     * @var DateTimeField
     */
    protected $searchable;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->searchable->getKey();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->searchable->getName();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE;
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'id' => $this->searchable->getId(),
            'rewrite' => $this->searchable->getRewrite(),
        ];
    }

    /**
     * @return string
     */
    public function getPlaceholderFrom()
    {
        if (!empty($this->config[self::PLACEHOLDER_FROM])) {
            return $this->config[self::PLACEHOLDER_FROM];
        }

        return $this->getName() . ' ' . vehicaApp('from_string');
    }


    /**
     * @return string
     */
    public function getPlaceholderTo()
    {
        if (!empty($this->config[self::PLACEHOLDER_TO])) {
            return $this->config[self::PLACEHOLDER_TO];
        }

        return $this->getName() . ' ' . vehicaApp('to_string');
    }

}