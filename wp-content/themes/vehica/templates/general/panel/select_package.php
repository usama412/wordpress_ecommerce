<?php
$vehicaUser = new \Vehica\Model\User\User(_wp_get_current_user());
$vehicaIsPayPalEnabled = vehicaApp('current_base_currency_support_paypal') && vehicaApp('settings_config')->isPayPalEnabled();
?>
<div
        id="vehica-select-package"
        class="vehica-select-package"
        :class="{'vehica-select-package--has-error': carForm.showErrors && carForm.packageKey === ''}"
>
    <h3 class="vehica-car-form__section-title">
        <?php echo esc_html(vehicaApp('choose_package_string')); ?><span class="vehica-text-primary"> *</span>
    </h3>

    <?php do_action('vehica/packages/before'); ?>

    <vehica-user-packages request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_user_packages')); ?>">
        <div slot-scope="userPackages">
            <vehica-packages-view
                    initial-view="<?php echo esc_attr(vehicaApp('current_user')->hasPackages() ? 'select' : 'buy'); ?>"
            >
                <div
                        slot-scope="packagesView"
                        class="vehica-car-form__section"
                >
                    <?php if (!vehicaApp('woocommerce_mode') || true) : ?>
                        <template>
                            <div
                                    v-show="userPackages.packages.length > 0"
                                    class="vehica-car-form__switcher-wrapper"
                            >
                                <div
                                        class="vehica-car-form__switcher__option"
                                        :class="{'vehica-car-form__switcher__option--active': packagesView.view === 'select'}"
                                        @click.prevent="packagesView.setView('select')"
                                >
                                    <?php echo esc_html(vehicaApp('my_packages_string')); ?>
                                </div>

                                <label class="vehica-car-form__switcher">
                                    <input
                                            type="checkbox"
                                            :checked="packagesView.view === 'buy'"
                                            @change="packagesView.changeView"
                                    >

                                    <span class="vehica-car-form__switcher__slider"></span>
                                </label>

                                <div
                                        class="vehica-car-form__switcher__option"
                                        :class="{'vehica-car-form__switcher__option--active': packagesView.view === 'buy'}"
                                        @click.prevent="packagesView.setView('buy')"
                                >
                                    <?php echo esc_html(vehicaApp('buy_new_string')); ?>
                                </div>
                            </div>
                        </template>
                    <?php endif; ?>

                    <div class="vehica-packages">
                        <template>
                            <div v-show="packagesView.view === 'buy'">
                                <?php foreach (vehicaApp('valid_payment_packages') as $vehicaPaymentPackage) :/* @var \Vehica\Panel\PaymentPackage $vehicaPaymentPackage */ ?>
                                    <div
                                            class="vehica-package"
                                            :class="{'vehica-package--active': '<?php echo esc_attr($vehicaPaymentPackage->getKey()) ?>' === carForm.buyPackageKey}"
                                            @click.prevent="carForm.setBuyPackageKey('<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>')"
                                            key="<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                                    >
                                        <div class="vehica-package__left">
                                            <?php if (!empty($vehicaPaymentPackage->getLabel())) : ?>
                                                <div class="vehica-package-new__label">
                                                    <?php echo esc_html($vehicaPaymentPackage->getLabel()); ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="vehica-package__name">
                                                <?php echo esc_html($vehicaPaymentPackage->getName()); ?>
                                            </div>

                                            <div class="vehica-package__price">
                                                <?php echo esc_html($vehicaPaymentPackage->getDisplayPrice()); ?>
                                            </div>
                                        </div>

                                        <div class="vehica-package__right">
                                            <div class="vehica-package__desc">
                                                <div class="vehica-package__desc__row">
                                                    <div class="vehica-package__desc__row__label">
                                                        <?php echo esc_html(vehicaApp('listings_string')); ?>:
                                                    </div>

                                                    <div class="vehica-package__desc__row__value">
                                                        <?php echo esc_html($vehicaPaymentPackage->getNumber()) ?>
                                                        x
                                                    </div>
                                                </div>

                                                <div class="vehica-package__desc__row">
                                                    <div class="vehica-package__desc__row__label">
                                                        <?php echo esc_html(vehicaApp('duration_string')); ?>
                                                    </div>

                                                    <div class="vehica-package__desc__row__value">
                                                        <?php echo esc_html($vehicaPaymentPackage->getExpire()) ?>

                                                        <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                            'UTF-8')); ?>
                                                    </div>
                                                </div>

                                                <?php if (!empty($vehicaPaymentPackage->getFeaturedExpire())) : ?>
                                                    <div class="vehica-package__desc__row">
                                                        <div class="vehica-package__desc__row__label">
                                                            <?php echo esc_html(vehicaApp('featured_string')); ?>:
                                                        </div>

                                                        <div class="vehica-package__desc__row__value">
                                                            <?php echo esc_html($vehicaPaymentPackage->getFeaturedExpire()) ?>

                                                            <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                                'UTF-8')); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="vehica-package__radio">
                                            <div class="vehica-package__radio__inner"></div>
                                        </div>
                                    </div>

                                    <?php if (!vehicaApp('woocommerce_mode')) : ?>
                                        <div
                                                v-if="carForm.buyPackageKey === '<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>'"
                                                class="vehica-package-buy-new"
                                        >
                                            <div class="vehica-package-buy-new__inner">
                                                <?php if (vehicaApp('settings_config')->isStripeEnabled() && \Vehica\Core\BaseCurrency::getSelected()) : ?>
                                                    <div>
                                                        <h3><?php echo esc_html(vehicaApp('pay_with_card_string')); ?></h3>

                                                        <?php do_action('vehica/packages/beforeStripe'); ?>

                                                        <vehica-stripe
                                                                key="stripe_<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                                                                api-key="<?php echo esc_attr(vehicaApp('settings_config')->getStripeKey()); ?>"
                                                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_stripe_init')); ?>"
                                                                confirm-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_stripe_confirm')); ?>"
                                                                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_stripe_init')); ?>"
                                                                :package-key="carForm.buyPackageKey"
                                                                in-progress-text="<?php echo esc_attr(vehicaApp('payment_processing_string')); ?>"
                                                                success-text="<?php echo esc_attr(vehicaApp('payment_processed_string')); ?>"
                                                                error-title="<?php echo esc_attr(vehicaApp('payment_error_string')); ?>"
                                                                error-text="<?php echo esc_attr(vehicaApp('payment_error_message_string')); ?>"
                                                                ok-text="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
                                                                :collect-zip-code="<?php echo esc_attr(vehicaApp('settings_config')->stripeCollectZipCode() ? 'true' : 'false'); ?>"
                                                                :error-messages="<?php echo htmlspecialchars(json_encode([
                                                                    'incomplete_number' => vehicaApp('incomplete_number_string'),
                                                                ])); ?>"
                                                        >
                                                            <div
                                                                    slot-scope="stripeProps"
                                                                    class="vehica-package-buy-new__stripe"
                                                            >
                                                                <form id="vehica-payment-form">
                                                                    <div id="vehica-card-errors"></div>

                                                                    <div id="vehica-card-element"></div>

                                                                    <button
                                                                            id="vehica-submit"
                                                                            class="vehica-stripe-button"
                                                                    >
                                                                        <span id="button-text"><?php echo esc_html(vehicaApp('pay_now_string')); ?>
                                                                            (<?php echo esc_html($vehicaPaymentPackage->getDisplayPrice()); ?>
                                                                            )</span>
                                                                    </button>

                                                                    <p id="card-error" role="alert"></p>
                                                                </form>

                                                                <div class="vehica-package-buy-new__inner__info">
                                                                    <?php echo esc_html(vehicaApp('pay_with_card_info_string')); ?>
                                                                </div>
                                                            </div>
                                                        </vehica-stripe>
                                                    </div>

                                                    <?php if ($vehicaIsPayPalEnabled) : ?>
                                                        <div class="vehica-package-buy-new__or">
                                                            <?php echo esc_html(vehicaApp('or_string')); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                                <?php if ($vehicaIsPayPalEnabled) : ?>
                                                    <div>
                                                        <vehica-paypal
                                                                key="paypal_<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                                                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_paypal_init')); ?>"
                                                                confirm-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_paypal_confirm')); ?>"
                                                                :package-key="carForm.buyPackageKey"
                                                                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_paypal_init')); ?>"
                                                                in-progress-text="<?php echo esc_attr(vehicaApp('payment_processing_string')); ?>"
                                                                success-text="<?php echo esc_attr(vehicaApp('payment_processed_string')); ?>"
                                                        >
                                                            <div slot-scope="payPal">
                                                                <div id="paypal-button-container"></div>
                                                            </div>
                                                        </vehica-paypal>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                            <div v-show="packagesView.view === 'select'">
                                <div
                                        v-for="(package, key) in userPackages.packages"
                                        :key="package.key"
                                        @click.prevent="carForm.setPackageKey(package.key)"
                                >
                                    <div
                                            class="vehica-package vehica-package--owned"
                                            :class="{'vehica-package--active': carForm.packageKey === package.key}"
                                    >
                                        <div class="vehica-package__left">
                                            <div class="vehica-package__name" v-if="userPackages.packages.length > 1">
                                                <?php echo esc_html(vehicaApp('package_string')); ?> #{{ key + 1}}
                                            </div>

                                            <div class="vehica-package__name" v-if="userPackages.packages.length == 1">
                                                <?php echo esc_html(vehicaApp('my_package_string')); ?>
                                            </div>

                                            <div class="vehica-package__listings-left">
                                                <strong>{{ package.number
                                                    }}</strong> <?php echo esc_html(vehicaApp('listings_left_string')); ?>
                                            </div>
                                        </div>

                                        <div class="vehica-package__right">
                                            <div class="vehica-package__desc">
                                                <div class="vehica-package__desc__row">
                                                    <div class="vehica-package__desc__row__label">
                                                        <?php echo esc_html(vehicaApp('duration_string')); ?>
                                                    </div>

                                                    <div class="vehica-package__desc__row__value">
                                                        {{ package.expire
                                                        }} <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                            'UTF-8')); ?>
                                                    </div>
                                                </div>

                                                <div
                                                        v-if="package.featured_expire > 0"
                                                        class="vehica-package__desc__row"
                                                >
                                                    <div class="vehica-package__desc__row__label">
                                                        <?php echo esc_html(vehicaApp('featured_string')); ?>:
                                                    </div>

                                                    <div class="vehica-package__desc__row__value">
                                                        {{ package.featured_expire
                                                        }} <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                            'UTF-8')); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="vehica-package__radio">
                                            <div class="vehica-package__radio__inner"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (vehicaApp('settings_config')->isFreeListingEnabled()) : ?>
                                <div
                                        class="vehica-package vehica-package--free"
                                        :class="{'vehica-package--active': carForm.packageKey === 'free'}"
                                        @click.prevent="carForm.setPackageKey('free')"
                                >
                                    <div class="vehica-package__left">
                                        <div class="vehica-package__price">
                                            <?php echo esc_html(vehicaApp('free_string')); ?>
                                        </div>
                                    </div>

                                    <div class="vehica-package__right">
                                        <div class="vehica-package__desc">
                                            <div class="vehica-package__desc__row">
                                                <div class="vehica-package__desc__row__label">
                                                    <?php echo esc_html(vehicaApp('duration_string')); ?>
                                                </div>

                                                <div class="vehica-package__desc__row__value">
                                                    <?php echo esc_html(vehicaApp('settings_config')->getFreeListingExpire()); ?>

                                                    <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                        'UTF-8')); ?>
                                                </div>
                                            </div>

                                            <?php if (!empty(vehicaApp('settings_config')->getFreeListingFeaturedExpire())) : ?>
                                                <div class="vehica-package__desc__row">
                                                    <div class="vehica-package__desc__row__label">
                                                        <?php echo esc_html(vehicaApp('featured_string')); ?>:
                                                    </div>

                                                    <div class="vehica-package__desc__row__value">
                                                        <?php echo esc_html(vehicaApp('settings_config')->getFreeListingFeaturedExpire()) ?>

                                                        <?php echo esc_html(mb_strtolower(vehicaApp('days_string'),
                                                            'UTF-8')); ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="vehica-package__radio">
                                        <div class="vehica-package__radio__inner"></div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </template>
                    </div>
                </div>
            </vehica-packages-view>
        </div>
    </vehica-user-packages>
</div>