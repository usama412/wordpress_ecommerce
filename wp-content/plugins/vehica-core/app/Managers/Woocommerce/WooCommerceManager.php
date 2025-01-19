<?php /** @noinspection TransitiveDependenciesUsageInspection */


namespace Vehica\Managers\Woocommerce;


use Exception;
use Vehica\Action\ApplyPackageAction;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;
use Vehica\Panel\Package;
use Vehica\Panel\PaymentPackage;
use Vehica\Widgets\General\PanelGeneralWidget;
use WC_Order;
use WC_Order_Item;
use WC_Order_Item_Product;
use WC_Product;

/**
 * Class WooCommerceManager
 * @package Vehica\Managers\Woocommerce
 */
class WooCommerceManager extends Manager
{

    public function boot()
    {
        add_action('admin_init', static function () {
            if (get_option('woocommerce_feature_order_attribution_enabled') !== 'yes') {
                update_option('woocommerce_feature_order_attribution_enabled', 'no');
            }
        });

        add_action('wp_ajax_vehica/woocommerce/checkout', [$this, 'checkout']);

        add_filter('woocommerce_checkout_fields', [$this, 'checkoutFields']);

        add_filter('woocommerce_billing_fields', [$this, 'billingFields']);

        add_action('woocommerce_payment_complete', [$this, 'paymentComplete']);

        add_action('woocommerce_order_status_changed', [$this, 'bacsPaymentComplete'], 10, 3);

        add_action('admin_post_vehica_checkout', [$this, 'checkout']);

        add_filter('woocommerce_create_pages', function ($body) {
            if (!isset($body['checkout'])) {
                return $body;
            }

            $body['checkout']['content'] = '<!-- wp:shortcode -->[' . apply_filters('woocommerce_checkout_shortcode_tag', 'woocommerce_checkout') . ']<!-- /wp:shortcode -->';

            return $body;
        });

        add_filter('nonce_user_logged_out', static function ($uid = 0, $action = '') {
            if (in_array($action, [
                'vehica_login',
                'vehica_register',
                'vehica_create_car',
                'vehica_send_reset_password_link',
                'vehica_set_password',
            ], true)) {
                return 0;
            }

            return $uid;
        }, 100, 2);

        add_action('template_redirect', static function () {
            if (!vehicaApp('woocommerce_mode')) {
                return;
            }

            global $woocommerce;
            if (!$woocommerce) {
                return;
            }

            if (is_checkout() && 0 == sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'woothemes'),
                    $woocommerce->cart->cart_contents_count) && !isset($_GET['key'])) {
                if (is_user_logged_in()) {
                    wp_redirect(PanelGeneralWidget::getCarListPageUrl());
                } else {
                    wp_redirect(home_url());
                }
                exit;
            }
        });
    }

    /**
     * @param array $fields
     * @return array
     */
    public function billingFields($fields)
    {
        $fields ['billing_phone']['required'] = false;

        $fields['billing_email']['class'] = ['form-row-wide'];

        unset($fields ['billing_phone']);

        return $fields;
    }

    /**
     * @param int $orderId
     * @param string $oldStatus
     * @param string $newStatus
     * @noinspection PhpUnusedParameterInspection
     */
    public function bacsPaymentComplete($orderId, $oldStatus, $newStatus)
    {
        if (!$orderId || $newStatus !== 'completed') {
            return;
        }

        $order = wc_get_order($orderId);
        if (
            !$order
            || (
                $order->get_payment_method() !== 'bacs'
                && $order->get_payment_method() !== 'cod'
                && $order->get_payment_method() !== 'multicaixa_proxypay'
                && $order->get_payment_method() !== 'netopiapayments'
                && $order->get_payment_method() !== 'WCGatewayDPO'
                && $order->get_payment_method() !== 'obs-woocommerce-piraeus'
                && $order->get_payment_method() !== 'piraeusbank_gateway'
            )) {
            return;
        }

        $this->applyPackageFromOrder($order);
    }

    /**
     * @param int $orderId
     */
    public function paymentComplete($orderId)
    {
        if (!$orderId) {
            return;
        }

        $order = wc_get_order($orderId);
        if (!$order || in_array($order->get_payment_method(), ['bacs', 'cod', 'cheque'])) {
            return;
        }

        $this->applyPackageFromOrder($order);
    }

    /**
     * @param WC_Order $order
     */
    private function applyPackageFromOrder(WC_Order $order)
    {
        $user = User::getById($order->get_user_id());
        if (!$user) {
            return;
        }

        foreach ($order->get_items() as $item) {
            $itemProduct = new WC_Order_Item_Product($item->get_id());

            $paymentPackage = PaymentPackage::getByAssignedProduct($itemProduct->get_product_id());
            if (!$paymentPackage) {
                return;
            }

            $package = $user->addPaymentPackage($paymentPackage);
            if ($user->hasCarInProgress()) {
                $this->applyPackage($user->getCarInProgress(), $package);
                $user->removeCarInProgress();
            }
        }
    }

    /**
     * @param array $fields
     * @return array
     */
    public function checkoutFields($fields)
    {
        unset($fields['order']['order_comments']);

        return $fields;
    }

    private function applyFreeListing()
    {
        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            return;
        }

        $package = Package::getFree();
        $user->addPackage($package);

        if (!$user->hasCarInProgress()) {
            return;
        }

        $this->applyPackage($user->getCarInProgress(), $package);
    }

    /**
     * @param Car $car
     * @param Package $package
     */
    private function applyPackage(Car $car, Package $package)
    {
        if (
            !vehicaApp('settings_config')->isModerationEnabled()
            || (current_user_can('manage_options') && $car->getUser()->getId() === get_current_user_id())
        ) {
            $applyPackage = new ApplyPackageAction();
            if (!$applyPackage->apply($package, $car)) {
                return;
            }

            if (!$car->isPublished()) {
                $car->setPublish();
            }
        } else {
            $car->setPendingPackage($package->getKey());

            $car->setPending();
        }
    }

    public function checkout()
    {
        if (!isset($_POST['packageKey'], $_POST['nonce']) || !vehicaApp('woocommerce_mode') || !wp_verify_nonce($_POST['nonce'],
                'vehica_buy_package')) {
            /** @noinspection ForgottenDebugOutputInspection */
            wp_die();
        }

        $packageKey = $_POST['packageKey'];
        if ($packageKey === 'free' && vehicaApp('settings_config')->isFreeListingEnabled()) {
            $this->applyFreeListing();

            echo json_encode(['redirect' => PanelGeneralWidget::getCarListPageUrl()]);
            exit;
        }

        $package = PaymentPackage::getByKey($packageKey);
        if (!$package || !$package->isProductAssigned()) {
            http_response_code(500);
            exit;
        }

        WC()->cart->empty_cart();

        try {
            WC()->cart->add_to_cart($package->getProductId());
        } catch (Exception $e) {
            http_response_code(500);
            exit;
        }

        echo json_encode(['redirect' => wc_get_checkout_url()]);
        exit;
    }

    /**
     * @param WC_Order $order
     * @return false|WC_Product|null
     */
    public static function getProduct(WC_Order $order)
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($order->get_items() as $item) {
            $productId = (new WC_Order_Item_Product($item->get_id()))->get_product_id();
            return wc_get_product($productId);
        }

        return false;
    }

    /**
     * @return false|WC_Product|null
     */
    public static function getCurrentProduct()
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach (WC()->cart->get_cart() as $item) {
            return wc_get_product($item['product_id']);
        }

        return false;
    }

}