<?php
if (count(vehicaApp('currencies')) < 2) {
    return;
}
?>
<div class="vehica-app">
    <vehica-currency-switcher
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_change')); ?>"
    >
        <div
                slot-scope="props"
                class="vehica-currency-switcher vehica-currency-switcher--widget"
        >
            <div
                    @click="props.onOpen"
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