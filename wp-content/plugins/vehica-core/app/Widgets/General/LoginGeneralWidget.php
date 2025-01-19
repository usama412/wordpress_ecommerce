<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class LoginGeneralWidget
 * @package Vehica\Widgets\General
 */
class LoginGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_login_general_widget';
    const TEMPLATE = 'general/login/login';
    const ACTION_TYPE = 'action';
    const ACTION_TYPE_LOGIN = 'login';
    const ACTION_TYPE_SEND_CONFIRMATION = 'send_confirmation';
    const ACTION_TYPE_RESET_PASSWORD = 'reset_password';
    const ACTION_TYPE_SET_PASSWORD = 'set_password';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Login and Register Form', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'vehica_policy',
            [
                'label' => esc_html__('I accept Private Policy / Terms of Use', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'I accept the <a href="#">privacy policy</a>'
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getPolicyLabel()
    {
        return (string)$this->get_settings_for_display('vehica_policy');
    }

    /**
     * @return bool
     */
    public function hasPolicyLabel()
    {
        return !empty($this->getPolicyLabel());
    }

    /**
     * @return string
     */
    public static function getSendConfirmationPageUrl()
    {
        return vehicaApp('settings_config')->getLoginPageUrl() . '?' . self::ACTION_TYPE . '=' . self::ACTION_TYPE_SEND_CONFIRMATION;
    }

    /**
     * @return bool
     */
    public function isSendConfirmationMailPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_SEND_CONFIRMATION;
    }

    /**
     * @return bool
     */
    public function isResetPasswordPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_RESET_PASSWORD;
    }

    /**
     * @return bool
     */
    public function isSetPasswordPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_SET_PASSWORD;
    }

    /**
     * @return bool
     */
    public function isLoginPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_LOGIN;
    }

    /**
     * @return string
     */
    public function getActionType()
    {
        if (!isset($_GET[self::ACTION_TYPE]) || $_GET[self::ACTION_TYPE] === 'elementor') {
            return self::ACTION_TYPE_LOGIN;
        }

        return $_GET[self::ACTION_TYPE];
    }

    /**
     * @return string
     */
    public static function getResetPasswordPageUrl()
    {
        return vehicaApp('settings_config')->getLoginPageUrl() . '?' . self::ACTION_TYPE . '=' . self::ACTION_TYPE_RESET_PASSWORD;
    }

    /**
     * @return string
     */
    public function getSelector()
    {
        return isset($_GET['selector']) ? $_GET['selector'] : '';
    }

    /**
     * @return string
     */
    public function getValidator()
    {
        return isset($_GET['validator']) ? $_GET['validator'] : '';
    }

    /**
     * @return string
     */
    public static function getRedirectUrl()
    {
        return apply_filters('vehica/login/redirectUrl', PanelGeneralWidget::getCarListPageUrl());
    }

    /**
     * @return bool
     */
    public static function showPhoneNumberField()
    {
        $phoneSetting = vehicaApp('settings_config')->getPanelPhoneNumber();

        return $phoneSetting === 'optional_show' || $phoneSetting === 'required';
    }

    /**
     * @return bool
     */
    public function isPhoneNumberFieldRequired()
    {
        return vehicaApp('settings_config')->getPanelPhoneNumber() === 'required';
    }

}