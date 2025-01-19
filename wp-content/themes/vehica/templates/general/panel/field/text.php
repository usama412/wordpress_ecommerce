<?php
/* @var \Vehica\Panel\PanelField\TextPanelField $vehicaPanelField */
global $vehicaPanelField;
?>
<vehica-text-panel-field
        :car="carForm.car"
        :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
>
    <div slot-scope="textField" :class="{'vehica-has-error': carForm.showErrors && textField.hasError}">
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

        <input
                id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                class="vehica-car-form__field"
                name="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                type="text"
                @input="textField.setValue($event.target.value)"
                :value="textField.value"
                placeholder="<?php echo esc_attr($vehicaPanelField->getPlaceholder()); ?>"
                @keypress.enter.prevent
        >
    </div>
</vehica-text-panel-field>