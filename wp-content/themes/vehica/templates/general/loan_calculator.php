<?php
/* @var \Vehica\Widgets\General\LoanCalculatorGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaInitialPrice = $vehicaCurrentWidget->getInitialPrice();
/* @var \Vehica\Field\Fields\Price\Currency $vehicaCurrency */
$vehicaCurrency = vehicaApp('current_currency');
?>
<div class="vehica-app">
    <vehica-loan-calculator
            widget-id="<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
            decimal-separator="<?php echo esc_attr(vehicaApp('decimal_separator')); ?>"
            thousands-separator="<?php echo esc_attr(vehicaApp('thousands_separator')); ?>"
            :price-decimal-places="<?php echo esc_attr($vehicaCurrency->getDecimalPlaces()); ?>"
            price-decimal-separator="<?php echo esc_attr($vehicaCurrency->getDecimalSeparator()); ?>"
            price-thousands-separator="<?php echo esc_attr($vehicaCurrency->getThousandsSeparator()); ?>"
            :round-to-integer="<?php echo esc_attr($vehicaCurrentWidget->roundToInteger() ? 'true' : 'false'); ?>"
    >
        <div slot-scope="props" class="vehica-loan-calculator">
            <div class="vehica-loan-calculator__title">
                <?php echo esc_html(vehicaApp('loan_calculator_string')); ?>
            </div>

            <?php if ($vehicaCurrentWidget->hasTextBefore()) : ?>
                <div class="vehica-loan-calculator__subtitle">
                    <?php echo wp_kses_post($vehicaCurrentWidget->getTextBefore()) ?>
                </div>
            <?php endif; ?>

            <form @submit.prevent="props.onCalculate">
                <div class="vehica-loan-calculator__fields">
                    <div class="vehica-loan-calculator__fields__inner">
                        <div class="vehica-loan-calculator__field-wrapper">
                            <h4>
                                <?php echo esc_html(vehicaApp('price_string')); ?>
                                <span class="vehica-text-primary">*</span>
                            </h4>

                            <div class="vehica-loan-calculator__field">
                                <div class="vehica-loan-calculator__sign">
                                    <i class="fas fa-tag"></i>
                                </div>

                                <input
                                        id="vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>-loan-calculator__price"
                                        type="text"
                                    <?php if (!empty($vehicaInitialPrice)) : ?>
                                        value="<?php echo esc_attr($vehicaInitialPrice); ?>"
                                    <?php endif; ?>
                                >
                            </div>
                        </div>

                        <div class="vehica-loan-calculator__field-wrapper">
                            <h4>
                                <?php echo esc_attr(vehicaApp('interest_rate_string')); ?>
                                <span class="vehica-text-primary">*</span>
                            </h4>

                            <div class="vehica-loan-calculator__field">
                                <div class="vehica-loan-calculator__sign">
                                    <?php esc_html_e('%', 'vehica'); ?>
                                </div>

                                <input
                                        id="vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>-loan-calculator__rate"
                                        type="text"
                                        value="<?php echo esc_attr($vehicaCurrentWidget->getInitialRate()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-loan-calculator__field-wrapper">
                            <h4><?php echo esc_attr(vehicaApp('period_months_string')); ?>
                                <span class="vehica-text-primary">*</span>
                            </h4>

                            <div class="vehica-loan-calculator__field">
                                <div class="vehica-loan-calculator__sign">
                                    <i class="far fa-calendar-alt"></i>
                                </div>

                                <input
                                        id="vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>-loan-calculator__months"
                                        type="text"
                                        value="<?php echo esc_attr($vehicaCurrentWidget->getInitialMonths()); ?>"
                                >
                            </div>
                        </div>

                        <div class="vehica-loan-calculator__field-wrapper">
                            <h4><?php echo esc_attr(vehicaApp('down_payment_string')); ?></h4>

                            <div class="vehica-loan-calculator__field">
                                <div class="vehica-loan-calculator__sign">
                                    <?php echo esc_html($vehicaCurrentWidget->getCurrencySign()); ?>
                                </div>

                                <input
                                        id="vehica-<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>-loan-calculator__contribution"
                                        type="text"
                                    <?php if (!empty($vehicaCurrentWidget->getInitialDownPayment())): ?>
                                        value="<?php echo esc_attr($vehicaCurrentWidget->getInitialDownPayment()); ?>"
                                    <?php endif; ?>
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div>
                <div class="vehica-loan-calculator__separator"></div>

                <div class="vehica-loan-calculator__results--wrapper">
                    <div class="vehica-loan-calculator__results">
                        <div class="vehica-loan-calculator__results__col">
                            <h3><?php echo esc_html(vehicaApp('monthly_payment_string')); ?></h3>
                            <h4 v-if="!props.showResults">-</h4>
                            <template>
                                <h4 v-if="props.showResults">{{ props.installment }}</h4>
                            </template>
                        </div>

                        <div class="vehica-loan-calculator__results__col">
                            <h3><?php echo esc_html(vehicaApp('total_interest_string')); ?></h3>
                            <h4 v-if="!props.showResults">-</h4>
                            <template>
                                <h4 v-if="props.showResults">{{ props.interest }}</h4>
                            </template>
                        </div>

                        <div class="vehica-loan-calculator__results__col">
                            <h3><?php echo esc_html(vehicaApp('total_payments_string')); ?></h3>
                            <h4 v-if="!props.showResults">-</h4>
                            <template>
                                <h4 v-if="props.showResults">
                                    {{ props.total }}
                                </h4>
                            </template>
                        </div>
                    </div>
                </div>
                <?php if ($vehicaCurrentWidget->hasTextAfter()) : ?>
                    <div class="vehica-loan-calculator__end-text">
                        <?php echo wp_kses_post($vehicaCurrentWidget->getTextAfter()); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </vehica-loan-calculator>
</div>