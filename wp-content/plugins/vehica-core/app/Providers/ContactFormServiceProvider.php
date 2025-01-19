<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Post;

/**
 * Class ContactFormServiceProvider
 * @package Vehica\Providers
 */
class ContactFormServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('contact_forms', static function () {
            $query = new \WP_Query([
                'post_type' => 'wpcf7_contact_form',
                'limit' => -1
            ]);

            return Collection::make($query->posts)->map(static function ($wpPost) {
                return new Post($wpPost);
            });
        });

        $this->app->bind('contact_forms_list', static function () {
            $list = [];
            vehicaApp('contact_forms')->each(static function ($contactForm) use (&$list) {
                /* @var Post $contactForm */
                $list[$contactForm->getId()] = $contactForm->getName();
            });
            return $list;
        });
    }

}