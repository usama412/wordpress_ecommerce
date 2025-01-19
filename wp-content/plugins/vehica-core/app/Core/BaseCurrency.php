<?php


namespace Vehica\Core;


/**
 * Class BaseCurrency
 *
 * @package Vehica\Core
 */
class BaseCurrency
{
    public $code = '';
    public $decimal = 2;
    public $support = [];

    /**
     * BaseCurrency constructor.
     *
     * @param string $code
     * @param int $decimal
     * @param array $support
     */
    public function __construct($code, $decimal, $support)
    {
        $this->code = $code;
        $this->decimal = $decimal;
        $this->support = $support;
    }

    /**
     * @return BaseCurrency|false
     */
    public static function getSelected()
    {
        $currencyCode = vehicaApp('settings_config')->getPaymentCurrency();
        if (empty($currencyCode)) {
            return false;
        }

        return vehicaApp('base_currencies')->find(static function ($baseCurrency) use ($currencyCode) {
            /* @var BaseCurrency $baseCurrency */
            return $baseCurrency->code === $currencyCode;
        });
    }

    /**
     * @return bool
     */
    public function supportPayPal()
    {
        return in_array('paypal', $this->support, true);
    }

    /**
     * @return string
     */
    public function getSupportedPaymentIntegrationsString()
    {
        if (!$this->supportPayPal()) {
            return esc_html__(' (Stripe)', 'vehica-core');
        }

        return esc_html__(' (Stripe & PayPal)', 'vehica-core');
    }

}