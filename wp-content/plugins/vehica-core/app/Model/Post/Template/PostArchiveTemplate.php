<?php

namespace Vehica\Model\Post\Template;


use Vehica\Widgets\WidgetCategory;

/**
 * Class PostArchiveTemplate
 * @package Vehica\Model\Post\Template
 */
class PostArchiveTemplate extends Template
{
    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return WidgetCategory::POST_ARCHIVE;
    }

}