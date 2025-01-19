<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
?>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="general">
                <?php esc_html_e('Monetization System', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div>
                <div class="vehica-field">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_SYSTEM); ?>">
                        <?php esc_html_e('Monetization system', 'vehica-core'); ?>
                    </label>

                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_SYSTEM); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_SYSTEM); ?>"
                            class="vehica-selectize"
                    >
                        <option value="0">
                            <?php esc_html_e('Disabled', 'vehica-core'); ?>
                        </option>

                        <option
                                value="builtin"
                            <?php if (vehicaApp('builtin_mode')) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('Build in (PayPal & Stripe)', 'vehica-core'); ?>
                        </option>

                        <option
                                value="woocommerce"
                            <?php if (vehicaApp('settings_config')->isWoocommerceModeEnabled()) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('WooCommerce Payment Gateways', 'vehica-core'); ?>
                        </option>
                    </select>

                    <div>
                        <?php esc_html_e('Please click "Save All Changes" button after changing this option to hide / display fields related to chosen monetization system.', 'vehica-core') ?>
                    </div>
                </div>

                <div>
                    <button class="vehica-button">
                        <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    <?php if (!vehicaApp('settings_config')->isPaymentEnabled()) : ?>
        v-show="false"
    <?php endif; ?>
>
    <div class="vehica-section">
        <div class="vehica-section__left">
            <div class="vehica-section__left__inner">
                <h2 id="general">
                    <?php esc_html_e('Paid Submission', 'vehica-core'); ?>
                </h2>

                <?php esc_html_e('You can configure charging your visitors for adding listings on your website. You can add all listings paid, some free etc.', 'vehica-core') ?>
            </div>
        </div>

        <div class="vehica-section__right">
            <div class="vehica-section__right__inner">
                <div>

                    <?php if (vehicaApp('builtin_mode')) : ?>
                        <div class="vehica-field">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAYMENT_CURRENCY); ?>">
                                <?php esc_html_e('Paid Submission Currency', 'vehica-core'); ?>
                            </label>

                            <select
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAYMENT_CURRENCY); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAYMENT_CURRENCY); ?>"
                                    class="vehica-selectize"
                            >
                                <option value="0">
                                    <?php esc_html_e('Select currency', 'vehica-core'); ?>
                                </option>
                                <?php foreach (vehicaApp('base_currencies') as $vehicaBaseCurrency) :
                                    /* @var \Vehica\Core\BaseCurrency $vehicaBaseCurrency */
                                    ?>
                                    <option
                                            value="<?php echo esc_attr($vehicaBaseCurrency->code); ?>"
                                        <?php if ($vehicaBaseCurrency->code === vehicaApp('settings_config')->getPaymentCurrency()) : ?>
                                            selected
                                        <?php endif; ?>
                                    >
                                        <?php echo esc_html($vehicaBaseCurrency->code);
                                        echo esc_html($vehicaBaseCurrency->getSupportedPaymentIntegrationsString()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <h3><?php esc_html_e('"Add for free" option', 'vehica-core'); ?></h3>

                    <div class="vehica-field">
                        <input
                                id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FREE_LISTING); ?>"
                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FREE_LISTING); ?>"
                                type="checkbox"
                                value="1"
                            <?php if (vehicaApp('settings_config')->isFreeListingEnabled()) : ?>
                                checked
                            <?php endif; ?>
                        >

                        <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FREE_LISTING); ?>">
                            <?php esc_html_e('Enable "Add for free"', 'vehica-core'); ?>
                        </label>
                    </div>

                    <div class="vehica-field">
                        <div class="vehica-field__col-1">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_EXPIRE); ?>">
                                <?php esc_html_e('Listing duration (days)', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field__col-2">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_EXPIRE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_EXPIRE); ?>"
                                    type="text"
                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getFreeListingExpire()); ?>"
                            >
                        </div>
                    </div>

                    <div class="vehica-field">
                        <div class="vehica-field__col-1">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_FEATURED_EXPIRE); ?>">
                                <?php esc_html_e('Featured duration (days)', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field__col-2">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_FEATURED_EXPIRE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FREE_LISTING_FEATURED_EXPIRE); ?>"
                                    type="text"
                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getFreeListingFeaturedExpire()); ?>"
                            >
                        </div>
                    </div>

                    <h3><?php esc_html_e('"Free Package" option for new registered users', 'vehica-core'); ?></h3>

                    <div class="vehica-field">
                        <input
                                id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_ADD_PACKAGE_REGISTER); ?>"
                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_ADD_PACKAGE_REGISTER); ?>"
                                type="checkbox"
                                value="1"
                            <?php if (vehicaApp('settings_config')->isAddPackageWhenRegisterEnabled()) : ?>
                                checked
                            <?php endif; ?>
                        >

                        <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_ADD_PACKAGE_REGISTER); ?>">
                            <?php esc_html_e('Enable "Free Package"', 'vehica-core'); ?>
                        </label>
                    </div>

                    <div class="vehica-field">
                        <div class="vehica-field__col-1">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_NUMBER); ?>">
                                <?php esc_html_e('Number of Listings', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field__col-2">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_NUMBER); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_NUMBER); ?>"
                                    type="text"
                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getRegisterPackageNumber()); ?>"
                            >
                        </div>
                    </div>

                    <div class="vehica-field">
                        <div class="vehica-field__col-1">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_EXPIRE); ?>">
                                <?php esc_html_e('Listing duration (days)', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field__col-2">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_EXPIRE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_EXPIRE); ?>"
                                    type="text"
                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getRegisterPackageExpire()); ?>"
                            >
                        </div>
                    </div>

                    <div class="vehica-field">
                        <div class="vehica-field__col-1">
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_FEATURED_EXPIRE); ?>">
                                <?php esc_html_e('Featured duration (days)', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field__col-2">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_FEATURED_EXPIRE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PACKAGE_FEATURED_EXPIRE); ?>"
                                    type="text"
                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getRegisterPackageFeaturedExpire()); ?>"
                            >
                        </div>
                    </div>

                    <div>
                        <button class="vehica-button">
                            <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (vehicaApp('builtin_mode')) : ?>
        <div class="vehica-section">
            <div class="vehica-section__left">
                <div class="vehica-section__left__inner">
                    <h2 id="general">
                        <?php esc_html_e('Stripe', 'vehica-core'); ?>
                    </h2>

                    <a
                            href="https://stripe.com/docs/keys"
                            target="_blank"
                    >
                        <?php esc_html_e('Get PayPal Stripe keys here', 'vehica-core'); ?>
                    </a>
                </div>
            </div>

            <div class="vehica-section__right">
                <div class="vehica-section__right__inner">
                    <div>
                        <div class="vehica-field">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_STRIPE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_STRIPE); ?>"
                                    type="checkbox"
                                    value="1"
                                <?php if (vehicaApp('settings_config')->isStripeEnabled()) : ?>
                                    checked
                                <?php endif; ?>
                            >

                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_STRIPE); ?>">
                                <?php esc_html_e('Enable Stripe', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field">
                            <div class="vehica-field__col-1">
                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_KEY); ?>">
                                    <?php esc_html_e('Stripe Key', 'vehica-core'); ?>
                                </label>
                            </div>

                            <div class="vehica-field__col-2">
                                <input
                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_KEY); ?>"
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_KEY); ?>"
                                        type="text"
                                        value="<?php echo esc_attr(vehicaApp('settings_config')->getRawStripeKey()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-field">
                            <div class="vehica-field__col-1">
                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_SECRET_KEY); ?>">
                                    <?php esc_html_e('Stripe Secret Key', 'vehica-core'); ?>
                                </label>
                            </div>

                            <div class="vehica-field__col-2">
                                <input
                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_SECRET_KEY); ?>"
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_SECRET_KEY); ?>"
                                        type="text"
                                        value="<?php echo esc_attr(vehicaApp('settings_config')->getRawStripeSecretKey()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-field">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_COLLECT_ZIP_CODE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_COLLECT_ZIP_CODE); ?>"
                                    type="checkbox"
                                    value="1"
                                <?php if (vehicaApp('settings_config')->stripeCollectZipCode()) : ?>
                                    checked
                                <?php endif; ?>
                            >

                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STRIPE_COLLECT_ZIP_CODE); ?>">
                                <?php esc_html_e('Collect Zip Code', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div>
                            <button class="vehica-button">
                                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="vehica-section">
            <div class="vehica-section__left">
                <div class="vehica-section__left__inner">
                    <h2 id="general">
                        <?php esc_html_e('PayPal', 'vehica-core'); ?>
                    </h2>

                    <a href="https://developer.paypal.com/docs/api/overview/#get-credentials"
                       target="_blank"><?php esc_html_e('Get PayPal API keys here', 'vehica'); ?></a>
                </div>
            </div>

            <div class="vehica-section__right">
                <div class="vehica-section__right__inner">
                    <div>
                        <?php if (!vehicaApp('current_base_currency_support_paypal')) : ?>
                            <div class="vehica-section__not-available">
                                <?php esc_html_e('Selected currency isn\'t supported by PayPal', 'vehica'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="vehica-field">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PAY_PAL); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PAY_PAL); ?>"
                                    type="checkbox"
                                    value="1"
                                <?php if (vehicaApp('settings_config')->isPayPalEnabled()) : ?>
                                    checked
                                <?php endif; ?>
                            >
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PAY_PAL); ?>">
                                <?php esc_html_e('Enable PayPal', 'vehica-core'); ?>
                            </label>
                        </div>

                        <div class="vehica-field">
                            <div class="vehica-field__col-1">
                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_CLIENT_ID); ?>">
                                    <?php esc_html_e('PayPal Client ID', 'vehica-core'); ?>
                                </label>
                            </div>

                            <div class="vehica-field__col-2">
                                <input
                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_CLIENT_ID); ?>"
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_CLIENT_ID); ?>"
                                        type="text"
                                        value="<?php echo esc_attr(vehicaApp('settings_config')->getRawPayPalClientId()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-field">
                            <div class="vehica-field__col-1">
                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_SECRET); ?>">
                                    <?php esc_html_e('PayPal Secret', 'vehica-core'); ?>
                                </label>
                            </div>

                            <div class="vehica-field__col-2">
                                <input
                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_SECRET); ?>"
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PAY_PAL_SECRET); ?>"
                                        type="text"
                                        value="<?php echo esc_attr(vehicaApp('settings_config')->getRawPayPalSecret()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-field vehica-field--checkbox">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_TEST_MODE); ?>"
                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_TEST_MODE); ?>"
                                    type="checkbox"
                                    value="1"
                                <?php if (vehicaApp('settings_config')->isMonetizationTestModeEnabled()) : ?>
                                    checked
                                <?php endif; ?>
                            >
                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MONETIZATION_TEST_MODE); ?>">
                                <?php esc_html_e('Check if you use "sandbox" credentials', 'vehica-core'); ?>
                            </label>
                        </div>
                    </div>
                    <div>
                        <button class="vehica-button">
                            <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
