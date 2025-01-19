<?php

namespace Vehica\Managers;

use Elementor\Plugin;
use Vehica\Core\Manager;

class UpdateManager extends Manager
{
    public function boot()
    {
        add_action('admin_init', [$this, 'afterUpdate']);
    }

    public function afterUpdate()
    {
        if (get_option('vehica_version') === VEHICA_VERSION) {
            return;
        }

        $this->fixElementor();

        do_action('vehica_flush_rewrites');

        update_option('vehica_version', VEHICA_VERSION);
    }

    private function fixElementor()
    {
        if (!class_exists(Plugin::class)) {
            return;
        }

        $kit = Plugin::instance()->kits_manager->get_active_kit_for_frontend();
        if (!$kit) {
            return;
        }

        $kit->set_settings('space_between_widgets', [
            "column" => "0",
            "row" => "0",
            "isLinked" => true,
            "unit" => "px",
            "size" => 0,
            "sizes" => []
        ]);

        $kit->save(['settings' => $kit->get_settings()]);
    }
}