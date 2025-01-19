<?php
/* @var \Vehica\Panel\PanelField\DateTimePanelField $vehicaPanelField */
global $vehicaPanelField;
/* @var \Vehica\Model\Post\Field\DateTimeField $vehicaDateTimeField */
$vehicaDateTimeField = $vehicaPanelField->getField();
$vehicaValueType = $vehicaDateTimeField->getValueType();

if ($vehicaDateTimeField->isRange()) : ?>
    <div
        <?php if ($vehicaValueType === \Vehica\Model\Post\Field\DateTimeField::TYPE_DATE_TIME) : ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form__grid-element--row vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php elseif ($vehicaValueType === \Vehica\Model\Post\Field\DateTimeField::TYPE_DATE): ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form__grid-element--2of3 vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php elseif ($vehicaValueType === \Vehica\Model\Post\Field\DateTimeField::TYPE_TIME): ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form__grid-element--2of3 vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php else : ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php endif; ?>
    >
        <div class="vehica-number-range-v2">
            <vehica-date-time-panel-field
                    :car="carForm.car"
                    :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
                    time-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getTimeFormat()); ?>"
                    date-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getDateFormat()); ?>"
                    :start-of-week="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getStartOfWeek()); ?>"
                    field-key="from"
                    :strings="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getStrings())); ?>"
                    class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--left"
            >
                <div
                        slot-scope="dateTimeField"
                        :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
                >
                    <label
                            for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        <?php if ($vehicaPanelField->isRequired()) : ?>
                            class="vehica-car-form__label vehica-car-form__label--required"
                        <?php else : ?>
                            class="vehica-car-form__label"
                        <?php endif; ?>
                    >
                        <?php echo esc_html($vehicaPanelField->getLabel() . ' ' . vehicaApp('from_string')); ?>
                    </label>

                    <div
                            class="vehica-date-field__inputs-wrapper"
                            :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
                            class="vehica-date-field__inputs-wrapper"
                    >
                        <?php if ($vehicaValueType === 'date' || $vehicaValueType === 'datetime') : ?>
                            <div class="vehica-date-field__inputs-wrapper__field">
                                <input
                                        class="vehica-date-picker"
                                        type="text"
                                        placeholder="<?php echo esc_attr(vehicaApp('select_date_string')); ?>"
                                >
                                <template>
                                    <i
                                            v-if="dateTimeField.showClearDate"
                                            class="fas fa-times vehica-date-field__clear"
                                            @click.prevent="dateTimeField.clearDate"
                                    ></i>
                                </template>
                            </div>
                        <?php endif; ?>

                        <?php if ($vehicaValueType === 'time' || $vehicaValueType === 'datetime') : ?>
                            <div class="vehica-date-field__inputs-wrapper__field">
                                <input
                                        class="vehica-time-picker"
                                        type="text"
                                        placeholder="<?php echo esc_attr(vehicaApp('select_time_string')); ?>"
                                >
                                <template>
                                    <i
                                            v-if="dateTimeField.showClearTime"
                                            class="fas fa-times vehica-date-field__clear"
                                            @click.prevent="dateTimeField.clearTime"
                                    ></i>
                                </template>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </vehica-date-time-panel-field>

            <vehica-date-time-panel-field
                    :car="carForm.car"
                    :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
                    time-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getTimeFormat()); ?>"
                    date-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getDateFormat()); ?>"
                    :start-of-week="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getStartOfWeek()); ?>"
                    field-key="to"
                    :strings="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getStrings())); ?>"
                    class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right"
            >
                <div
                        slot-scope="dateTimeField"
                        :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
                >
                    <label
                            for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        <?php if ($vehicaPanelField->isRequired()) : ?>
                            class="vehica-car-form__label vehica-car-form__label--required"
                        <?php else : ?>
                            class="vehica-car-form__label"
                        <?php endif; ?>
                    >
                        <?php echo esc_html($vehicaPanelField->getLabel() . ' ' . vehicaApp('to_string')); ?>
                    </label>

                    <div
                            :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
                            class="vehica-date-field__inputs-wrapper"
                    >
                        <?php if ($vehicaValueType === 'date' || $vehicaValueType === 'datetime') : ?>
                            <div class="vehica-date-field__inputs-wrapper__field">
                                <input
                                        class="vehica-date-picker"
                                        type="text"
                                        placeholder="<?php echo esc_attr(vehicaApp('select_date_string')); ?>"
                                >
                                <template>
                                    <i
                                            v-if="dateTimeField.showClearDate"
                                            class="fas fa-times vehica-date-field__clear"
                                            @click.prevent="dateTimeField.clearDate"
                                    ></i>
                                </template>
                            </div>
                        <?php endif; ?>

                        <?php if ($vehicaValueType === 'time' || $vehicaValueType === 'datetime') : ?>
                            <div class="vehica-date-field__inputs-wrapper__field">
                                <input
                                        class="vehica-time-picker"
                                        type="text"
                                        placeholder="<?php echo esc_attr(vehicaApp('select_time_string')); ?>"
                                >
                                <template>
                                    <i
                                            v-if="dateTimeField.showClearTime"
                                            class="fas fa-times vehica-date-field__clear"
                                            @click.prevent="dateTimeField.clearTime"
                                    ></i>
                                </template>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </vehica-date-time-panel-field>
        </div>
    </div>
