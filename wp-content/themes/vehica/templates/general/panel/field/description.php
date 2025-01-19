<?php
/* @var \Vehica\Panel\PanelField\DescriptionPanelField $vehicaPanelField */
global $vehicaPanelField;

if (vehicaApp('settings_config')->getDescriptionType() === 'rich') :?>
    <vehica-description-panel-field
            :car="carForm.car"
            :is-required="<?php echo esc_attr($vehicaPanelField->isRequired() ? 'true' : 'false'); ?>"
            :is-advanced="true"
    >
        <div
                slot-scope="descriptionField"
                :class="{'vehica-has-error': carForm.showErrors && descriptionField.hasError}"
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

            <div>
                <?php wp_editor('', 'vehica_description', $vehicaPanelField->getEditorConfig()); ?>
            </div>
        </div>
    </vehica-description-panel-field>
<?php else : ?>
    <vehica-description-panel-field
            :car="carForm.car"
            :is-required="<?php echo esc_attr($vehicaPanelField->isRequired() ? 'true' : 'false'); ?>"
            :is-advanced="false"
    >
        <div
                slot-scope="descriptionField"
                :class="{'vehica-has-error': carForm.showErrors && descriptionField.hasError}"
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

            <div>
                <textarea
                        id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        class="vehica-car-form__field vehica-car-form__field--textarea"
                        name="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        cols="80"
                        rows="10"
                        @input="descriptionField.setValue($event.target.value)"
                        :value="descriptionField.value"
                        placeholder="<?php echo esc_attr(vehicaApp('enter_listing_description_string')); ?>"
                ></textarea>
            </div>
        </div>
    </vehica-description-panel-field>
<?php endif; ?>
