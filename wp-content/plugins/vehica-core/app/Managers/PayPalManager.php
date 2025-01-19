<?php /** @noinspection TransitiveDependenciesUsageInspection */


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Transaction;
use Vehica\Model\User\User;
use Vehica\Panel\PaymentPackage;
use PayPalCheckoutSdk\Core\PayPalEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpResponse;

/**
 * Class PayPalManager
 *
 * @package Vehica\Managers
 */
class PayPalManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_paypal_init', [$this, 'init']);

        add_action('admin_post_vehica_paypal_confirm', [$this, 'confirm']);
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
     * @param string $transactionId
     */
    public function confirmPayment($transactionId)
    {
        $client = $this->getClient();
        $request = new OrdersGetRequest($transactionId);
        $response = $client->execute($request);

        $transaction = Transaction::getByTransactionId($transactionId);
        if ($transaction && $transaction->isCreated() && $response->result->status === 'COMPLETED') {
            $transaction->setFinished();
        }

        echo json_encode(['success' => $response->result->status === 'COMPLETED']);
    }

    public function init()
    {
        $user = User::getCurrent();
        $paymentPackage = PaymentPackage::getByKey($_POST['packageKey']);

        $response = $this->createOrder($paymentPackage, $user);

        $this->createTransaction($user, $paymentPackage, $response->result->id);

        echo json_encode($response);
    }

    /**
     * @param User $user
     * @param PaymentPackage $paymentPackage
     * @param string $transactionId
     */
    private function createTransaction(User $user, PaymentPackage $paymentPackage, $transactionId)
    {
        Transaction::create([
            'meta_input' => [
                Transaction::PAYMENT_METHOD => Transaction::PAYMENT_METHOD_PAYPAL,
                Transaction::USER_ID => $user->getId(),
                Transaction::STATUS => Transaction::STATUS_CREATED,
                Transaction::TRANSACTION_ID => $transactionId,
                Transaction::PACKAGE => $paymentPackage->getKey()
            ]
        ]);
    }

    /**
     * @param PaymentPackage $paymentPackage
     * @param User $user
     *
     * @return HttpResponse
     */
    private function createOrder(PaymentPackage $paymentPackage, User $user)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = $this->buildRequestBody($paymentPackage, $user);

        return $this->getClient()->execute($request);
    }

    /**
     * @param PaymentPackage $paymentPackage
     * @param User $user
     *
     * @return array
     */
    private function buildRequestBody(PaymentPackage $paymentPackage, User $user)
    {
        return [
            'intent' => 'CAPTURE',
            'application_context' => [
                'shipping_preference' => 'NO_SHIPPING',
            ],
            'purchase_units' =>
                [
                    0 =>
                        [
                            'description' => $paymentPackage->getName(),
                            'amount' =>
                                [
                                    'currency_code' => vehicaApp('settings_config')->getPaymentCurrency(),
                                    'value' => $paymentPackage->getPrice()
                                ]
                        ]
                ]
        ];
    }

    /**
     * @return PayPalHttpClient
     */
    private function getClient()
    {
        return new PayPalHttpClient($this->getEnvironment());
    }

    /**
     * @return PayPalEnvironment
     */
    public function getEnvironment()
    {
        $clientId = vehicaApp('settings_config')->getPayPalClientId();
        $clientSecret = vehicaApp('settings_config')->getPayPalSecret();

        if (vehicaApp('settings_config')->isMonetizationTestModeEnabled()) {
            return new SandboxEnvironment($clientId, $clientSecret);
        }

        return new ProductionEnvironment($clientId, $clientSecret);
    }
}