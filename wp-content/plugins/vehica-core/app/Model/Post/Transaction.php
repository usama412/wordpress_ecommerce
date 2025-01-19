<?php /** @noinspection ContractViolationInspection */


namespace Vehica\Model\Post;


use Vehica\Core\Post\PostStatus;
use Vehica\Model\User\User;
use Vehica\Panel\PaymentPackage;
use WP_Error;
use WP_Query;

/**
 * Class Transaction
 *
 * @package Vehica\Model\Post
 */
class Transaction extends Post
{
    const POST_TYPE             = 'vehica_transaction';
    const PAYMENT_METHOD        = 'vehica_payment_method';
    const PAYMENT_METHOD_STRIPE = 'stripe';
    const PAYMENT_METHOD_PAYPAL = 'paypal';
    const TRANSACTION_ID        = 'vehica_transaction_id';
    const USER_ID               = 'vehica_user_id';
    const STATUS                = 'vehica_status';
    const STATUS_CREATED        = 'created';
    const STATUS_FINISHED       = 'finished';
    const PACKAGE               = 'vehica_package';

    /**
     * @param  string  $method
     */
    public function setPaymentMethod($method)
    {
        $this->setMeta(self::PAYMENT_METHOD, $method);
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return (string)$this->getMeta(self::PAYMENT_METHOD);
    }

    /**
     * @return bool
     */
    public function isPaymentMethodPayPal()
    {
        return $this->getPaymentMethod() === self::PAYMENT_METHOD_PAYPAL;
    }

    /**
     * @return bool
     */
    public function isPaymentMethodStripe()
    {
        return $this->getPaymentMethod() === self::PAYMENT_METHOD_STRIPE;
    }

    /**
     * @param  string  $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->setMeta(self::TRANSACTION_ID, $transactionId);
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return (string)$this->getMeta(self::TRANSACTION_ID);
    }

    /**
     * @param  int  $userId
     */
    public function setTransactionUser($userId)
    {
        $userId = (int)$userId;
        $this->setMeta(self::USER_ID, $userId);
    }

    /**
     * @return int
     */
    public function getTransactionUserId()
    {
        return (int)$this->getMeta(self::USER_ID);
    }

    /**
     * @return User|false
     */
    public function getTransactionUser()
    {
        $userId = $this->getUserId();

        if (empty($userId)) {
            return false;
        }

        return User::getById($userId);
    }

    /**
     * @return mixed|string
     */
    public function getTransactionStatus()
    {
        return $this->getMeta(self::STATUS);
    }

    /**
     * @param  string  $status
     */
    public function setTransactionStatus($status)
    {
        $this->setMeta(self::STATUS, $status);
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        return $this->getTransactionStatus() === self::STATUS_CREATED;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->getTransactionStatus() === self::STATUS_FINISHED;
    }

    /**
     * @param  string  $packageKey
     */
    public function setPackage($packageKey)
    {
        $this->setMeta(self::PACKAGE, $packageKey);
    }

    /**
     * @return PaymentPackage|false
     */
    public function getPackage()
    {
        $packageKey = $this->getMeta(self::PACKAGE);

        return vehicaApp('payment_packages')->find(static function ($package) use ($packageKey) {
            /* @var PaymentPackage $package */
            return $package->getKey() === $packageKey;
        });
    }

    /**
     * @param  string  $transactionId
     *
     * @return false|Transaction
     */
    public static function getByTransactionId($transactionId)
    {
        $query = new WP_Query([
            'post_status' => PostStatus::PUBLISH,
            'post_type'   => self::POST_TYPE,
            'meta_key'    => self::TRANSACTION_ID,
            'meta_value'  => (string)$transactionId
        ]);

        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($query->posts as $post) {
            return new self($post);
        }

        return false;
    }

    public function setFinished()
    {
        $package = $this->getPackage();
        $user = $this->getUser();

        if ( ! $package || ! $user) {
            return;
        }

        $user->addPaymentPackage($package);

        $this->setTransactionStatus(self::STATUS_FINISHED);
    }

    /**
     * @param  array  $modelData
     *
     * @return Transaction|false
     */
    public static function create($modelData = [])
    {
        $post = wp_insert_post(
            [
                'post_type'   => self::POST_TYPE,
                'post_status' => PostStatus::PUBLISH,
            ] + $modelData
        );

        if ($post instanceof WP_Error) {
            return false;
        }

        return new self($post);
    }

}