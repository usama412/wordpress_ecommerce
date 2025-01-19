<?php

namespace Vehica\Model\Post\Template;


use Vehica\Model\Post\Post;
use Vehica\Widgets\WidgetCategory;

/**
 * Class PostSingleTemplate
 * @package Vehica\Model\Post\Template
 */
class PostSingleTemplate extends Template
{
    const PREVIEW_POST = 'vehica_post_preview';

    public function preparePreview()
    {
        $previewPostId = (int)$this->document->get_settings(self::PREVIEW_POST);

        global $vehicaPost;
        $vehicaPost = Post::getById($previewPostId);

        if (!$vehicaPost instanceof Post) {
            $vehicaPost = Post::first();
        }
    }

    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return WidgetCategory::POST_SINGLE;
    }

}