<div class="vehica-car-form">
    <div class="vehica-car-form__inner">
        <h3 class="vehica-car-form__section-title">
            <?php echo esc_attr(vehicaApp('choose_package_string')); ?>
        </h3>

        <vehica-panel-buy-package
                choose-package-text="<?php echo esc_attr(vehicaApp('choose_package_string')); ?>"
                checkout-url="<?php echo esc_url(admin_url('admin-ajax.php?action=vehica/woocommerce/checkout')); ?>"
                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_buy_package')); ?>"
        >
            <div slot-scope="buyPackage">
                <div class="vehica-car-form__section">
                    <?php foreach (vehicaApp('valid_payment_packages') as $vehicaPaymentPackage) :
                        /* @var \Vehica\Panel\PaymentPackage $vehicaPaymentPackage */
                        ?>
                        <div
                                class="vehica-package"
                                :class="{'vehica-package--active': '<?php echo esc_attr($vehicaPaymentPackage->getKey()) ?>' === buyPackage.packageKey}"
                                @click.prevent="buyPackage.onBuyPackage('<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>')"
                                key="<?php echo esc_attr($vehicaPaymentPackage->getKey()); ?>"
                        >
                            <div class="vehica-package__left">
                                <?php if (!empty($vehicaPaymentPackage->getLabel())) : ?>
                                    <div class="vehica-package-new__label"><?php echo esc_html($vehicaPaymentPackage->getLabel()); ?></div>
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
                                            <?php echo esc_html(mb_strtolower(vehicaApp('days_string'), 'UTF-8')); ?>
                                        </div>
                                    </div>

                                    <?php if (!empty($vehicaPaymentPackage->getFeaturedExpire())) : ?>
                                        <div class="vehica-package__desc__row">
                                            <div class="vehica-package__desc__row__label">
                                                <?php echo esc_html(vehicaApp('featured_string'));
                                                ?>:
                                            </div>

                                            <div class="vehica-package__desc__row__value">
                                                <?php echo esc_html($vehicaPaymentPackage->getFeaturedExpire()) ?>
                                                <?php echo esc_html(mb_strtolower(vehicaApp('days_string'), 'UTF-8')); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="vehica-package__radio">
                                <div class="vehica-package__radio__inner"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if (vehicaApp('settings_config')->isFreeListingEnabled()) : ?>
                        <div
                                class="vehica-package vehica-package--free"
                                :class="{'vehica-package--active': buyPackage.packageKey === 'free'}"
                                @click.prevent="buyPackage.onBuyPackage('free')"
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
                                            <?php echo esc_html(mb_strtolower(vehicaApp('days_string'), 'UTF-8')); ?>
                                        </div>
                                    </div>

                                    <?php if (!empty(vehicaApp('settings_config')->getFreeListingFeaturedExpire())) : ?>
                                        <div class="vehica-package__desc__row">
                                            <div class="vehica-package__desc__row__label">
                                                <?php echo esc_html(vehicaApp('featured_expire_string'));?>:
                                            </div>

                                            <div class="vehica-package__desc__row__value">
                                                <?php echo esc_html(vehicaApp('settings_config')->getFreeListingFeaturedExpire()) ?>
                                                <?php echo esc_html(mb_strtolower(vehicaApp('days_string'), 'UTF-8')); ?>
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
                </div>

                <div class="vehica-car-form__save-submit">
                    <button
                            @click.prevent="buyPackage.onBuy"
                            class="vehica-button vehica-button--with-progress-animation"
                            :class="{'vehica-button--with-progress-animation--active': buyPackage.inProgress}"
                            :disabled="buyPackage.inProgress"
                    >
                        <span><?php echo esc_html(vehicaApp('continue_string')); ?></span>

                        <template>
                            <svg
                                    v-if="buyPackage.inProgress"
                                    width="120"
                                    height="30"
                                    wviewBox="0 0 120 30"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="#fff"
                            >
                                <circle cx="15" cy="15" r="15">
                                    <animate attributeName="r" from="15" to="15"
                                             begin="0s" dur="0.8s"
                                             values="15;9;15" calcMode="linear"
                                             repeatCount="indefinite"/>
                                    <animate attributeName="fill-opacity" from="1" to="1"
                                             begin="0s" dur="0.8s"
                                             values="1;.5;1" calcMode="linear"
                                             repeatCount="indefinite"/>
                                </circle>
                                <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                    <animate attributeName="r" from="9" to="9"
                                             begin="0s" dur="0.8s"
                                             values="9;15;9" calcMode="linear"
                                             repeatCount="indefinite"/>
                                    <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                             begin="0s" dur="0.8s"
                                             values=".5;1;.5" calcMode="linear"
                                             repeatCount="indefinite"/>
                                </circle>
                                <circle cx="105" cy="15" r="15">
                                    <animate attributeName="r" from="15" to="15"
                                             begin="0s" dur="0.8s"
                                             values="15;9;15" calcMode="linear"
                                             repeatCount="indefinite"/>
                                    <animate attributeName="fill-opacity" from="1" to="1"
                                             begin="0s" dur="0.8s"
                                             values="1;.5;1" calcMode="linear"
                                             repeatCount="indefinite"/>
                                </circle>
                            </svg>
                        </template>
                    </button>
                </div>
            </div>
        </vehica-panel-buy-package>
    </div>
</div>

