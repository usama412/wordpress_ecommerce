<?php

namespace Vehica\Widgets\User;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Template\UserTemplate;
use Vehica\Widgets\Widget;
use Vehica\Widgets\WidgetCategory;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\User\User;

/**
 * Class UserElement
 * @package Vehica\Widgets\User
 */
abstract class UserWidget extends Widget
{
    /**
     * @return array
     */
    public function get_categories()
    {
        return [
            WidgetCategory::USER
        ];
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        global $vehicaUser;

        if (!$vehicaUser instanceof User) {
            return false;
        }

        return $vehicaUser;
    }

    /**
     * @return void
     */
    protected function render()
    {
        $postType = get_post_type();
        if ($postType === Template::POST_TYPE || $postType === 'elementor_library') {
            $this->prepareUser();
        }
    }

    protected function prepareUser()
    {
        global $post;
        UserTemplate::get($post)->preparePreview();
    }

    /**
     * @return string
     */
    public function get_icon()
    {
        return 'eicon-vehica eicon-vehica--seller';
    }

}