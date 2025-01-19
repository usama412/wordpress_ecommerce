<?php
/* @var \Vehica\Search\Field\NumberSearchField $vehicaSearchField */

/* @var \Vehica\Widgets\General\SearchV1GeneralWidget $vehicaCurrentWidget */
global $vehicaSearchField, $vehicaCurrentWidget;
?>
<div class="vehica-search__field vehica-relation-field <?php echo esc_attr($vehicaSearchField->getClass()); ?>">

    <?php if ($vehicaSearchField->isSelectFromToControl()) : ?>
        <div class="vehica-number-range-v2">
            <div v-if="false" class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--left">
                <input type="text" placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom()); ?>">
            </div>

            <div v-if="false" class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right">
                <input type="text" placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo()); ?>">
            </div>

            <template>
                <vehica-number-search-field
                        :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                        :filters="searchFormProps.filters"
                        number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                        :disable-greater-than-filter="true"
                    <?php if ($vehicaSearchField->hasStaticValuesFrom()) : ?>
                        :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValuesFrom())); ?>"
                    <?php endif; ?>
                >
                    <div
                            slot-scope="numberField"
                            class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--left"
                            :class="{'vehica-number-field--active': numberField.value !== ''}"
                    >
                        <v-select
                                label="display"
                                :options="numberField.staticValues"
                                :value="numberField.currentOption"
                                @input="numberField.onSelectValueChange"
                                :append-to-body="false"
                                :searchable="false"
                                placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom()); ?>"
                        >
                            <template #option="option">
                                <div class="vehica-option">
                                    {{ option.display }}
                                </div>
                            </template>

                            <template #selected-option="option">
                                <div class="vehica-option">
                                    {{ option.display }}
                                </div>
                            </template>
                        </v-select>
                    </div>
                </vehica-number-search-field>

                <vehica-number-search-field
                        :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                        :filters="searchFormProps.filters"
                        number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
                        :disable-greater-than-filter="true"
                    <?php if ($vehicaSearchField->hasStaticValuesTo()) : ?>
                        :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValuesTo())); ?>"
                    <?php endif; ?>
                >
                    <div
                            slot-scope="numberField"
                            class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right"
                            :class="{'vehica-number-field--active': numberField.value !== ''}"
                    >
                        <v-select
                                label="display"
                                :options="numberField.staticValues"
                                :value="numberField.currentOption"
                                @input="numberField.onSelectValueChange"
                                :append-to-body="false"
                                :searchable="false"
                                placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo()); ?>"
                        >
                            <template #option="option">
                                <div class="vehica-option">
                                    {{ option.display }}
                                </div>
                            </template>

                            <template #selected-option="option">
                                <div class="vehica-option">
                                    {{ option.display }}
                                </div>
                            </template>
                        </v-select>
                    </div>
                </vehica-number-search-field>
            </template>
        </div>
    <?php elseif ($vehicaSearchField->isTextFromToControl()) : ?>
        <div class="vehica-number-range">
            <vehica-number-search-field
                    :number-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    number-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                    :disable-greater-than-filter="true"
                    :validate-numbers="true"
            >
                <div
                        slot-scope="numberField"
                        class="vehica-number-range__1of2 vehica-number-range__1of2--left"
                        :class="{'vehica-active-number': numberField.value !== ''}"
                >
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom()); ?>"
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
                    :disable-greater-than-filter="true"
                    :validate-numbers="true"
            >
                <div
                        slot-scope="numberField"
                        class="vehica-number-range__1of2 vehica-number-range__1of2--right"
                        :class="{'vehica-active-number': numberField.value !== ''}"
                >
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo()); ?>"
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
        <div v-if="false" class="vehica-text-field">
            <input type="text" placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>">
        </div>

        <template>
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
                <div slot-scope="numberField" :class="{'vehica-number-field--active': numberField.value !== ''}">
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
                                {{ option.display }}
                            </div>
                        </template>

                        <template #selected-option="option">
                            <div class="vehica-option">
                                {{ option.display }}
                            </div>
                        </template>
                    </v-select>
                </div>
            </vehica-number-search-field>
        </template>
    <?php endif; ?>
</div>