<?php
/*
Plugin Name: Vehica Core
Version: 1.0.90
Plugin URI: https://tangibledesign.net
Text Domain: vehica-core
Domain Path: /languages
Description: This plugin is a part of Vehica Theme.
Author: TangibleDesign
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

add_action('plugins_loaded', static function () {
    load_plugin_textdomain('vehica-core', false, basename(__DIR__) . '/languages/');

    require_once __DIR__ . '/bootstrap/app.php';
}, 0);

register_activation_hook(WP_PLUGIN_DIR . '/elementor/elementor.php', static function () {
    update_option('vehica_reset_rewrites', 1);
});

register_activation_hook(__FILE__, static function () {
    if (empty(get_option('vehica_demo'))) {
        update_option('vehica_welcome', 1);
    }
});