<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-location
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :validation="props.validation"
        :snazzy="field.snazzyAdd"
>
    <div
            slot-scope="fieldProps"
            class="vehica-field vehica-edit__section vehica-edit__section--location"
            :class="fieldProps.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner vehica-edit-location">
            <label class="vehica-edit__section__heading">
                {{ field.name }} <span v-if="field.isRequired">*</span>
            </label>

            <?php if (empty(vehicaApp('google_maps_api_key'))) : ?>
                <div><?php esc_html_e('Google Map API Key not set - please add it in the Vehica Panel > Google Maps ', 'vehica-core'); ?></div>
            <?php else : ?>
                <input
                        :id="'vehica-address-' + field.id"
                        :name="fieldProps.field.key + '[address]'"
                        type="text"
                        @input="fieldProps.setAddress($event.target.value)"
                        :value="fieldProps.address"
                >

                <div class="vehica-edit-location__label">
                    <input
                            type="checkbox"
                            :checked="fieldProps.markerChangeAddress"
                            @change="fieldProps.setMarkerChangeAddress"
                    >
                    <span><?php esc_html_e('Autocomplete address when marker position is changed ', 'vehica-core'); ?></span>
                </div>

                <input type="hidden" :name="fieldProps.field.key + '[position][lat]'" :value="fieldProps.position.lat">

                <input type="hidden" :name="fieldProps.field.key + '[position][lng]'" :value="fieldProps.position.lng">

                <div
                        :id="'vehica-map-' + field.id"
                        class="vehica-edit-location__map"
                ></div>
            <?php endif; ?>
        </div>
    </div>
</vehica-location>
