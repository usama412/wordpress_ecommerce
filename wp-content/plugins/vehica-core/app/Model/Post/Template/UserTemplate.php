<?php

namespace Vehica\Model\Post\Template;


use Vehica\Model\User\User;
use Vehica\Widgets\WidgetCategory;

/**
 * Class UserTemplate
 * @package Vehica\Model\Post\Template
 */
class UserTemplate extends Template
{
    const PREVIEW_USER = 'vehica_user_preview';

    public function preparePreview()
    {
        $previewUserId = (int)$this->document->get_settings(self::PREVIEW_USER);

        global $vehicaUser;
        $vehicaUser = User::getById($previewUserId);

        if (!$vehicaUser instanceof User) {
            $vehicaUser = User::first();
        }
    }

    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return WidgetCategory::USER;
    }

    public function prepare()
    {
        $this->setMeta('_elementor_edit_mode', 'builder');
    }

}