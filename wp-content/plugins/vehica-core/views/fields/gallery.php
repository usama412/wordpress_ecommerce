<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-gallery
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        title-text="<?php esc_html_e('Select or Upload Media', 'vehica-core'); ?>"
        button-text="<?php esc_html_e('Use this media', 'vehica-core'); ?>"
        :validation="props.validation"
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/gallery/fetch')); ?>"
>
    <div
            slot-scope="gallery"
            class="vehica-field vehica-edit__section vehica-edit__section--gallery"
            :class="gallery.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label class="vehica-edit__section__heading">
                <i class="fas fa-camera"></i> {{ field.name }}
                <span v-if="field.isRequired">*</span>
            </label>

            <input type="hidden" :name="gallery.key" :value="gallery.value">

            <template>
                <input v-if="gallery.ready" type="hidden" :name="gallery.key + '_loaded'" value="1">
            </template>

            <vehica-draggable
                    :data-name="field.name"
                    :list="gallery.images"
                    :options="{group:'gallery'}"
                    class="vehica-gallery"
            >
                <div
                        v-for="image in gallery.images"
                        :class="{'is-marked':gallery.isImageMarked(image.id)}"
                        :key="image.id"
                        class="vehica-gallery__single"
                >
                    <div class="vehica-gallery__single__inner">
                        <i @click.prevent="gallery.removeImage(image)" class="fas fa-trash" aria-hidden="true"></i>
                        <img :src="image.url" alt="">
                    </div>
                </div>
            </vehica-draggable>

            <button @click.prevent="gallery.openMedia" class="vehica-gallery__add-images">
                <i class="fas fa-plus-circle"></i>
                <?php esc_html_e('Add Images', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</vehica-gallery>