<?php

namespace Vehica\Widgets\Post\Archive;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class NamePostArchiveWidget
 * @package Vehica\Widgets\Post\Archive
 */
class NamePostArchiveWidget extends PostArchiveWidget
{
    const NAME = 'vehica_name_post_archive_widget';
    const TEMPLATE = 'post/archive/name';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Blog Page Name', 'vehica-core');
    }

    protected function render()
    {
        $this->loadTemplate();
    }

    /**
     * @return bool
     */
    public function isSearch()
    {
        return !empty($_GET['s']);
    }

    /**
     * @return string
     */
    public function getSearchQuery()
    {
        return (string)$_GET['s'];
    }

}