<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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

defined('ABSPATH') || exit;
?>

<div class="vehica-thank-you">
    <div class="vehica-car-form">
        <div class="vehica-car-form__inner">
            <?php
            /* @var WC_Order $order */
            if ($order) :
                $product = \Vehica\Managers\Woocommerce\WooCommerceManager::getProduct($order);

                do_action('woocommerce_before_thankyou', $order->get_id());
                ?>

                <div class="vehica-car-form__section">
                    <?php if ($order->has_status('failed')) : ?>
                        <h3 class="vehica-car-form__section-title">
                            <?php echo esc_html(vehicaApp('unfortunately_your_order_cannot_string')); ?>
                        </h3>

                        <div>
                            <a
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>"
                                    class="vehica-button"
                            >
                                <?php echo esc_html(vehicaApp('back_to_panel_string')); ?>
                            </a>
                        </div>
                    <?php else : ?>
                        <div class="vehica-thank-you__icon"><i class="fas fa-check"></i></div>

                        <h3 class="vehica-car-form__section-title">
                            <?php echo esc_attr(vehicaApp('thank_you_for_your_order_string')); ?>
                            <span class="vehica-text-primary">#<?php echo esc_html($order->get_id()); ?></span>
                        </h3>

                        <div class="vehica-thank-you__purchased">
                            <?php if ($product) : ?>
                                <strong><?php echo esc_html($product->get_name()); ?></strong>
                            <?php endif; ?>
                            <strong>(<?php echo wp_kses_post($order->get_formatted_order_total()); ?>)</strong>
                        </div>

                        <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>

                        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

                        <div>
                            <a
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>"
                                    class="vehica-button vehica-button--back-to-panel"
                            >
                                <?php echo esc_html(vehicaApp('back_to_panel_string')); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>


            <?php else : ?>

                <div class="vehica-car-form__section">
                    <div class="vehica-thank-you__icon"><i class="fas fa-check"></i></div>

                    <h3 class="vehica-car-form__section-title">
                        <?php echo esc_html(vehicaApp('thank_you_order_string')); ?>
                    </h3>
                </div>

            <?php endif; ?>

        </div>
    </div>
</div>
