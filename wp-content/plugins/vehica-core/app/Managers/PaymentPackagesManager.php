<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Core\Post\PostStatus;
use Vehica\Panel\PaymentPackage;

/**
 * Class PaymentPackagesManager
 *
 * @package Vehica\Managers
 */
class PaymentPackagesManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'registerPostType']);
        add_action('admin_post_vehica_create_payment_package', [$this, 'create']);
        add_action('admin_post_vehica_delete_payment_package', [$this, 'delete']);
        add_action('admin_post_vehica_update_payment_package', [$this, 'update']);
    }

    public function update()
    {
        if (!isset($_POST['package']['id']) || !current_user_can('manage_options')) {
            return;
        }

        $packageData = $_POST['package'];
        $package = PaymentPackage::getById($packageData['id']);

        if (!$package instanceof PaymentPackage) {
            return;
        }

        $package->setData($packageData);

        do_action('vehica/paymentPackages/synchronize');
    }

    public function delete()
    {
        if (!isset($_POST['packageId']) || !current_user_can('manage_options')) {
            return;
        }

        $packageId = (int)$_POST['packageId'];
        PaymentPackage::destroy($packageId);

        do_action('vehica/paymentPackages/synchronize');
    }

    public function create()
    {
        if (
            !isset($_POST['nonce'], $_POST['package'])
            || !current_user_can('manage_options')
            || !wp_verify_nonce($_POST['nonce'], 'vehica_create_payment_package')
        ) {
            return;
        }

        $packageData = $_POST['package'];

        $packageId = wp_insert_post([
            'post_title' => $packageData['name'],
            'post_status' => PostStatus::PUBLISH,
            'post_type' => PaymentPackage::POST_TYPE,
        ]);

        if (is_wp_error($packageId)) {
            echo json_encode([
                'success' => false,
                'message' => $packageId->get_error_message()
            ]);

            return;
        }

        $package = PaymentPackage::getById($packageId);

        if (!$package instanceof PaymentPackage) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Something went wrong', 'vehica-core')
            ]);

            return;
        }

        $package->setData($packageData);

        echo json_encode([
            'success' => true,
            'package' => $package
        ]);

        do_action('vehica/paymentPackages/synchronize');
    }

    public function registerPostType()
    {
        register_post_type(PaymentPackage::POST_TYPE, [
            'labels' => [
                'name' => esc_html__('Payment Packages', 'vehica-core'),
                'singular_name' => esc_html__('Payment Package', 'vehica-core'),
            ],
            'public' => true,
            'publicly_queryable' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'query_var' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => ['title'],
        ]);
    }

}