<vehica-car-terms
        :taxonomy="field"
        :post="props.fieldsUser"
        :validation="props.validation"
        placeholder="<?php esc_attr_e('Choose from the list or add new', 'vehica-core'); ?>"
        create-string="<?php esc_attr_e('Create', 'vehica-core'); ?>"
>
    <div
            slot-scope="taxonomyProps"
            class="vehica-field vehica-edit__section"
            :class="taxonomyProps.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading" :for="field.key">
                {{ field.name }}
                <span v-if="field.isRequired">*</span>
            </label>
            <div>
                <select
                        :id="field.key"
                        :name="field.key + '[]'"
                        :multiple="field.allowMultiple"
                >
                </select>
            </div>
        </div>
    </div>
</vehica-car-terms>
