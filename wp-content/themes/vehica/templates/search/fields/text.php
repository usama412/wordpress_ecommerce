<?php
/* @var \Vehica\Search\Field\TextSearchField $vehicaSearchField */
global $vehicaSearchField;
?>
<div class="vehica-results__field vehica-relation-field">
    <vehica-text-search-field
            :text-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
            :filters="searchFormProps.filters"
    >
        <div
                slot-scope="textField"
                class="vehica-text-field"
                :class="{'vehica-text-field-active': textField.value !== ''}"
        >
            <input
                    @input="textField.onValueChange"
                    :value="textField.value"
                    type="text"
                    placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
            >

            <template>
                <span
                        v-if="textField.value !== ''"
                        class="vehica-form-button__clear vehica-form-button__clear--text"
                        @click.prevent="textField.onClear"
                >
                    <i class="fas fa-times"></i>
                </span>
            </template>
        </div>
    </vehica-text-search-field>
</div>