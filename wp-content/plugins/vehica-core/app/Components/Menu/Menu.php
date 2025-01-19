<?php

namespace Vehica\Components\Menu;


/**
 * Class Menu
 * @package Vehica\Components
 */
class Menu
{
    /**
     * @var int
     */
    protected $menuId;

    /**
     * Menu constructor.
     * @param int $menuId
     */
    public function __construct($menuId)
    {
        $this->menuId = $menuId;
    }

    /**
     * @param string $id
     */
    public function display($id = 'vehica-menu')
    {
        wp_nav_menu([
            'menu' => $this->menuId,
            'container' => 'div',
            'container_class' => 'vehica-menu',
            'container_id' => $id,
            'walker' => new MenuWalker(),
            'items_wrap' => '%3$s',
            'depth' => 4,
        ]);
    }

}