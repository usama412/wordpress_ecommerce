<?php
/* @var \Vehica\Panel\PanelField\LocationPanelField $vehicaPanelField */
global $vehicaPanelField;

/* @var \Vehica\Model\Post\Field\LocationField $vehicaLocationField */
$vehicaLocationField = $vehicaPanelField->getField();

if (empty(vehicaApp('google_maps_api_key'))) {
    return;
}
?>
<div class="vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaLocationField->getKey()); ?>">
    <div class="vehica-car-form__location">
        <h3
            <?php if ($vehicaPanelField->isRequired()) : ?>
                class="vehica-car-form__section-title vehica-car-form__section-title--required"
            <?php else : ?>
                class="vehica-car-form__section-title"
            <?php endif; ?>
        >
            <?php echo esc_html($vehicaPanelField->getLabel()); ?>
        </h3>

        <vehica-location-panel-field
                :car="carForm.car"
                :field="<?php echo htmlspecialchars(json_encode($vehicaLocationField)); ?>"
                map-type="<?php echo esc_attr($vehicaLocationField->getMapType()); ?>"
                :snazzy="<?php echo esc_attr(vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected($vehicaLocationField->getKey() . '_add') ? 'true' : 'false'); ?>"
        >
            <div
                    slot-scope="locationField"
                    class="vehica-car-form__section"
                    :class="{'vehica-has-error': carForm.showErrors && locationField.hasError}"
            >
                <div class="vehica-car-form__location__address-field">
                    <input
                            id="vehica-car-form__address--<?php echo esc_attr($vehicaLocationField->getId()); ?>"
                            type="text"
                            :value="locationField.address"
                            placeholder="<?php echo esc_attr(vehicaApp('enter_location_string')); ?>"
                    >
                </div>

                <div class="vehica-checkbox">
                    <input
                            type="checkbox"
                            @change="locationField.setMarkerChangeAddress"
                            :checked="locationField.markerChangeAddress"
                            id="vehica-locaton-sync-<?php echo esc_attr($vehicaLocationField->getId()); ?>"
                    >

                    <label for="vehica-locaton-sync-<?php echo esc_attr($vehicaLocationField->getId()); ?>">
                        <?php echo esc_html(vehicaApp('autocomplete_map_string')); ?>
                    </label>
                </div>

                <div
                        id="vehica-car-form__map--<?php echo esc_attr($vehicaLocationField->getId()); ?>"
                        class="vehica-car-form__location__map"
                ></div>
            </div>
        </vehica-location-panel-field>
    </div>
</div>