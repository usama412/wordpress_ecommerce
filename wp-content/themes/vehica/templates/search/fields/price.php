<?php
/* @var \Vehica\Search\Field\PriceSearchField $vehicaSearchField */

global $vehicaSearchField;

if ($vehicaSearchField->isSelectFromToControl()) :?>
    <div class="vehica-results__field vehica-results__field--price vehica-relation-field">
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
                    :class="{'vehica-price-active': priceField.value !== ''}"
                    style="position: relative;"
            >
                <div v-if="false" class="vehica-text-field">
                    <input type="text">
                </div>

                <template>
                    <span
                            v-if="priceField.value !== ''"
                            class="vehica-form-button__clear vehica-form-button__clear--number-range"
                            @click.prevent="priceField.clearSelection"
                            style="z-index: 999999;"
                    >
                        <i class="fas fa-times"></i>
                    </span>
                </template>

                <v-select
                        label="controlDisplay"
                        :options="priceField.staticValues"
                        :value="priceField.currentOption"
                        @input="priceField.onSelectValueChange"
                        :append-to-body="false"
                        :searchable="false"
                        placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom(true)); ?>"
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
                        <div class="vehica-option">
                            <?php echo esc_html(vehicaApp('no_options_string')); ?>
                        </div>
                    </template>
                </v-select>
            </div>
        </vehica-price-search-field>
    </div>

    <div class="vehica-results__field vehica-results__field--price vehica-relation-field">
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
                    :class="{'vehica-price-active': priceField.value !== ''}"
                    style="position: relative;"
            >
                <div v-if="false" class="vehica-text-field">
                    <input type="text">
                </div>

                <template>
                    <span
                            v-if="priceField.value !== ''"
                            class="vehica-form-button__clear vehica-form-button__clear--number-range"
                            @click.prevent="priceField.clearSelection"
                            style="z-index: 999999;"
                    >
                        <i class="fas fa-times"></i>
                    </span>
                </template>

                <v-select
                        label="controlDisplay"
                        :options="priceField.staticValues"
                        :value="priceField.currentOption"
                        @input="priceField.onSelectValueChange"
                        :append-to-body="false"
                        :searchable="false"
                        placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo(true)); ?>"
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
                        <div class="vehica-option">
                            <?php echo esc_html(vehicaApp('no_options_string')); ?>
                        </div>
                    </template>
                </v-select>
            </div>
        </vehica-price-search-field>
    </div>
<?php else : ?>
    <div class="vehica-results__field vehica-results__field--price vehica-relation-field">
        <vehica-clear-search-field
                field-key="<?php echo esc_attr($vehicaSearchField->getKey()); ?>"
                :filters="searchFormProps.filters"
        >
            <div slot-scope="props" :class="{'vehica-active-price': props.showClearButton}">

                <?php if ($vehicaSearchField->isTextFromToControl()) : ?>
                    <div class="vehica-number-range">
                        <vehica-price-search-field
                                :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                                :filters="searchFormProps.filters"
                                price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_FROM); ?>"
                                :delay="1000"
                                :validate-numbers="true"
                        >
                            <div
                                    slot-scope="priceField"
                                    class="vehica-number-range__1of2 vehica-number-range__1of2--left"
                            >
                                <input
                                        placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderFrom(true)); ?>"
                                        :value="priceField.value"
                                        @input="priceField.onValueChange"
                                        type="text"
                                        :class="{'vehica-field-filled': priceField.value.toString().length > 0}"
                                >

                                <template>
                                    <span
                                            v-if="priceField.value !== ''"
                                            class="vehica-form-button__clear vehica-form-button__clear--number-range"
                                            @click.prevent="priceField.clearSelection"
                                    >
                                        <i class="fas fa-times"></i>
                                    </span>
                                </template>
                            </div>
                        </vehica-price-search-field>

                        <vehica-price-search-field
                                :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                                :filters="searchFormProps.filters"
                                price-type="<?php echo esc_attr(\Vehica\Search\Field\NumberSearchField::NUMBER_TYPE_TO); ?>"
                                :delay="1000"
                                :validate-numbers="true"
                        >
                            <div
                                    slot-scope="priceField"
                                    class="vehica-number-range__1of2 vehica-number-range__1of2--right"
                            >
                                <input
                                        placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholderTo(true)); ?>"
                                        :value="priceField.value"
                                        @input="priceField.onValueChange"
                                        type="text"
                                        :class="{'vehica-field-filled': priceField.value.toString().length > 0}"
                                >

                                <template>
                                    <span
                                            v-if="priceField.value !== ''"
                                            class="vehica-form-button__clear vehica-form-button__clear--number-range"
                                            @click.prevent="priceField.clearSelection"
                                    >
                                        <i class="fas fa-times"></i>
                                    </span>
                                </template>
                            </div>
                        </vehica-price-search-field>
                    </div>
                <?php elseif ($vehicaSearchField->isSelectControl()) : ?>
                    <div v-if="false" class="vehica-text-field">
                        <input
                                type="text"
                                placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
                        >
                    </div>

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
                        <div slot-scope="priceField">
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
                <?php elseif ($vehicaSearchField->isRadioControl()) : ?>
                    <vehica-price-search-field
                            :price-field="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
                            :filters="searchFormProps.filters"
                        <?php if ($vehicaSearchField->hasStaticValues()) : ?>
                            :static-values="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getStaticValues())); ?>"
                        <?php endif; ?>
                        <?php if ($vehicaSearchField->isCompareValueGreaterThan()) : ?>
                            price-type="<?php echo esc_attr(\Vehica\Search\Field\PriceSearchField::NUMBER_TYPE_FROM); ?>"
                        <?php else : ?>
                            price-type="<?php echo esc_attr(\Vehica\Search\Field\PriceSearchField::NUMBER_TYPE_TO); ?>"
                        <?php endif; ?>
                        <?php if ($vehicaSearchField->hasGreaterThanValue()) : ?>
                            :clear-greater-than="true"
                        <?php endif; ?>
                    >
                        <div slot-scope="priceField">
                            <div>
                                <input
                                        type="radio"
                                        :checked="priceField.isAnyValue"
                                        @change="priceField.clearSelection"
                                >
                                <?php echo esc_html($vehicaSearchField->getPlaceholder(true)); ?>
                            </div>

                            <div v-for="staticValue in priceField.staticValues" :key="staticValue.value">
                                <input
                                        @change="priceField.setValue(staticValue.value, staticValue.display)"
                                        :checked="priceField.isValue(staticValue.value)"
                                        type="radio"
                                > {{ staticValue.display }}
                            </div>
                        </div>
                    </vehica-price-search-field>
                <?php endif; ?>
            </div>
        </vehica-clear-search-field>
    </div>
<?php
endif;