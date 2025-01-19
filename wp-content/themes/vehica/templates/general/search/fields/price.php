<?php
/* @var \Vehica\Search\Field\PriceSearchField $vehicaSearchField */

/* @var \Vehica\Widgets\General\SearchV1GeneralWidget $vehicaCurrentWidget */
global $vehicaSearchField, $vehicaCurrentWidget;
?>
<div class="vehica-search__field vehica-relation-field <?php echo esc_attr($vehicaSearchField->getClass()); ?>">

    <?php if ($vehicaSearchField->isSelectFromToControl()) : ?>
        <div class="vehica-number-range-v2">
            <vehica-price-search-field
                    :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                <?php if ($vehicaSearchField->hasStaticValuesFrom()) : ?>
                    :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValuesFrom())); ?>"
                <?php endif; ?>
            >
                <div
                        slot-scope="priceField"
                        class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--left"
                        :class="{'vehica-price-select-from-to-active': priceField.value !== ''}"
                >
                    <div v-if="false" class="vehica-form-button">
                        <?php echo esc_html($vehicaSearchField->getPlaceholderFrom()); ?>
                    </div>

                    <v-select
                            label="controlDisplay"
                            :options="priceField.staticValues"
                            :value="priceField.currentOption"
                            @input="priceField.onSelectValueChange"
                            :append-to-body="false"
                            :searchable="false"
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom()); ?>"
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

                        <template #no-options="{ search, searching, loading }">
                            <?php echo esc_html(vehicaApp('no_options_string')); ?>
                        </template>
                    </v-select>
                </div>
            </vehica-price-search-field>

            <vehica-price-search-field
                    :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
                <?php if ($vehicaSearchField->hasStaticValuesTo()) : ?>
                    :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValuesTo())); ?>"
                <?php endif; ?>
            >
                <div
                        slot-scope="priceField"
                        class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right"
                        :class="{'vehica-price-select-from-to-active': priceField.value !== ''}"
                >
                    <div v-if="false" class="vehica-form-button">
                        <?php echo esc_html($vehicaSearchField->getPlaceholderTo()); ?>
                    </div>

                    <v-select
                            label="controlDisplay"
                            :options="priceField.staticValues"
                            :value="priceField.currentOption"
                            @input="priceField.onSelectValueChange"
                            :append-to-body="false"
                            :searchable="false"
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo()); ?>"
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

                        <template #no-options="{ search, searching, loading }">
                            <?php echo esc_html(vehicaApp('no_options_string')); ?>
                        </template>
                    </v-select>
                </div>
            </vehica-price-search-field>
        </div>
    <?php elseif ($vehicaSearchField->isTextFromToControl()) : ?>
        <div class="vehica-number-range-v2">
            <vehica-price-search-field
                    :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                    :validate-numbers="true"
            >
                <div
                        slot-scope="priceField"
                        class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--left"
                        :class="{'vehica-price-active': priceField.value !== ''}"
                >
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom(true)); ?>"
                            :value="priceField.value"
                            @input="priceField.onValueChange"
                            type="text"
                            :class="{'vehica-field-filled': priceField.value.toString().length > 0}"
                    >
                    <template>
                        <span class="vehica-form-button__clear vehica-form-button__clear--number-range"
                              v-if="priceField.value !== ''" @click.prevent="priceField.clearSelection">
                            <i class="fas fa-times"></i>
                        </span>
                    </template>
                </div>
            </vehica-price-search-field>
            <vehica-price-search-field
                    :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                    :filters="searchFormProps.filters"
                    price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
                    :validate-numbers="true"
            >
                <div
                        slot-scope="priceField"
                        class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right"
                        :class="{'vehica-price-active': priceField.value !== ''}"
                >
                    <input
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo(true)); ?>"
                            :value="priceField.value"
                            @input="priceField.onValueChange"
                            type="text"
                            :class="{'vehica-field-filled': priceField.value.toString().length > 0}"
                    >
                    <template>
                        <span class="vehica-form-button__clear vehica-form-button__clear--number-range"
                              v-if="priceField.value !== ''" @click.prevent="priceField.clearSelection">
                            <i class="fas fa-times"></i>
                        </span>
                    </template>
                </div>
            </vehica-price-search-field>
        </div>
    <?php elseif ($vehicaSearchField->isSelectControl()) : ?>
        <vehica-price-search-field
                :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                :filters="searchFormProps.filters"
            <?php if ($vehicaSearchField->isCompareValueGreaterThan()) : ?>
                price-type="<?php echo esc_attr(\Vehica\Search\Field\PriceSearchField::NUMBER_TYPE_FROM); ?>"
            <?php else : ?>
                price-type="<?php echo esc_attr(\Vehica\Search\Field\PriceSearchField::NUMBER_TYPE_TO); ?>"
            <?php endif; ?>
            <?php if ($vehicaSearchField->hasStaticValues()) : ?>
                :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValues())); ?>"
            <?php endif; ?>
        >
            <div slot-scope="priceField" :class="{'vehica-price-active': priceField.value !== ''}">
                <div v-if="false" class="vehica-form-button">
                    <?php echo esc_html($vehicaSearchField->getPlaceholder()); ?>
                </div>

                <v-select
                        label="controlDisplay"
                        :options="priceField.staticValues"
                        :value="priceField.currentOption"
                        @input="priceField.onSelectValueChange"
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
        </vehica-price-search-field>
    <?php endif; ?>
</div>
