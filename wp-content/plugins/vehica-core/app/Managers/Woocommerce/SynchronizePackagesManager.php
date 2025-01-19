<?php


namespace Vehica\Managers\Woocommerce;


use Vehica\Core\Manager;
use Vehica\Core\Post\PostStatus;
use Vehica\Panel\PaymentPackage;

/**
 * Class SynchronizePackagesManager
 * @package Vehica\Managers\Woocommerce
 */
class SynchronizePackagesManager extends Manager
{

    public function boot()
    {
        add_action('vehica/paymentPackages/synchronize', [$this, 'synchronisePackages']);

        add_action('admin_init', [$this, 'synchronisePackages']);
    }

    public function synchronisePackages()
    {
        if (!vehicaApp('woocommerce_mode') || !$this->isWooCommerceActive()) {
            return;
        }

        vehicaApp('payment_packages')->each(function ($paymentPackage) {
            /* @var PaymentPackage $paymentPackage */
            if (!$paymentPackage->isProductAssigned()) {
                $this->createProduct($paymentPackage);
            } else {
                $this->updateProduct($paymentPackage);
            }
        });
    }

    /**
     * @param PaymentPackage $paymentPackage
     */
    private function createProduct(PaymentPackage $paymentPackage)
    {
        $productId = wp_insert_post([
            'post_title' => $paymentPackage->getName(),
            'post_content' => $paymentPackage->getDescription(),
            'post_type' => 'product',
            'post_status' => PostStatus::PUBLISH
        ]);

        update_post_meta($productId, 'vehica_payment_package', $paymentPackage->getKey());

        wp_set_object_terms($productId, 'simple', 'product_type');

        update_post_meta($productId, '_virtual', 'yes');
        update_post_meta($productId, '_regular_price', $paymentPackage->getPrice());
        update_post_meta($productId, '_price', $paymentPackage->getPrice());

        $paymentPackage->assignProduct($productId);
    }

    /**
     * @param PaymentPackage $paymentPackage
     */
    private function updateProduct(PaymentPackage $paymentPackage)
    {
        $productId = $paymentPackage->getProductId();

        wp_update_post([
            'ID' => $productId,
            'post_title' => $paymentPackage->getName(),
            'post_content' => $paymentPackage->getDescription(),
        ]);

        update_post_meta($productId, '_regular_price', $paymentPackage->getPrice());
        update_post_meta($productId, '_price', $paymentPackage->getPrice());
    }

}