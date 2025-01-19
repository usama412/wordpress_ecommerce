<?php
/* @var \Vehica\Panel\PanelField\NamePanelField $vehicaPanelField */

global $vehicaPanelField;
?>
<vehica-name-panel-field :car="carForm.car">
    <div slot-scope="nameField" class="vehica-car-form-field__name">
        <label
                for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                class="vehica-car-form__label vehica-car-form__label--required"
        >
            <?php echo esc_html($vehicaPanelField->getLabel()); ?>
        </label>

        <div
                class="vehica-car-form__field-wrapper"
                :class="{'vehica-has-error': carForm.showErrors && nameField.hasError}"
        >
            <input
                    id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    class="vehica-car-form__field"
                    name="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    type="text"
                    @input="nameField.setValue($event.target.value)"
                    :value="nameField.value"
                    placeholder="<?php echo esc_attr(vehicaApp('enter_listing_title_string')); ?>"
                    @keypress.enter.prevent
            >
        </div>
    </div>
</vehica-name-panel-field>