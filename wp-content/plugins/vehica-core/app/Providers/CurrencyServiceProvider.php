<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\BaseCurrency;
use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Field\Fields\Price\Currency;
use WP_Term;

/**
 * Class CurrencyServiceProvider
 *
 * @package Vehica\Providers
 */
class CurrencyServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('base_currencies', static function () {
            /** @noinspection PhpIncludeInspection */
            $currencies = require vehicaApp('path') . '/config/currencies.php';
            usort($currencies, static function ($a, $b) {
                return strcmp($a['code'], $b['code']);
            });

            return Collection::make($currencies)->map(static function ($currency) {
                return new BaseCurrency($currency['code'], $currency['decimal'], $currency['support']);
            });
        });

        $this->app->bind('current_base_currency_support_paypal', static function () {
            $baseCurrency = BaseCurrency::getSelected();

            if (!$baseCurrency) {
                return false;
            }

            return $baseCurrency->supportPayPal();
        });

        $this->app->bind('currencies', static function () {
            $currencies = get_terms([
                'taxonomy' => Currency::CURRENCIES,
                'hide_empty' => false,
            ]);

            if (!is_array($currencies)) {
                return Collection::make();
            }

            return Collection::make($currencies)->map(static function ($term) {
                /* @var WP_Term $term */
                return new Currency($term);
            });
        });

        $this->app->bind('current_currency', static function () {
            if (empty($_COOKIE['vehicaCurrentCurrency'])) {
                return vehicaApp('currency_default');
            }

            $currencyId = (int)$_COOKIE['vehicaCurrentCurrency'];
            $currency = vehicaApp('currencies')->find(static function ($currency) use ($currencyId) {
                /* @var Currency $currency */
                return $currency->getId() === $currencyId;
            });

            return $currency ?: vehicaApp('currency_default');
        });

        $this->app->bind('current_currency_key', static function () {
            $currency = vehicaApp('current_currency');
            if (!$currency instanceof Currency) {
                return '';
            }

            return $currency->getKey();
        });

        $this->app->bind('current_currency_id', static function () {
            $currency = vehicaApp('current_currency');
            if (!$currency instanceof Currency) {
                return 0;
            }

            return $currency->getId();
        });

        $this->app->bind('currency_default', static function () {
            $defaultCurrencyId = Currency::getDefaultId();

            $currency = vehicaApp('currencies')->find(static function ($currency) use ($defaultCurrencyId) {
                /* @var Currency $currency */
                return $currency->getId() === $defaultCurrencyId;
            });

            if ($currency instanceof Currency) {
                return $currency;
            }

            $currencies = vehicaApp('currencies');
            if ($currencies->count() > 0) {
                return $currencies[0];
            }

            return false;
        });

        $this->app->bind('currency_default_id', static function () {
            $currency = vehicaApp('currency_default');

            if (!$currency instanceof Currency) {
                return 0;
            }

            return $currency->getId();
        });
    }

}