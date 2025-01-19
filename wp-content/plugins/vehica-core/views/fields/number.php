<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-number
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :validation="props.validation"
        decimal-separator="<?php echo esc_attr(vehicaApp('decimal_separator')); ?>"
        thousands-separator="<?php echo esc_attr(vehicaApp('thousands_separator')); ?>"
>
    <div
            slot-scope="fieldProps"
            class="vehica-field vehica-edit__section"
            :class="fieldProps.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading">
                {{ fieldProps.name }}
                <span v-if="field.isRequired">*</span>
            </label>

            <input
                    :name="fieldProps.key"
                    type="text"
                    :placeholder="fieldProps.name"
                    :value="fieldProps.value"
                    @input="fieldProps.setValue($event.target.value)"
                    @keypress.enter.prevent
            >
        </div>
    </div>
</vehica-number>