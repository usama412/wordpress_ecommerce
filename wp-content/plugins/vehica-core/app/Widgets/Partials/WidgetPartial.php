<?php

namespace Vehica\Widgets\Partials;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Trait ElementPartial
 * @package Vehica\Widgets\Partials
 */
trait WidgetPartial
{
    /**
     * @param string|null $key
     * @return array
     */
    abstract public function get_settings_for_display($key = null);

    /**
     * @param $element
     * @param string|null $key
     * @param string|null $value
     * @param bool $overwrite
     * @return mixed
     */
    abstract public function add_render_attribute($element, $key = null, $value = null, $overwrite = false);

    /**
     * @param string $section_id
     * @param array $args
     */
    abstract public function start_controls_section($section_id, array $args = []);

    /**
     * @param string $id
     * @param array $args
     * @param array $options
     * @return bool
     */
    abstract public function add_control($id, array $args, $options = []);

    /**
     * @param string $group_name
     * @param array $args
     * @param array $options
     */
    abstract public function add_group_control($group_name, array $args = [], array $options = []);

    /**
     * @param string $tabs_id
     * @param array $args
     */
    abstract public function start_controls_tabs($tabs_id, array $args = []);

    abstract public function end_controls_tabs();

    /**
     * @param string $tab_id
     * @param array $args
     */
    abstract public function start_controls_tab($tab_id, $args);

    abstract public function end_controls_tab();

    /**
     * @param string $id
     * @param array $args
     * @param array $options
     * @return mixed
     */
    abstract public function add_responsive_control($id, array $args, $options = []);

    abstract public function end_controls_section();

    abstract public function start_popover();

    abstract public function end_popover();

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     */
    abstract protected function addBorderRadiusControl($key, $selectors, $label = '', $condition = []);

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     * @param array $additionalSettings
     */
    abstract protected function addPaddingControl($key, $selectors, $label = '', $condition = [], $additionalSettings = []);

    /**
     * @param string $key
     * @param array|string $selectors
     * @param string $label
     * @param array $condition
     * @param array $settings
     */
    abstract protected function addMarginControl($key, $selectors, $label = '', $condition = [], $settings = []);

}