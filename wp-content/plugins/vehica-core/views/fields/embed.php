<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<vehica-embed
        :opt-fields-user="props.fieldsUser"
        :opt-field="field"
        :validation="props.validation"
>
    <div
            slot-scope="fieldProps"
            class="vehica-field vehica-edit__section vehica-edit__section--embed"
            :class="fieldProps.classes"
            :data-name="field.name"
    >
        <div class="vehica-edit__section__inner">
            <label
                    for="<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::KEY); ?>"
                    class="vehica-edit__section__heading"
            >
                {{ field.name }}
                <span v-if="field.isRequired">*</span>
            </label>
            <div>
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::KEY); ?>"
                        :data-name="field.name"
                        class="da-field"
                        :class="fieldProps.classes"
                        :name="fieldProps.field.key + '[<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::URL); ?>]'"
                        type="text"
                        :value="fieldProps.url"
                        @input="fieldProps.setUrl($event.target.value)"
                        placeholder="Enter link (Youtube / Vimeo / Twitter / link to .mp4)"
                        @keypress.enter.prevent
                >
                <input
                        :name="fieldProps.field.key + '[<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::EMBED); ?>]'"
                        :value="fieldProps.embed"
                        type="hidden"
                >
            </div>
            <div v-if="fieldProps.loading">
                <?php esc_html_e('Loading...', 'vehica-core'); ?>
            </div>
            <div v-if="!fieldProps.loading" v-html="fieldProps.embed"></div>
        </div>
    </div>
</vehica-embed>