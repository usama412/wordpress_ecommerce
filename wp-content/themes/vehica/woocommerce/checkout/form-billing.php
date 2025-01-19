<?php
/**
 * Checkout billing information form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-billing.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 20.0.0
 * @global WC_Checkout $checkout
 */

defined('ABSPATH') || exit;
?>

<div class="vehica-checkout__biling-info">
    <h3 class="vehica-car-form__section-title">
        <?php echo esc_attr(vehicaApp('billing_details_string')); ?>
    </h3>
    <div class="vehica-car-form__section vehica-checkout__biling-info__inner">
        <?php do_action('woocommerce_before_checkout_billing_form', $checkout); ?>

        <div class="vehica-checkout__fields">
            <?php
            $fields = $checkout->get_checkout_fields('billing');

            foreach ($fields as $key => $field) {
                woocommerce_form_field($key, $field, $checkout->get_value($key));
            }
            ?>
        </div>

        <?php do_action('woocommerce_after_checkout_billing_form', $checkout); ?>
    </div>
</div>
