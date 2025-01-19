<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
/* @var \Vehica\Panel\PanelField\AttachmentsPanelField $vehicaPanelField */
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

        <?php if ($vehicaCurrentWidget->hasAttachmentsTip()) : ?>
            <div class="vehica-car-form__tip">
                <div class="vehica-car-form__tip__title">
                    <div><i class="far fa-lightbulb"></i></div>

                    <div><?php echo esc_html(vehicaApp('quick_tips_string')); ?></div>
                </div>

                <div class="vehica-car-form__tip__content">
                    <?php echo wp_kses_post($vehicaCurrentWidget->getAttachmentsTip()); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <vehica-attachments-panel-field
            :car="carForm.car"
            :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_upload_attachment')); ?>"
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/attachments/info')); ?>"
            pdf-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/pdf.svg'); ?>"
            xls-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/xls.svg'); ?>"
            doc-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/doc.svg'); ?>"
            jpg-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/jpg.svg'); ?>"
            png-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/png.svg'); ?>"
            zip-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/zip.svg'); ?>"
            other-icon="<?php echo esc_url(get_template_directory_uri() . '/assets/img/other_file_type.svg'); ?>"
    >
        <div
                slot-scope="attachmentsField"
                class="vehica-car-form__section vehica-car-form__section--gallery vehica-car-form__section--attachment"
                :class="{'vehica-has-error': carForm.showErrors && attachmentsField.hasError}"
        >
            <vehica-dropzone
                    id="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
                    :options="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getDropZoneConfig())); ?>"
                    @vdropzone-sending="attachmentsField.onSending"
                    @vdropzone-thumbnail="attachmentsField.onAddedFile"
                    @vdropzone-success="attachmentsField.onSuccess"
                    @vdropzone-file-added="attachmentsField.onAddedFile"
                    @vdropzone-removed-file="attachmentsField.onRemove"
                    @vdropzone-complete="attachmentsField.onComplete"
            >
            </vehica-dropzone>

            <template>
                <div class="vehica-car-form__gallery__bottom">
                    <div class="vehica-car-form__gallery__counter">
                        {{ attachmentsField.value.length
                        }}/<?php echo esc_html(vehicaApp('settings_config')->getMaxAttachmentNumber()); ?>
                    </div>

                    <div
                            v-if="attachmentsField.value.length < <?php echo esc_attr(vehicaApp('settings_config')->getMaxAttachmentNumber()); ?> && attachmentsField.value.length > 0"
                            @click="attachmentsField.onOpen"
                            class="vehica-car-form__gallery__add-photos"
                    >
                        <i class="fas fa-paperclip"></i> <?php echo esc_attr(vehicaApp('add_more_attachments_string')); ?>
                    </div>
                </div>
            </template>
        </div>
    </vehica-attachments-panel-field>
</div>