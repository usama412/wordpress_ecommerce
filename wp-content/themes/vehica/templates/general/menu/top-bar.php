<div class="vehica-top-bar">
    <div class="vehica-top-bar__left">
        <?php if (!empty(vehicaApp('phone'))) : ?>
            <div class="vehica-top-bar__element vehica-text-secondary">
                <a
                        class="vehica-text-secondary"
                        href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>"
                >
                    <i class="fas fa-phone-alt vehica-text-primary"></i> <?php echo esc_html(vehicaApp('phone')); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php if (!empty(vehicaApp('email'))) : ?>
            <div class="vehica-top-bar__element">
                <a
                        class="vehica-text-secondary"
                        href="mailto:<?php echo esc_attr(vehicaApp('email')); ?>"
                >
                    <i class="far fa-envelope vehica-text-primary"></i> <?php echo esc_html(vehicaApp('email')); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="vehica-top-bar__right">
        <?php if (count(vehicaApp('currencies')) > 1) : ?>
            <div class="vehica-top-bar__element vehica-top-bar__element--currency_switcher vehica-text-secondary">
                <vehica-currency-switcher
                        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_change')); ?>"
                >
                    <div
                            slot-scope="props"
                            class="vehica-currency-switcher"
                    >
                        <?php echo esc_html(vehicaApp('currency_string')); ?>

                        <div
                                @click.prevent="props.onOpen"
                                class="vehica-currency-switcher__inner"
                                :class="{'vehica-currency-switcher__inner--open': props.isOpen}"
                        >
                            <template>
                                {{ props.currentCurrency.name }}
                            </template>

                            <template v-if="props.isOpen">
                                <portal to="footer">
                                    <div
                                            class="vehica-currency-switcher__list"
                                            :style="props.position"
                                            tabindex="100"
                                    >
                                        <div
                                                v-for="currency in props.currencies"
                                                class="vehica-currency-switcher__element"
                                                @click="props.onChange(currency.key)"
                                        >
                                            {{ currency.name }}
                                        </div>
                                    </div>
                                </portal>
                            </template>
                            <i class="fas fa-angle-down"></i>
                        </div>
                    </div>
                </vehica-currency-switcher>
            </div>
        <?php endif; ?>
    </div>
</div>
