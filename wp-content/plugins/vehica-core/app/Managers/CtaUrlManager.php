<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Page;

/**
 * Class CtaUrlManager
 * @package Vehica\Managers
 */
class CtaUrlManager extends Manager
{

    public function boot()
    {
        add_filter('vehica/menu/submitButtonUrl', static function ($url) {
            if (empty(vehicaApp('settings_config')->getCtaPageId())) {
                return $url;
            }

            $page = Page::getById(vehicaApp('settings_config')->getCtaPageId());
            if (!$page) {
                return $url;
            }

            return $page->getUrl();
        });
    }

}