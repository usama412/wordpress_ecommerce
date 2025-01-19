<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
/* @var \Vehica\Panel\PanelField\GalleryPanelField $vehicaPanelField */
global $vehicaCurrentWidget, $vehicaPanelField;
?>
<div class="vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaPanelField->getKey()); ?>">
    <div class="vehica-car-form__tip-title">
        <h3
            <?php if ($vehicaPanelField->isRequired()) : ?>
                class="vehica-car-form__section-title vehica-car-form__section-title--required"
            <?php else : ?>
                class="vehica-car-form__section-title"
            <?php endif; ?>
        >
            <?php echo esc_html($vehicaPanelField->getLabel()); ?>
        </h3>

        <?php if ($vehicaCurrentWidget->hasGalleryTip()) : ?>
            <div class="vehica-car-form__tip">
                <div class="vehica-car-form__tip__title">
                    <div><i class="far fa-lightbulb"></i></div>

                    <div><?php echo esc_html(vehicaApp('quick_tips_string')); ?></div>
                </div>

                <div class="vehica-car-form__tip__content">
                    <?php echo wp_kses_post($vehicaCurrentWidget->getGalleryTip()); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <vehica-gallery-panel-field
            :car="carForm.car"
            :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_upload_image')); ?>"
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_gallery_info')); ?>"
    >
        <div
                slot-scope="galleryField"
                class="vehica-car-form__section vehica-car-form__section--gallery"
                :class="{'vehica-has-error': carForm.showErrors && galleryField.hasError}"
        >
            <vehica-dropzone
                    id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    :options="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getDropZoneConfig())); ?>"
                    @vdropzone-sending="galleryField.onSending"
                    @vdropzone-success="galleryField.onSuccess"
                    @vdropzone-removed-file="galleryField.onRemove"
                    @vdropzone-complete="galleryField.onComplete"
            >
            </vehica-dropzone>

            <template>
                <div class="vehica-car-form__gallery__bottom">
                    <div class="vehica-car-form__gallery__counter">
                        {{ galleryField.value.length
                        }}/<?php echo esc_html(vehicaApp('settings_config')->getMaxImageNumber()); ?>
                    </div>
                    <div
                            v-if="galleryField.value.length < <?php echo esc_attr(vehicaApp('settings_config')->getMaxImageNumber()); ?> && galleryField.value.length > 0"
                            @click="galleryField.onOpen"
                            class="vehica-car-form__gallery__add-photos"
                    >
                        <i class="far fa-images"></i> <?php echo esc_attr(vehicaApp('add_more_images_string')); ?>
                    </div>
                </div>
            </template>
        </div>
    </vehica-gallery-panel-field>
</div>