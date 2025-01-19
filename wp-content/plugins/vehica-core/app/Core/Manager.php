<?php

namespace Vehica\Core;

use Vehica\Action\VerifyReCaptchaAction;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Manager
 * @package Vehica\Core
 */
abstract class Manager
{
    /**
     * @var App
     */
    protected $app;

    /**
     * Manager constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    abstract public function boot();

    /**
     * @return bool
     */
    protected function currentUserCanManageOptions()
    {
        return current_user_can('manage_options');
    }

    /**
     * @return bool
     */
    protected function isAjax()
    {
        return defined('DOING_AUTOSAVE') && DOING_AUTOSAVE;
    }


    /**
     * @return bool
     */
    public function isWooCommerceActive()
    {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true);
    }

    /**
     * @return bool
     */
    public function isReCaptchaEnabled()
    {
        return vehicaApp('recaptcha');
    }

    /**
     * @param string $action
     * @param string $token
     * @return bool
     */
    public function verifyReCaptcha($action, $token = '')
    {
        if (empty($token) && empty($_POST['token'])) {
            return false;
        }

        $token = (string)(empty($token) ? $_POST['token'] : $token);
        return VerifyReCaptchaAction::verify($action, $token);
    }

}