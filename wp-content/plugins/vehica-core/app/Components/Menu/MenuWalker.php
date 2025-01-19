<?php

namespace Vehica\Components\Menu;


/**
 * Class MenuWalker
 */
class MenuWalker extends \Walker_Nav_Menu
{
    public static $counter = 0;

    public function __construct()
    {
        self::$counter++;
    }

    /**
     * @param string $output
     * @param \WP_Post $item
     * @param int $depth
     * @param array $args
     * @param int $id
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        global $vehicaMenuElement;
        $vehicaMenuElement = new MenuElement($item, $depth, self::$counter);
        ob_start();
        get_template_part('templates/general/menu/item', 'start');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param \WP_Post $item
     * @param int $depth
     * @param array $args
     */
    public function end_el(&$output, $item, $depth = 0, $args = [])
    {
        global $vehicaMenuElement;
        $vehicaMenuElement = new MenuElement($item, $depth);
        ob_start();
        get_template_part('templates/general/menu/item', 'end');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param int $depth
     * @param array $args
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        global $vehicaMenuLevel;
        $vehicaMenuLevel = new MenuLevel($depth);
        ob_start();
        get_template_part('templates/general/menu/level', 'start');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param int $depth
     * @param array $args
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        global $vehicaMenuLevel;
        $vehicaMenuLevel = new MenuLevel($depth);
        ob_start();
        get_template_part('templates/general/menu/level', 'end');
        $output .= ob_get_clean();
    }

}