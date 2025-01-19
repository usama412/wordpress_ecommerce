<?php


namespace Vehica\Managers;


use Vehica\Core\BackgroundAccentUser;
use Vehica\Core\Manager;
use Vehica\Model\Post\Page;

/**
 * Class BackgroundAccentManager
 * @package Vehica\Managers
 */
class BackgroundAccentManager extends Manager
{

    public function boot()
    {
        add_action('wp_footer', [$this, 'backgroundAccent']);
    }

    public function backgroundAccent()
    {
        $backgroundAccentUser = $this->getBackgroundAccentUser();
        if (!$backgroundAccentUser || !$backgroundAccentUser->hasBackgroundAccent()) {
            return;
        }

        get_template_part('templates/backgroundAccent/' . $backgroundAccentUser->getBackgroundAccent());
    }

    /**
     * @return BackgroundAccentUser|false
     */
    private function getBackgroundAccentUser()
    {
        global $vehicaTemplate;
        if ($vehicaTemplate) {
            return $vehicaTemplate;
        }

        if (is_page()) {
            global $post;

            return new Page($post);
        }

        return false;
    }

}