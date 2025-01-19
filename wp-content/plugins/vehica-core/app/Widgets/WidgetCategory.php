<?php

namespace Vehica\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class ElementCategory
 * @package Vehica\Widgets
 */
class WidgetCategory
{
    const GENERAL = 'vehica_general';
    const POST_SINGLE = 'vehica_post_single';
    const POST_ARCHIVE = 'vehica_post_archive';
    const CAR_SINGLE = 'vehica_car_single';
    const CAR_ARCHIVE = 'vehica_car_archive';
    const USER = 'vehica_user';
    const LAYOUT = 'vehica_layout';
}