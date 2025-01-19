<?php

namespace Vehica\Components\Menu;


/**
 * Class MenuLevel
 * @package Vehica\Components\Menu
 */
class MenuLevel
{
    /**
     * @var int
     */
    protected $depth;

    /**
     * MenuLevel constructor.
     * @param int $depth
     */
    public function __construct($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        $classes = ['vehica-submenu vehica-submenu--level-' . $this->getDepth()];
        return implode(' ', $classes);
    }

}