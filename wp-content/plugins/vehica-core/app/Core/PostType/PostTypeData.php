<?php

namespace Vehica\Core\PostType;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PostTypeData
 * @package Vehica\Core\PostType
 */
class PostTypeData
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $postClass;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $singularName;

    /**
     * @var bool
     */
    protected $isCustom;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $taxonomies;

    /**
     * CustomFieldType constructor.
     * @param string $key
     * @param string $postClass
     * @param string $name
     * @param string $singularNme
     * @param bool $isCustom
     * @param array $options
     * @param array $taxonomies
     */
    public function __construct(
        $key,
        $postClass,
        $name,
        $singularNme,
        $isCustom,
        $options = [],
        $taxonomies = []
    ) {
        $this->key = $key;
        $this->postClass = $postClass;
        $this->name = $name;
        $this->singularName = $singularNme;
        $this->isCustom = $isCustom;
        $this->options = $options;
        $this->taxonomies = $taxonomies;
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
    public function getPostClass()
    {
        return $this->postClass;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSingularName()
    {
        return $this->singularName;
    }

    /**
     * @return bool
     */
    public function isCustom()
    {
        return $this->isCustom;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return array
     */
    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

}