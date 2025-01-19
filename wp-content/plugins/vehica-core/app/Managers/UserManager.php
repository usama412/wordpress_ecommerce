<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Model\User\User;
use Vehica\Widgets\General\PanelGeneralWidget;
use WP_User;

/**
 * Class UserManager
 * @package Vehica\Managers
 */
class UserManager extends Manager
{

    public function boot()
    {
        add_filter('rest_user_query', [$this, 'modifyRestQuery']);
        add_filter('wp_dropdown_users_args', [$this, 'selectPostAuthor']);

        add_action('user_edit_form_tag', static function () {
            ?>enctype="multipart/form-data"<?php
        });

        add_action('edit_user_profile', [$this, 'addStaticFields'], 9);
        add_action('show_user_profile', [$this, 'addStaticFields'], 9);

        add_action('edit_user_profile_update', [$this, 'saveStaticFields']);
        add_action('personal_options_update', [$this, 'saveStaticFields']);
        add_action('admin_post_vehica_instant_logout', [$this, 'logout']);

        add_action('admin_post_vehica/user/removeSocialImage', [$this, 'removeSocialImage']);
    }

    /**
     * @param array $prepared_args
     * @return array
     */
    public function modifyRestQuery($prepared_args)
    {
        unset($prepared_args['has_published_posts']);
        return $prepared_args;
    }

    /**
     * @param array $args
     * @return array
     */
    public function selectPostAuthor($args)
    {
        unset($args['capability']);
        return $args;
    }

    /**
     * @param WP_User $user
     */
    public function addStaticFields(WP_User $user)
    {
        $vehicaUser = new User($user);
        /** @noinspection PhpIncludeInspection */
        require vehicaApp('views_path') . 'forms/user_static_fields.php';
    }

    /**
     * @param int $userId
     */
    public function saveStaticFields($userId)
    {
        $wpUser = get_user_by('id', $userId);
        if (!$wpUser) {
            return;
        }

        $user = new User($wpUser);
        if (!$user) {
            return;
        }

        $user->updateStaticFields($_POST);
    }

    public function logout()
    {
        wp_logout();

        wp_redirect(vehicaApp('login_page_url'));
        exit;
    }

}