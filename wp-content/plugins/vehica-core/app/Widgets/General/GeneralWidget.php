<?php

namespace Vehica\Widgets\General;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;

/**
 * Class GeneralElement
 * @package Vehica\Widgets\General
 */
abstract class GeneralWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::GENERAL
        ];
    }

}