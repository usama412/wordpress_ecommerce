<?php
/* @var \Vehica\Panel\PanelField\NumberPanelField $vehicaPanelField */
global $vehicaPanelField;
/* @var \Vehica\Model\Post\Field\NumberField $vehicaNumberField */
$vehicaNumberField = $vehicaPanelField->getField();
?>
<vehica-number-panel-field
        :car="carForm.car"
        :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
        decimal-separator="<?php echo esc_attr(vehicaApp('decimal_separator')); ?>"
>
    <div slot-scope="numberField">
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
                class="vehica-car-form__field-wrapper"
                :class="{'vehica-has-error': carForm.showErrors && numberField.hasError}"
        >
            <input
                    id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    class="vehica-car-form__field"
                    name="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    type="text"
                    @input="numberField.setValue($event.target.value)"
                    :value="numberField.value"
                    placeholder="<?php echo esc_attr($vehicaPanelField->getPlaceholder()); ?>"
                    @keypress.enter.prevent
            >
            <?php if ($vehicaNumberField->hasDisplayAfter()) : ?>
                <div class="vehica-car-form__field-units">
                    <?php echo esc_html($vehicaNumberField->getDisplayAfter()); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</vehica-number-panel-field>