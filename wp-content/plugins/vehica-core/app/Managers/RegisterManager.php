<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Core\Notification;
use Vehica\Model\User\User;
use Vehica\Widgets\General\PanelGeneralWidget;

/**
 * Class RegisterManager
 *
 * @package Vehica\Managers
 */
class RegisterManager extends Manager
{
    const NAME = 'name';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const PHONE = 'phone';
    const NONCE = 'nonce';
    const ROLE = 'role';

    public function boot()
    {
        add_action('admin_post_nopriv_vehica_register', [$this, 'register']);

        add_action('admin_post_nopriv_vehica_user_confirmation', [$this, 'userConfirmation']);

        add_action('admin_post_nopriv_vehica_send_confirmation_mail', [$this, 'resendConfirmationMail']);

        add_filter('sanitize_user', static function ($username, $rawUserName, $strict) {
            if (!$strict) {
                return $username;
            }

            return sanitize_user(stripslashes($rawUserName), false);
        }, 10, 3);
    }

    /** @noinspection DuplicatedCode */
    public function register()
    {
        if (!vehicaApp('settings_config')->isUserRegisterEnabled()) {
            return;
        }

        if ($this->isReCaptchaEnabled() && !$this->verifyReCaptcha('register')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('something_went_wrong_string')
            ]);
            return;
        }

        if (!isset($_POST[self::NAME], $_POST[self::EMAIL], $_POST[self::PASSWORD], $_POST[self::NONCE])) {
            return;
        }

        if (empty($_POST[self::NAME]) || empty($_POST[self::EMAIL]) || empty($_POST[self::PASSWORD])) {
            return;
        }

        $name = sanitize_user(stripslashes($_POST[self::NAME]), false);
        $email = $_POST[self::EMAIL];
        $password = $_POST[self::PASSWORD];
        $nonce = $_POST[self::NONCE];

        if (mb_strlen($name, 'UTF-8') < 50 && mb_strlen(sanitize_title_with_dashes($name), 'UTF-8') > 50) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('user_name_too_long_if_you_used_special_chars_like_cyr_string')
            ]);

            return;
        }

        if (mb_strlen(sanitize_title_with_dashes($name), 'UTF-8') > 50) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('user_name_too_long_string')
            ]);

            return;
        }

        if (vehicaApp('settings_config')->getPanelPhoneNumber() !== 'disable') {
            if (isset($_POST[self::PHONE]) && !empty(self::PHONE)) {
                $phone = trim($_POST[self::PHONE]);
            } else {
                $phone = '';
            }

            if (empty($phone) && vehicaApp('settings_config')->getPanelPhoneNumber() === 'required') {
                echo json_encode([
                    'success' => false,
                    'message' => vehicaApp('something_went_wrong_string')
                ]);

                return;
            }
        }

        if (!wp_verify_nonce($nonce, 'vehica_register')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('nonce_error_string')
            ]);

            return;
        }

        remove_action('register_new_user', 'wp_send_new_user_notifications');

        $userId = register_new_user($name, $email);

        if (is_wp_error($userId)) {
            $errorCode = $userId->get_error_code();
            if ($errorCode === 'email_exists' || $errorCode === 'username_exists') {
                $message = vehicaApp('email_or_username_exists_string');
            } else {
                $message = vehicaApp('something_went_wrong_string');
            }

            echo json_encode([
                'success' => false,
                'message' => $message,
            ]);

            return;
        }

        do_action('vehica/user/created', User::getById($userId));

        update_user_meta($userId, 'vehica_source', 'panel');

        if (!empty($phone)) {
            update_user_meta($userId, User::PHONE, $phone);
        }

        wp_update_user([
            'display_name' => $name,
            'ID' => $userId,
            'role' => $this->getRole(),
        ]);

        wp_set_password($password, $userId);

        if (vehicaApp('is_user_confirmation_enabled')) {
            $this->sendConfirmationEmail($userId);

            echo json_encode([
                'success' => true,
                'message' => vehicaApp('check_email_confirmation_string'),
                'reload' => false,
            ]);

            return;
        }

        $user = User::getById($userId);

        do_action('vehica/notification/' . Notification::WELCOME_USER, $user);

        $user->login();

        echo json_encode([
            'success' => true,
            'reload' => true,
            'message' => ''
        ]);
    }

    public function resendConfirmationMail()
    {
        if ($this->isReCaptchaEnabled() && !$this->verifyReCaptcha('send_confirmation')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('something_went_wrong_string')
            ]);
        }

        if (!vehicaApp('settings_config')->isUserRegisterEnabled()) {
            return;
        }

        if (!isset($_POST['email'], $_POST['nonce'])) {
            return;
        }

        $email = $_POST['email'];
        $nonce = $_POST['nonce'];

        if (empty($email) || empty($nonce)) {
            return;
        }

        if (!wp_verify_nonce($nonce, 'vehica_send_confirmation_mail')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('nonce_error_string')
            ]);

            return;
        }

        $user = User::getUserByEmail($email);

        if (!$user) {
            echo json_encode([
                'success' => true,
                'message' => vehicaApp('confirmation_link_sent_string')
            ]);

            return;
        }

        if ($user->isConfirmed()) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('user_already_confirmed_string')
            ]);

            return;
        }

        $this->sendConfirmationEmail($user->getId());

        echo json_encode([
            'success' => true,
            'message' => vehicaApp('confirmation_link_sent_string')
        ]);
    }

    /**
     * @param int $userId
     *
     * @return bool
     */
    public function sendConfirmationEmail($userId)
    {
        if (!vehicaApp('settings_config')->isUserRegisterEnabled()) {
            return false;
        }

        $user = User::getById($userId);

        if (!$user instanceof User) {
            return false;
        }

        do_action('vehica/notification/' . Notification::MAIL_CONFIRMATION, $user);

        return true;
    }

    public function userConfirmation()
    {
        if (!isset($_GET['selector'], $_GET['validator'])) {
            return;
        }

        $selector = $_GET['selector'];
        $validator = $_GET['validator'];

        $userId = User::verifyConfirmation($selector, $validator);
        if (!$userId) {
            wp_redirect(vehicaApp('settings_config')->getLoginPageUrl());
            exit;
        }

        $user = User::getById($userId);

        $user->setConfirmed();

        $user->login();

        do_action('vehica/notification/' . Notification::WELCOME_USER, $user);

        wp_redirect(apply_filters('vehica/user/confirmation/redirectUrl', PanelGeneralWidget::getCreateCarPageUrl()));
        exit;
    }

    /**
     * @return string
     */
    private function getRole()
    {
        if (vehicaApp('user_role_mode') === 'hidden_private') {
            return vehicaApp('private_user_role');
        }

        if (vehicaApp('user_role_mode') === 'hidden_business') {
            return vehicaApp('business_user_role');
        }

        if (!isset($_POST[self::ROLE])) {
            return vehicaApp('private_user_role');
        }

        if ($_POST[self::ROLE] === 'business') {
            return vehicaApp('business_user_role');
        }

        return vehicaApp('private_user_role');
    }

}