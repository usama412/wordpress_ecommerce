<?php

namespace Vehica\Search;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class SearchControl
 * @package Vehica\Search
 */
class SearchControl
{
    const TYPE_SELECT = 'vehica_search_field_control_select';
    const TYPE_MULTI_SELECT = 'multi_select';
    const TYPE_SELECT_FROM_TO = 'select_from_to';
    const TYPE_SELECT_FROM = 'select_from';
    const TYPE_SELECT_TO = 'select_to';
    const TYPE_RADIO = 'radio';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_INPUT = 'text';
    const TYPE_INPUT_FROM_TO = 'text_from_to';
}