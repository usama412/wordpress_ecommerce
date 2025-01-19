<?php

namespace Vehica\Attribute;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Term\Term;

/**
 * Class AttributeValue
 * @package Vehica\Attribute
 */
class AttributeValue
{
    /**
     * @var string
     */
    public $value;

    /**
     * @var string
     */
    public $display;

    /**
     * @var string
     */
    public $link;

    public function __construct($value, $display, $link = false)
    {
        $this->value = $value;
        $this->display = $display;
        $this->link = $link;
    }

    /**
     * @return bool
     */
    public function isLink()
    {
        return $this->link !== false;
    }

    /**
     * @param string $value
     * @param string $display
     * @param bool $link
     * @return AttributeValue
     */
    public static function make($value, $display, $link = false)
    {
        return new self($value, $display, $link);
    }

    /**
     * @param Term $term
     * @return AttributeValue
     */
    public static function makeFromTerm(Term $term)
    {
        return self::make($term->getSlug(), $term->getName(), $term->getLink());
    }

}