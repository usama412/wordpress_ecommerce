<?php


namespace Vehica\Managers;


use Hybridauth\Exception\Exception;
use Hybridauth\Provider\Facebook;
use Hybridauth\Provider\Google;
use Hybridauth\User\Profile;
use Vehica\Core\Manager;
use Vehica\Model\User\User;
use Vehica\Widgets\General\PanelGeneralWidget;

/**
 * Class SocialAuthManager
 * @package Vehica\Managers
 */
class SocialAuthManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_nopriv_vehica/socialAuth/google', [$this, 'google']);

        add_action('admin_post_nopriv_vehica/socialAuth/facebook', [$this, 'facebook']);

        add_action('init', static function () {
            add_rewrite_rule('social-auth/([a-z0-9-]+)[/]?$', 'index.php?social_auth=$matches[1]', 'top');
        });

        add_filter('query_vars', static function ($vars) {
            $vars[] = 'social_auth';
            return $vars;
        });

        add_action('template_redirect', function () {
            if (!get_query_var('social_auth')) {
                return;
            }

            if (get_query_var('social_auth') === 'google') {
                $this->google();
            }

            if (get_query_var('social_auth') === 'facebook') {
                $this->facebook();
            }

            exit;
        });
    }

    /** @noinspection DuplicatedCode */
    public function facebook()
    {
        $adapter = new Facebook([
            'callback' => site_url() . '/social-auth/facebook/',
            'keys' => [
                'id' => vehicaApp('settings_config')->getFacebookAppId(),
                'secret' => vehicaApp('settings_config')->getFacebookAppSecret()
            ],
            'photo_size' => 400,
        ]);

        try {
            $adapter->authenticate();
            $profile = $adapter->getUserProfile();
        } catch (Exception $e) {
            try {
                $adapter->setAccessToken(null);
                $adapter->authenticate();
                $profile = $adapter->getUserProfile();
            } catch (Exception $e) {
                wp_redirect(vehicaApp('login_page_url'));
                exit;
            }
        }

        if (!$profile) {
            wp_redirect(vehicaApp('login_page_url'));
            exit;
        }

        $this->auth($profile);

        $adapter->disconnect();
    }

    /**
     * @param User $user
     * @param Profile $profile
     */
    private function setUserImage(User $user, Profile $profile)
    {
        if (!empty($profile->photoURL)) {
            $user->setSocialImage($profile->photoURL);
        }
    }

    /**
     * @param Profile $profile
     */
    private function auth(Profile $profile)
    {
        if (empty($profile->emailVerified)) {
            wp_redirect(vehicaApp('login_page_url'));
            exit;
        }

        $user = User::getUserByEmail($profile->emailVerified);
        if ($user) {
            $user->login();

            wp_redirect(apply_filters('vehica/socialAuth/redirectUrl', PanelGeneralWidget::getCarListPageUrl()));
            exit;
        }

        $userId = wp_create_user(
            $profile->displayName,
            substr(md5(mt_rand()), 0, 7),
            $profile->emailVerified
        );

        if (is_wp_error($userId)) {
            wp_redirect(vehicaApp('login_page_url'));
            exit;
        }

        $user = User::getById($userId);
        if (!$user) {
            wp_redirect(vehicaApp('login_page_url'));
            exit;
        }

        do_action('vehica/user/created', $user);

        if (!empty($profile->phone)) {
            $user->setPhone($profile->phone);
        }

        $user->setConfirmed();

        $user->setRegisterSource(User::REGISTER_SOURCE_SOCIAL);

        $user->setRole($this->getUserRole());

        $this->setUserImage($user, $profile);

        update_user_meta($userId, 'vehica_source', 'panel');

        $user->login();

        wp_redirect(apply_filters('vehica/socialAuth/registered/redirectUrl', apply_filters('vehica/socialAuth/redirectUrl', PanelGeneralWidget::getCarListPageUrl())));
        exit;
    }

    /**
     * @return string
     */
    private function getUserRole()
    {
        if (vehicaApp('user_role_mode') === 'hidden_private') {
            return vehicaApp('private_user_role');
        }

        if (vehicaApp('user_role_mode') === 'hidden_business') {
            return vehicaApp('business_user_role');
        }

        return vehicaApp('private_user_role');
    }

    /** @noinspection DuplicatedCode */
    public function google()
    {
        $adapter = new Google($this->getGoogleConfig());

        try {
            $adapter->authenticate();
            $profile = $adapter->getUserProfile();
        } catch (Exception $e) {
            try {
                $adapter->setAccessToken(null);
                $adapter->authenticate();
                $profile = $adapter->getUserProfile();
            } catch (Exception $e) {
                wp_redirect(vehicaApp('login_page_url'));
                exit;
            }
        }

        if (!$profile) {
            wp_redirect(vehicaApp('login_page_url'));
            exit;
        }

        $this->auth($profile);
    }

    /**
     * @return array
     */
    private function getGoogleConfig()
    {
        return [
            'callback' => site_url() . '/social-auth/google/',
            'keys' => [
                'id' => vehicaApp('settings_config')->getGoogleAuthClientId(),
                'secret' => vehicaApp('settings_config')->getGoogleAuthClientSecret()
            ],
            'scope' => 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
        ];
    }

}