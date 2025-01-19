<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-attachments
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        title-text="<?php esc_html_e('Select or Upload Media', 'vehica-core'); ?>"
        button-text="<?php esc_html_e('Use this media', 'vehica-core'); ?>"
        :validation="props.validation"
>
    <div
            slot-scope="attachments"
            class="vehica-field vehica-edit__section vehica-edit__section--gallery"
            :class="attachments.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading">
                <i class="fas fa-camera"></i> {{ field.name }}
                <span v-if="field.isRequired">*</span>
            </label>

            <input type="hidden" :name="attachments.key" :value="attachments.value">

            <template>
                <input v-if="attachments.ready" type="hidden" :name="attachments.key + '_loaded'" value="1">
            </template>

            <vehica-draggable
                    :data-name="field.name"
                    :list="attachments.attachments"
                    :options="{group:'attachments'}"
                    class="vehica-gallery"
            >
                <div
                        v-for="attachment in attachments.attachments"
                        :class="{'is-marked': attachments.isAttachmentMarked(attachment.id)}"
                        :key="attachment.id"
                        class="vehica-gallery__single"
                >
                    <div class="vehica-gallery__single__inner">
                        <i
                                @click.prevent="attachments.removeAttachment(attachment)"
                                class="fas fa-trash"
                                aria-hidden="true"
                        ></i>

                        <div class="vehica-gallery__attachment-name">
                            {{ attachment.filename }}
                        </div>
                    </div>
                </div>
            </vehica-draggable>

            <button @click.prevent="attachments.openMedia" class="vehica-gallery__add-images">
                <i class="fas fa-plus-circle"></i>

                <?php esc_html_e('Add Attachments', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</vehica-attachments>
