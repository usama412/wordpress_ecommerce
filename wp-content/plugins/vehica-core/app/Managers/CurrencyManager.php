<?php

namespace Vehica\Managers;

use Vehica\Core\Manager;
use Vehica\Field\Fields\Price\Currency;

/**
 * Class CurrencyManager
 * @package Vehica\Managers
 */
class CurrencyManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_currency_delete', [$this, 'delete']);
        add_action('admin_post_vehica_currency_create', [$this, 'create']);
        add_action('admin_post_vehica_currency_set_sign', [$this, 'setSign']);
        add_action('admin_post_vehica_currency_set_name', [$this, 'setName']);
        add_action('admin_post_vehica_currency_set_sign_position', [$this, 'setSignPosition']);
        add_action('admin_post_vehica_currency_set_format', [$this, 'setFormat']);
        add_action('admin_post_vehica_currency_set_default', [$this, 'setDefault']);
        add_action('admin_post_vehica_currency_change', [$this, 'change']);
        add_action('admin_post_nopriv_vehica_currency_change', [$this, 'change']);
    }

    public function change()
    {
        if (!isset($_POST['currencyKey'])) {
            return;
        }

        $currencyKey = sanitize_text_field($_POST['currencyKey']);
        $currency = vehicaApp('currencies')->find(static function ($currency) use ($currencyKey) {
            /* @var Currency $currency */
            return $currency->getKey() === $currencyKey;
        });

        if (!$currency instanceof Currency) {
            return;
        }

        $currency->setCurrent();
    }

    public function create()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $currency = Currency::create();

        if (!$currency) {
            echo json_encode(['success' => false]);
            return;
        }

        echo json_encode([
            'success' => true,
            'currency' => $currency
        ]);
    }

    public function delete()
    {
        if (empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        $success = Currency::destroy($currencyId);

        echo json_encode([
            'success' => $success
        ]);
    }

    public function setName()
    {
        if (!isset($_POST['currencyName']) || empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        $currencyName = (string)$_POST['currencyName'];
        $currency = Currency::getById($currencyId);

        if (!$currency) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $currency->setName($currencyName);

        echo json_encode(['success' => true]);
    }

    public function setSign()
    {
        if (!isset($_POST['currencySign']) || empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        $currencySign = $_POST['currencySign'];

        $currency = Currency::getById($currencyId);

        if (!$currency) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $currency->setSign($currencySign);

        echo json_encode(['success' => true]);
    }

    public function setSignPosition()
    {
        if (!isset($_POST['currencySignPosition']) || empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        $currencySign = $_POST['currencySignPosition'];

        $currency = Currency::getById($currencyId);

        if (!$currency) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $currency->setSignPosition($currencySign);

        echo json_encode(['success' => true]);
    }

    public function setFormat()
    {
        if (!isset($_POST['currencyFormat']) || empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        $currencyFormat = $_POST['currencyFormat'];

        $currency = Currency::getById($currencyId);

        if (!$currency) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $currency->setFormat($currencyFormat);

        echo json_encode(['success' => true]);
    }

    public function setDefault()
    {
        if (empty($_POST['currencyId']) || !current_user_can('manage_options')) {
            return;
        }

        $currencyId = (int)$_POST['currencyId'];
        Currency::setDefault($currencyId);

        echo json_encode(['success' => true]);
    }

}