<?php
/* @var \Vehica\Search\Field\NumberSearchField $vehicaSearchField */
global $vehicaSearchField;
?>
<div class="vehica-results__field vehica-results__field--number vehica-relation-field">
    <?php if ($vehicaSearchField->isTextFromToControl()) : ?>
        <div class="vehica-number-range">
            <vehica-number-search-field
                    :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                    :delay="1000"
                    :disable-greater-than-filter="true"
                    class="vehica-number-range__1of2 vehica-number-range__1of2--left"
                    :validate-numbers="true"
            >
                <div slot-scope="numberField" :class="{'vehica-active-number': numberField.value !== ''}">
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom(true)); ?>"
                            :value="numberField.value"
                            @input="numberField.onValueChange"
                            type="text"
                    >
                    <template>
                        <span class="vehica-form-button__clear vehica-form-button__clear--number-range"
                              v-if="numberField.value !== ''" @click.prevent="numberField.clearSelection">
                            <i class="fas fa-times"></i>
                        </span>
                    </template>
                </div>
            </vehica-number-search-field>

            <vehica-number-search-field
                    :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
                    :delay="1000"
                    :disable-greater-than-filter="true"
                    class="vehica-number-range__1of2 vehica-number-range__1of2--right"
                    :validate-numbers="true"
            >
                <div slot-scope="numberField" :class="{'vehica-active-number': numberField.value !== ''}">
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo(true)); ?>"
                            :value="numberField.value"
                            @input="numberField.onValueChange"
                            type="text"
                    >
                    <template>
                        <span class="vehica-form-button__clear vehica-form-button__clear--number-range"
                              v-if="numberField.value !== ''" @click.prevent="numberField.clearSelection">
                            <i class="fas fa-times"></i>
                        </span>
                    </template>
                </div>
            </vehica-number-search-field>
        </div>
    <?php elseif ($vehicaSearchField->isSelectControl()) : ?>
        <div v-if="false" class="vehica-form-button">
            <?php echo esc_html($vehicaSearchField->getName()); ?>
        </div>

        <vehica-number-search-field
                :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                :filters="searchFormProps.filters"
            <?php if ($vehicaSearchField->isCompareValueGreaterThan()) : ?>
                number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
            <?php endif; ?>
            <?php if ($vehicaSearchField->isCompareValueLessThan()) : ?>
                number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
            <?php endif; ?>
            <?php if ($vehicaSearchField->hasStaticValues()) : ?>
                :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValues())); ?>"
            <?php endif; ?>
            <?php if ($vehicaSearchField->hasGreaterThanValue()) : ?>
                :greater-than-value="<?php echo esc_attr($vehicaSearchField->getGreaterThanValue()); ?>"
                greater-than-display="<?php echo esc_attr($vehicaSearchField->getGreaterThanDisplay()); ?>"
            <?php endif; ?>
        >
            <div slot-scope="numberField" :class="{'vehica-active-number': numberField.value !== ''}">
                <v-select
                        label="display"
                        :options="numberField.staticValues"
                        :value="numberField.currentOption"
                        @input="numberField.onSelectValueChange"
                        :append-to-body="false"
                        :searchable="false"
                        placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
                >
                    <template #option="option">
                        <div class="vehica-option">
                            {{ option.controlDisplay }}
                        </div>
                    </template>

                    <template #selected-option="option">
                        <div class="vehica-option">
                            {{ option.controlDisplay }}
                        </div>
                    </template>
                </v-select>
            </div>
        </vehica-number-search-field>
    <?php endif; ?>
</div>