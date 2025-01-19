<?php
if (!defined('ABSPATH')) {
    exit;
}
/* @var \Vehica\Field\FieldsManager $vehicaFieldsManager */
/* @var \Vehica\Model\Post\Car $vehicaFieldsUser */
?>
<input type="hidden" name="vehica_source" value="backend">

<vehica-hide-when-loaded>
    <div slot-scope="hideWhenLoadedProps">

        <div v-if="hideWhenLoadedProps.show">
            <div class="vehica-loading vehica-loading--single"></div>
        </div>

        <template>
            <vehica-sections
                    class="vehica-edit"
                    :fields="<?php echo htmlspecialchars(json_encode(vehicaApp('car_fields'))); ?>"
                    :opt-fields-user="<?php echo htmlspecialchars(json_encode($vehicaFieldsUser)); ?>"
                    required-fields-msg="<?php esc_attr_e('The required fields have not been filled out:', 'vehica-core'); ?>"
            >
                <div slot-scope="props" class="flex">
                    <input
                            name="vehica_save_car"
                            type="hidden"
                            value="<?php echo esc_attr(wp_create_nonce('vehica_save_car')); ?>"
                    >

                    <div class="vehica-edit">
                        <div class="vehica-edit__inner">
                            <div class="vehica-field vehica-edit__section">
                                <div class="vehica-edit__section__inner">
                                    <label class="vehica-edit__section__heading">
                                        <?php esc_html_e('Featured', 'vehica-core'); ?>
                                    </label>
                                    <input
                                            name="vehica_featured"
                                            type="checkbox"
                                            value="1"
                                        <?php if ($vehicaFieldsUser->isFeatured()) : ?>
                                            checked
                                        <?php endif; ?>
                                    >
                                </div>
                            </div>

                            <template v-for="field in props.fields" v-if="props.showField(field)">
                                <?php foreach (vehicaApp('field_types') as $vehicaFieldType) :
                                    /* @var \Vehica\Field\FieldType $vehicaFieldType */
                                    ?>
                                    <template v-if="field.type === '<?php echo esc_attr($vehicaFieldType->getKey()); ?>'">
                                        <?php require vehicaApp('views_path') . 'fields/' . $vehicaFieldType->getKey() . '.php'; ?>
                                    </template>
                                <?php endforeach; ?>
                            </template>
                        </div>
                    </div>

                    <div class="vehica-edit__save">
                        <vehica-update-car>
                            <button
                                    slot-scope="updateCar"
                                    @click.prevent="updateCar.onUpdateCar"
                                    class="button button-primary button-large"
                            >
                                <?php
                                global $post;
                                if ($post->post_status === \Vehica\Core\Post\PostStatus::PUBLISH) : ?>
                                    <?php esc_html_e('Publish', 'vehica-core'); ?>
                                <?php else : ?>
                                    <?php esc_html_e('Update', 'vehica-core'); ?>
                                <?php endif; ?>
                            </button>
                        </vehica-update-car>
                    </div>

                </div>
            </vehica-sections>
        </template>
    </div>
</vehica-hide-when-loaded>