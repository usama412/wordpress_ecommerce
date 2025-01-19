<?php

namespace Vehica\Widgets\Controls;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Base_Data_Control;

/**
 * Class SelectTermControl
 * @package Vehica\Widgets\Controls
 */
class SelectRemoteControl extends Base_Data_Control
{
    const TYPE = 'vehica_select_remote';

    /**
     * @return string
     */
    public function get_type()
    {
        return self::TYPE;
    }

    public function enqueue()
    {
        wp_register_script(
            'da-select-remote-control',
            vehicaApp('assets_js') . 'elements/controls/select-remote-control.js',
            ['jquery'],
            '1.0.0'
        );
        wp_enqueue_script('da-select-remote-control');
    }

    /**
     * @return array
     */
    protected function get_default_settings()
    {
        return [
            'options' => [],
            'multiple' => false,
            'source' => '',
            'placeholder' => esc_html__('Select', 'vehica-core')
        ];
    }

    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <div class="elementor-control-field">
            <label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">
                {{{ data.label }}}
            </label>

            <div class="elementor-control-input-wrapper">
                <# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
                <select
                        id="<?php echo esc_attr($control_uid); ?>"
                        type="vehica_select_remote"
                        data-setting="{{ data.name }}"
                        data-selected="{{ data.controlValue }}"
                        data-source="{{ data.source }}"
                        data-placeholder="{{ data.placeholder }}"
                        {{ multiple }}
                >
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
        <?php
    }

    /**
     * @return array
     */
    public function get_default_value()
    {
        return [];
    }

}