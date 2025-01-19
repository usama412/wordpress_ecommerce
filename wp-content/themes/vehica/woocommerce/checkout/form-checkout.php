<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 20.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/* @var WC_Checkout $checkout */
do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', vehicaApp('must_be_logged_to_checkout_string')));
    return;
}
?>
<div class="vehica-checkout">
    <div class="vehica-car-form">
        <div class="vehica-car-form__inner">
            <form name="checkout" method="post" class="checkout woocommerce-checkout"
                  action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

                <?php if ($checkout->get_checkout_fields()) : ?>
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div id="customer_details">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <?php endif; ?>

                <h3 class="vehica-car-form__section-title">
                    <?php echo esc_attr(vehicaApp('your_order_string')); ?>
                </h3>

                <div class="vehica-car-form__section">
                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <?php do_action('woocommerce_checkout_order_review'); ?>
                </div>

                <?php do_action('woocommerce_checkout_after_order_review'); ?>
            </form>

            <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
        </div>
    </div>
</div>