<?php else : ?>
    <div
        <?php if ($vehicaValueType === \Vehica\Model\Post\Field\DateTimeField::TYPE_DATE_TIME) : ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form__grid-element--2of3 vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php else : ?>
            class="vehica-car-form__grid-element vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaDateTimeField->getKey()); ?>"
        <?php endif; ?>
    >
        <vehica-date-time-panel-field
                :car="carForm.car"
                :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
                time-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getTimeFormat()); ?>"
                date-format="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getDateFormat()); ?>"
                :start-of-week="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::getStartOfWeek()); ?>"
                :strings="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getStrings())); ?>"
        >
            <div
                    slot-scope="dateTimeField"
                    :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
            >
                <label
                        for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    <?php if ($vehicaPanelField->isRequired()) : ?>
                        class="vehica-car-form__label vehica-car-form__label--required"
                    <?php else : ?>
                        class="vehica-car-form__label"
                    <?php endif; ?>
                >
                    <?php echo esc_html($vehicaPanelField->getLabel()); ?>
                </label>

                <div
                        class="vehica-date-field__inputs-wrapper"
                        :class="{'vehica-has-error': carForm.showErrors && dateTimeField.hasError}"
                >
                    <?php if ($vehicaValueType === 'date' || $vehicaValueType === 'datetime') : ?>
                        <div class="vehica-date-field__inputs-wrapper__field">
                            <input
                                    class="vehica-date-picker"
                                    type="text"
                                    placeholder="<?php echo esc_attr(vehicaApp('select_date_string')); ?>"
                            >
                            <template>
                                <i
                                        v-if="dateTimeField.showClearDate"
                                        class="fas fa-times vehica-date-field__clear"
                                        @click.prevent="dateTimeField.clearDate"
                                ></i>
                            </template>
                        </div>

                    <?php endif; ?>

                    <?php if ($vehicaValueType === 'time' || $vehicaValueType === 'datetime') : ?>
                        <div class="vehica-date-field__inputs-wrapper__field">
                            <input
                                    class="vehica-time-picker"
                                    type="text"
                                    placeholder="<?php echo esc_attr(vehicaApp('select_time_string')); ?>"
                            >
                            <template>
                                <i
                                        v-if="dateTimeField.showClearTime"
                                        class="fas fa-times vehica-date-field__clear"
                                        @click.prevent="dateTimeField.clearTime"
                                ></i>
                            </template>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </vehica-date-time-panel-field>
    </div>
<?php
endif;