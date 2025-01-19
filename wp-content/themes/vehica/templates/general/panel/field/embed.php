<?php
/* @var \Vehica\Panel\PanelField\EmbedPanelField $vehicaPanelField */
global $vehicaPanelField;
?>

<vehica-embed-panel-field
        :car="carForm.car"
        :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_embed_preview')); ?>"
>
    <div
            slot-scope="embedField"
            :class="{'vehica-has-error': carForm.showErrors && embedField.hasError}"
    >
        <div class="vehica-car-form__embed-wrapper">
            <label
                    for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                <?php if ($vehicaPanelField->isRequired()) : ?>
                    class="vehica-car-form__label vehica-car-form__label--required"
                <?php else : ?>
                    class="vehica-car-form__label"
                <?php endif; ?>
            >
                <?php echo esc_html($vehicaPanelField->getLabel()); ?>
                <?php echo esc_html(vehicaApp('copy_any_video_link_string')); ?>
            </label>

            <div class="vehica-car-form__field-wrapper vehica-car-form__field-wrapper--embed">
                <input
                        id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        class="vehica-car-form__field"
                        name="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                        type="text"
                        @input="embedField.setUrl($event.target.value)"
                        :value="embedField.value.url"
                    <?php if ($vehicaPanelField->hasPlaceholder()) : ?>
                        placeholder="<?php echo esc_html($vehicaPanelField->getPlaceholder()); ?>"
                    <?php else : ?>
                        placeholder="<?php echo esc_html(vehicaApp('video_link_string')); ?>"
                    <?php endif; ?>
                        @keypress.enter.prevent
                >
            </div>

            <template>
                <div
                        v-if="embedField.value.embed !== ''"
                        class="vehica-car-form__embed"
                        v-html="embedField.value.embed"
                ></div>
            </template>
        </div>
    </div>
</vehica-embed-panel-field>