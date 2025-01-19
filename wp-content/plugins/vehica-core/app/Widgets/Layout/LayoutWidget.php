<?php

namespace Vehica\Widgets\Layout;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;
use \Vehica\Model\Post\Template\Template;

/**
 * Class LayoutElement
 * @package Vehica\Widgets\Core
 */
abstract class LayoutWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        if (!is_singular(Template::POST_TYPE)) {
            return [];
        }

        return [
            WidgetCategory::LAYOUT
        ];
    }

}