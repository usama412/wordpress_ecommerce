<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Transaction;
use Vehica\Model\User\User;
use Vehica\Panel\PaymentPackage;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

/**
 * Class StripeManager
 *
 * @package Vehica\Managers
 */
class StripeManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_stripe_init', [$this, 'init']);

        add_action('admin_post_vehica_stripe_confirm', [$this, 'confirm']);
    }

    public function confirm()
    {
        if (empty($_POST['transactionId'])) {
            echo json_encode(['success' => false]);
            http_response_code(500);

            return;
        }

        $transactionId = $_POST['transactionId'];
        $this->confirmPayment($transactionId);
    }

    /**
     * @param  string  $transactionId
     */
    public function confirmPayment($transactionId)
    {
        Stripe::setApiKey(vehicaApp('settings_config')->getStripeSecretKey());

        try {
            $intent = PaymentIntent::retrieve($transactionId);
            $transaction = Transaction::getByTransactionId($intent->id);

            if ($transaction && $intent->status === 'succeeded' && $transaction->isCreated()) {
                $transaction->setFinished();
            }

            echo json_encode(['success' => $intent->status === 'succeeded']);
        } catch (ApiErrorException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            http_response_code(500);
        }
    }

    /**
     * @param  PaymentPackage  $paymentPackage
     * @param  User  $user
     */
    public function createPaymentForPackage(PaymentPackage $paymentPackage, User $user)
    {
        Stripe::setApiKey(vehicaApp('settings_config')->getStripeSecretKey());

        try {
            $intent = $this->createPaymentIntent($paymentPackage, $user);

            $this->createTransaction($intent, $paymentPackage, $user);

            echo json_encode([
                'clientSecret' => $intent->client_secret,
            ]);
        } catch (ApiErrorException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  PaymentPackage  $paymentPackage
     * @param  User  $user
     *
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    private function createPaymentIntent(PaymentPackage $paymentPackage, User $user)
    {
        return PaymentIntent::create([
            'amount'        => $paymentPackage->getStripePrice(),
            'currency'      => strtolower(vehicaApp('settings_config')->getPaymentCurrency()),
            'metadata'      => [
                'user_id'      => $user->getId(),
                'package_name' => $paymentPackage->getName(),
                'package_key'  => $paymentPackage->getKey(),
            ],
            'receipt_email' => $user->getMail(),
        ]);
    }

    /**
     * @param  PaymentIntent  $paymentIntent
     * @param  PaymentPackage  $paymentPackage
     * @param  User  $user
     */
    private function createTransaction(PaymentIntent $paymentIntent, PaymentPackage $paymentPackage, User $user)
    {
        Transaction::create([
            'meta_input' => [
                Transaction::PAYMENT_METHOD => Transaction::PAYMENT_METHOD_STRIPE,
                Transaction::USER_ID        => $user->getId(),
                Transaction::STATUS         => Transaction::STATUS_CREATED,
                Transaction::TRANSACTION_ID => $paymentIntent->id,
                Transaction::PACKAGE        => $paymentPackage->getKey()
            ]
        ]);
    }

    public function init()
    {
        if (empty($_POST['packageKey'])) {
            echo json_encode(['error' => vehicaApp('something_went_wrong_string')]);
            http_response_code(500);

            return;
        }

        $paymentPackageKey = $_POST['packageKey'];
        $paymentPackage = vehicaApp('payment_packages')
            ->find(static function ($paymentPackage) use ($paymentPackageKey) {
                /* @var PaymentPackage $paymentPackage */
                return $paymentPackage->getKey() === $paymentPackageKey;
            });

        if ( ! $paymentPackage) {
            echo json_encode(['error' => vehicaApp('something_went_wrong_string')]);
            http_response_code(500);

            return;
        }

        $user = User::getCurrent();

        $this->createPaymentForPackage($paymentPackage, $user);
    }

}