<?php

namespace Vehica\Widgets\Car\Archive;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;

/**
 * Class ArchiveElement
 * @package Vehica\Widgets\Car\Archive
 */
abstract class CarArchiveWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::CAR_ARCHIVE
        ];
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--car-archive';
    }

}