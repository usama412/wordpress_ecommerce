<?php

namespace Vehica\Widgets\Post\Archive;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;

/**
 * Class PostArchiveWidget
 * @package Vehica\Widgets\Post\Archive
 */
abstract class PostArchiveWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::POST_ARCHIVE
        ];
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--post-archive';
    }

}