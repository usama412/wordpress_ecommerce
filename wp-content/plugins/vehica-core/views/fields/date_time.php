<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-date-time
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
                {{ field.name }}
                <span v-if="field.isRequired">*</span>
            </label>
            <date-picker
                    :type="field.valueType"
                    :first-day-of-week="field.weekStart"
                    :range="field.isRange"
                    :shortcuts="false"
                    :lang="<?php echo htmlspecialchars(json_encode(\Vehica\Model\Post\Field\DateTimeField::getTranslation())); ?>"
                    :format="fieldProps.format"
                    @input="fieldProps.setValue($event)"
                    :value="fieldProps.value"
                    :input-name="field.key"
                    :placeholder="field.placeholder"
                    :show-second="false"
            ></date-picker>

            <template v-if="field.isRange">
                <input type="hidden" :name="field.key + '[]'" :value="fieldProps.valueFrom">
                <input type="hidden" :name="field.key + '[]'" :value="fieldProps.valueTo">
            </template>

            <template v-if="!field.isRange">
                <input type="hidden" :name="field.key" :value="fieldProps.value">
            </template>
        </div>
    </div>
</vehica-date-time>