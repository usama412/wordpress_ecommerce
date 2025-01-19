<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\User\User;
use Vehica\Widgets\General\PanelGeneralWidget;
use WP_User;

/**
 * Class FrontendPanelManager
 *
 * @package Vehica\Managers
 */
class FrontendPanelManager extends Manager
{

    public function boot()
    {
        add_action('after_setup_theme', static function () {
            if (!current_user_can('administrator') && !is_admin()) {
                show_admin_bar(false);
            }
        });

        add_action('template_redirect', [$this, 'panelRoute']);

        add_action('admin_post_vehica_change_password', [$this, 'changePassword']);
        add_action('admin_post_logout', [$this, 'logout']);
        add_action('template_redirect', [$this, 'checkIfUserLogged'], 100);

        add_action('admin_post_vehica_save_account_details', [$this, 'saveAccountDetails']);
        add_action('admin_post_vehica_save_account_social', [$this, 'saveAccountSocial']);
        add_action('admin_post_vehica_save_account_password', [$this, 'saveAccountPassword']);
        add_action('admin_post_vehica_save_account_image', [$this, 'saveAccountImage']);
        add_action('admin_post_vehica_delete_account_image', [$this, 'deleteAccountImage']);
        add_action('admin_post_vehica_delete_account', [$this, 'deleteAccount']);
    }

    public function panelRoute()
    {
        if (!is_page()) {
            return;
        }

        global $post;

        if (vehicaApp('panel_page_id') !== $post->ID || !is_user_logged_in()) {
            return;
        }

        if (empty($_GET[PanelGeneralWidget::ACTION_TYPE]) || !in_array($_GET[PanelGeneralWidget::ACTION_TYPE], [
                PanelGeneralWidget::ACTION_TYPE_CREATE_CAR,
                PanelGeneralWidget::ACTION_TYPE_EDIT_CAR,
                PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
            ], true)) {
            return;
        }

        if (vehicaApp('current_user')->canCreateCars()) {
            return;
        }

        wp_redirect(vehicaApp('show_favorite') ? PanelGeneralWidget::getFavoritePageUrl() : PanelGeneralWidget::getAccountPageUrl());
        exit;
    }

    public function deleteAccountImage()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_delete_account_image')) {
            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            return;
        }

        if ($user->hasSocialImage()) {
            $user->setSocialImage('');
        } else {
            $user->setImage(0);
        }
    }

    public function saveAccountImage()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_save_account_image')) {
            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            return;
        }

        $imageId = UploadImageManager::uploadImage();
        $user->setImage($imageId);

        update_post_meta($imageId, 'type', 'user_profile');

        echo json_encode([
            'id' => $imageId,
            'url' => $user->getImageUrl()
        ]);
    }

    public function saveAccountPassword()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_save_account_password')) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        if (empty($_POST['oldPassword']) || empty($_POST['newPassword'])) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            return;
        }

        $oldPassword = (string)trim($_POST['oldPassword']);
        $newPassword = (string)trim($_POST['newPassword']);

        if (!$user->checkPassword($oldPassword)) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        if (!$user->setPassword($newPassword)) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        $user->login();

        echo json_encode([
            'success' => true
        ]);
    }

    public function saveAccountSocial()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_save_account_social')) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            echo json_encode([
                'success' => false
            ]);

            return;
        }

        $user->updateSocialProfiles($_POST);

        echo json_encode([
            'success' => true
        ]);
    }

    public function saveAccountDetails()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_save_account')) {
            echo json_encode([
                'success' => false,
            ]);

            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            echo json_encode([
                'success' => false,
            ]);

            return;
        }

        $user->updateAccountDetails($_POST);

        echo json_encode([
            'success' => true,
        ]);
    }

    public function deleteAccount()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_delete_account')) {
            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            return;
        }

        $user->delete();
    }

    public function logout()
    {
        if (is_user_logged_in()) {
            wp_logout();
        }

        wp_redirect(vehicaApp('settings_config')->getLoginPageUrl());
        exit;
    }

    public function checkIfUserLogged()
    {
        global $post;
        if (!$post) {
            return;
        }

        if (
            vehicaApp('login_page_id') === $post->ID
            && is_user_logged_in()
            && !current_user_can('manage_options')
        ) {
            wp_redirect(PanelGeneralWidget::getCarListPageUrl());
            exit;
        }

        if (
            vehicaApp('panel_page_id') === $post->ID
            && !is_user_logged_in()
            && !vehicaApp('settings_config')->isSubmitWithoutLoginEnabled()
        ) {
            wp_redirect(vehicaApp('settings_config')->getLoginPageUrl());
            exit;
        }

        if (
            isset($_GET[PanelGeneralWidget::ACTION_TYPE])
            && $_GET[PanelGeneralWidget::ACTION_TYPE] === PanelGeneralWidget::ACTION_TYPE_CAR_LIST
            && vehicaApp('panel_page_id') === $post->ID
            && is_user_logged_in()
            && !current_user_can('manage_options')
            && !User::getCurrent()->hasCars()
        ) {
            wp_redirect(PanelGeneralWidget::getCreateCarPageUrl());
            exit;
        }
    }

    public function changePassword()
    {
        if (!isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['nonce']) || !is_user_logged_in()) {
            return;
        }

        $nonce = $_POST['nonce'];
        $oldPassword = $_POST['oldPassword'];
        $newPassword = $_POST['newPassword'];

        if (empty($oldPassword) || empty($newPassword) || empty($nonce)) {
            return;
        }

        if (!wp_verify_nonce($nonce, 'vehica_change_password')) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('nonce_error_string')
            ]);

            return;
        }

        $user = _wp_get_current_user();

        if (!$user instanceof WP_User) {
            return;
        }

        if (!wp_check_password($oldPassword, $user->data->user_pass, $user->ID)) {
            echo json_encode([
                'success' => false,
                'message' => vehicaApp('wrong_password_string')
            ]);

            return;
        }

        wp_set_password($newPassword, $user->ID);

        echo json_encode([
            'success' => true,
            'message' => ''
        ]);
    }

}