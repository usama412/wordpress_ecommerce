<?php
/* @var \Vehica\Panel\PanelField\PricePanelField $vehicaPanelField */

global $vehicaPanelField;
/* @var \Vehica\Model\Post\Field\Price\PriceField $vehicaField */
$vehicaField = $vehicaPanelField->getField();
?>
<?php foreach ($vehicaField->getPrices() as $vehicaPrice) :/* @var \Vehica\Field\Fields\Price\Price $vehicaPrice */ ?>
    <div class="vehica-car-form__grid-element vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaField->getKey()); ?>">
        <vehica-price-panel-field
                :car="carForm.car"
                :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
                price-key="<?php echo esc_attr($vehicaPrice->getKey()); ?>"
                :decimal-places="<?php echo esc_attr($vehicaPrice->getCurrency()->getDecimalPlaces()); ?>"
                decimal-separator="<?php echo esc_attr($vehicaPrice->getCurrency()->getDecimalSeparator()); ?>"
        >
            <div
                    slot-scope="priceField"
                    class="vehica-car-form__price-field"
                    :class="{'vehica-has-error': carForm.showErrors && priceField.hasError}"
            >
                <label
                        for="<?php echo esc_attr($vehicaPrice->getKey()); ?>"
                    <?php if ($vehicaPanelField->isRequired()) : ?>
                        class="vehica-car-form__label vehica-car-form__label--required"
                    <?php else : ?>
                        class="vehica-car-form__label"
                    <?php endif; ?>
                >
                    <?php echo esc_html($vehicaPanelField->getLabel()); ?>
                    (<?php echo esc_html($vehicaPrice->getCurrency()->getName()) ?>)
                </label>

                <div class="vehica-car-form__field-wrapper">
                    <input
                            id="<?php echo esc_attr($vehicaPrice->getKey()); ?>"
                            class="vehica-car-form__field vehica-car-form__field--price"
                            name="<?php echo esc_attr($vehicaPrice->getKey()); ?>"
                            type="text"
                            :value="priceField.price"
                            @input="priceField.setPrice($event.target.value)"
                            placeholder="<?php echo esc_attr($vehicaPanelField->getPlaceholder()); ?>"
                            @keypress.enter.prevent
                    >

                    <div class="vehica-car-form__field-units">
                        <?php echo esc_html($vehicaPrice->getSign()); ?>
                    </div>
                </div>
            </div>
        </vehica-price-panel-field>
    </div>
<?php endforeach; ?>
