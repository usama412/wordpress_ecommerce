<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Core\Notification;
use Vehica\Model\User\User;

/**
 * Class LoginManager
 *
 * @package Vehica\Managers
 */
class LoginManager extends Manager
{
    const LOGIN = 'login';
    const NONCE = 'nonce';
    const PASSWORD = 'password';
    const REMEMBER = 'remember';
    const EMAIL = 'email';

    public function boot()
    {
        add_action('admin_post_nopriv_vehica_login', [$this, 'login']);

        add_action('admin_post_vehica_login', [$this, 'login']);

        add_action('admin_post_vehica_logout', [$this, 'logout']);

        add_action('admin_post_nopriv_vehica_send_reset_password', [$this, 'sendResetPasswordMail']);

        add_action('admin_post_vehica_send_reset_password', [$this, 'sendResetPasswordMail']);

        add_action('admin_post_nopriv_vehica_set_password', [$this, 'setPassword']);

        add_action('wp_footer', [$this, 'loginModal']);
    }

    public function loginModal()
    {
        if (!is_user_logged_in()) {
            get_template_part('templates/login_modal');
        }
    }

    public function login()
    {
        if ($this->isReCaptchaEnabled() && !$this->verifyReCaptcha('login')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('something_went_wrong_string')
            ]);
            return;
        }

        if (!isset($_POST[self::LOGIN], $_POST[self::PASSWORD], $_POST[self::NONCE])) {
            return;
        }

        if (empty($_POST[self::LOGIN]) || empty($_POST[self::PASSWORD])) {
            return;
        }

        $login = $_POST[self::LOGIN];
        $password = $_POST[self::PASSWORD];
        $remember = !empty($_POST[self::REMEMBER]);
        $nonce = $_POST[self::NONCE];

        if (!wp_verify_nonce($nonce, 'vehica_login')) {
            echo json_encode([
                'success' => false,
                'message' => false
            ]);

            return;
        }

        $user = wp_signon([
            'user_login' => $login,
            'user_password' => $password,
            'remember' => $remember
        ]);

        if (is_wp_error($user)) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('invalid_login_or_password_string')
            ]);

            return;
        }

        if (vehicaApp('is_user_confirmation_enabled')) {
            $vehicaUser = new User($user);
            if (!$vehicaUser->isConfirmed() && !$vehicaUser->isAdmin()) {
                wp_logout();

                echo json_encode([
                    'success' => false,
                    'message' => '',
                    'confirmationRequired' => true
                ]);

                return;
            }
        }

        do_action('vehica/userLogged');

        echo json_encode([
            'success' => true
        ]);
    }

    public function logout()
    {
        if (!wp_verify_nonce($_POST['nonce'], 'vehica_logout')) {
            return;
        }

        wp_logout();

        echo json_encode(['success' => true]);
    }

    public function sendResetPasswordMail()
    {
        if ($this->isReCaptchaEnabled() && !$this->verifyReCaptcha('send_reset_password')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('something_went_wrong_string')
            ]);
            return;
        }

        if (!isset($_POST['nonce'], $_POST[self::EMAIL])) {
            return;
        }

        $nonce = (string)$_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'vehica_send_reset_password_link')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('nonce_error_string')
            ]);

            return;
        }

        $email = (string)$_POST[self::EMAIL];
        $user = User::getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                'success' => true,
                'message' => vehicaApp('reset_password_link_sent_string')
            ]);

            return;
        }

        do_action('vehica/notification/' . Notification::RESET_PASSWORD, $user, $user->getResetPasswordLink());

        echo json_encode([
            'success' => true,
            'message' => vehicaApp('reset_password_link_sent_string')
        ]);
    }

    public function setPassword()
    {
        if (!isset($_POST['selector'], $_POST['validator'], $_POST['nonce'], $_POST['password'])) {
            return;
        }

        $password = $_POST['password'];
        $selector = $_POST['selector'];
        $validator = $_POST['validator'];
        $nonce = $_POST['nonce'];

        if (empty($selector) || empty($validator) || empty($nonce) || empty($password)) {
            return;
        }

        if (!wp_verify_nonce($nonce, 'vehica_set_password')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('nonce_error_string')
            ]);

            return;
        }

        $userId = User::verifyResetPasswordToken($selector, $validator);
        if (!$userId) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('reset_password_link_invalid_or_expired_string')
            ]);

            return;
        }

        wp_set_password($password, $userId);

        $user = User::getById($userId);
        $user->clearResetPasswordTokenData();
        $user->setRegisterSource(User::REGISTER_SOURCE_REGULAR);

        echo json_encode([
            'success' => true,
            'message' => vehicaApp('password_changed_string')
        ]);
    }

}