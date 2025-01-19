<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-price
        v-for="priceField in field.fields"
        :key="priceField.key"
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :price-key="priceField.key"
        :validation="props.validation"
>
    <div
            slot-scope="priceFieldProps"
            class="vehica-field vehica-edit__section"
            :class="priceFieldProps.classes"
            :data-name="priceField.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading">
                {{ priceField.name }} <span v-if="field.isRequired">*</span>
            </label>
            <input
                    :placeholder="priceField.name"
                    :name="field.key + '[' + priceField.key + ']'"
                    :value="priceFieldProps.value"
                    @input="priceFieldProps.setValue($event.target.value, priceField)"
                    type="text"
                    @keypress.enter.prevent
            >
        </div>
    </div>
</vehica-price>