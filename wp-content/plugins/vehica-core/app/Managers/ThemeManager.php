<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use WP_Theme;

/**
 * Class ThemeManager
 * @package Vehica\Managers
 */
class ThemeManager extends Manager
{

    public function boot()
    {
        add_action('switch_theme', static function ($newName, WP_Theme $newTheme, WP_Theme $oldTheme) {
            if (!$newTheme->parent()) {
                return;
            }

            $mods = get_option('theme_mods_' . $oldTheme->get('Name'));
            if (!$mods) {
                return;
            }

            foreach ((array)$mods as $mod => $value) {
                if ($mod !== 'sidebars_widgets') {
                    set_theme_mod($mod, $value);
                }
            }
        }, 10, 3);
    }

}