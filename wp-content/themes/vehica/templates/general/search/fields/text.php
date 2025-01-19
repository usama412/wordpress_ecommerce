<?php
/* @var \Vehica\Search\Field\TextSearchField $vehicaSearchField */
/* @var \Vehica\Widgets\General\SearchV1GeneralWidget $vehicaCurrentWidget */
global $vehicaSearchField, $vehicaCurrentWidget;
?>
<div class="vehica-search__field vehica-relation-field <?php echo esc_attr($vehicaSearchField->getClass()); ?>">
    <vehica-text-search-field
            :text-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
            :filters="searchFormProps.filters"
    >
        <div slot-scope="textField" class="vehica-text-field" :class="{'vehica-field-filled': textField.value > 0}">
            <input
                    @input="textField.onValueChange"
                    :value="textField.value"
                    type="text"
                    placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
            >
        </div>
    </vehica-text-search-field>
</div>