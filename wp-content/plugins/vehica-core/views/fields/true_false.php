<vehica-true-false
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :validation="props.validation"
        class="vehica-edit__section vehica-edit__section--true-false"
>
    <div slot-scope="fieldProps">
        <div  class="vehica-edit__section__inner">
            <label @click="fieldProps.onClick" class="vehica-edit__section__heading">
                {{ field.name }}
            </label>
            <input
                    :name="field.key"
                    :checked="fieldProps.isChecked"
                    @click.prevent="fieldProps.onClick"
                    type="checkbox"
                    value="1"
            >
        </div>
    </div>
</vehica-true-false>