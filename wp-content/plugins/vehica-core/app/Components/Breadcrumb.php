<?php


namespace Vehica\Components;


/**
 * Class Breadcrumb
 * @package Vehica\Components
 */
class Breadcrumb
{
    const LAST = true;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var bool
     */
    private $isLast;

    public function __construct($name, $url, $isLast = false)
    {
        $this->name = $name;
        $this->url = $url;
        $this->isLast = $isLast;
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
    public function getUrl()
    {
        return trim($this->url, '?');
    }

    /**
     * @return bool
     */
    public function isLast()
    {
        return $this->isLast;
    }

    public function setLast()
    {
        $this->isLast = true;
    }

}