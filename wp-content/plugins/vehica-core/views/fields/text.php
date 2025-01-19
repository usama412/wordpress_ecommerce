<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-text
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :validation="props.validation"
>
    <div
            slot-scope="fieldProps"
            class="vehica-field vehica-edit__section"
            :class="fieldProps.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading">
                {{ fieldProps.name }} <span v-if="field.isRequired">*</span>
            </label>
            
            <input
                    :name="fieldProps.key"
                    :value="fieldProps.value"
                    type="text"
                    :placeholder="fieldProps.placeholder"
                    @input="fieldProps.setValue($event.target.value)"
                    @keypress.enter.prevent
            >
        </div>
    </div>
</vehica-text>