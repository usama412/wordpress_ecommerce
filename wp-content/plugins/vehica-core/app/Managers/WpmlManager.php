<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Field\Field;

/**
 * Class WpmlManager
 * @package Vehica\Managers
 */
class WpmlManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        if (!function_exists('icl_object_id') || !current_user_can('manage_options')) {
            return;
        }

        vehicaApp('car_fields')->each(static function ($field) {
            /* @var Field $field */
            do_action('wpml_register_single_string', 'Custom field', $field->getName(), $field->getName());
        });
    }

}