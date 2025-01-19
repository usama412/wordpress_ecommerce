<?php

namespace Vehica\Model\Post;


use Elementor\Plugin;
use Vehica\Core\BackgroundAccentUser;
use Vehica\Model\Post\Template\Layout;

/**
 * Class Page
 * @package Vehica\Model\Post
 */
class Page extends Post implements BackgroundAccentUser
{
    const POST_TYPE = 'page';
    const LAYOUT = 'vehica_layout';

    /**
     * @return int
     */
    public function getLayoutId()
    {
        $layout = $this->getLayout();

        if (!$layout instanceof Layout) {
            return 0;
        }

        return $layout->getId();
    }

    /**
     * @return Layout|false
     */
    public function getLayout()
    {
        $document = Plugin::instance()->documents->get($this->model->ID);
        if (!$document) {
            return vehicaApp('global_layout');
        }

        $layoutId = (int)$document->get_settings(self::LAYOUT);
        if (!$layoutId) {
            return vehicaApp('global_layout');
        }

        $layout = Layout::getById($layoutId);
        if (!$layout instanceof Layout) {
            return vehicaApp('global_layout');
        }

        return $layout;
    }

    /**
     * @return string
     */
    public function getBackgroundAccent()
    {
        $document = Plugin::instance()->documents->get($this->model->ID);
        if (!$document) {
            return 'dots';
        }

        return (string)$document->get_settings('vehica_background_accent');
    }

    /**
     * @return bool
     */
    public function isCompareEnabled()
    {
        $document = Plugin::instance()->documents->get($this->model->ID);
        if (!$document) {
            return false;
        }

        $enabled = (int)$document->get_settings('vehica_compare');
        return !empty($enabled);
    }

    /**
     * @return bool
     */
    public function hasBackgroundAccent()
    {
        $backgroundAccent = $this->getBackgroundAccent();

        return $backgroundAccent !== '' && $backgroundAccent !== 'none';
    }

    /**
     * @return string
     */
    public static function getApiEndpoint()
    {
        return get_rest_url(null, 'wp/v2/pages');
    }

}