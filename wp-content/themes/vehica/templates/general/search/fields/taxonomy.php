<?php
/* @var \Vehica\Search\Field\SearchField|\Vehica\Search\Field\TaxonomySearchField $vehicaSearchField */
/* @var \Vehica\Widgets\General\SearchV1GeneralWidget $vehicaCurrentWidget */
global $vehicaSearchField, $vehicaCurrentWidget;
?>
<div class="vehica-search__field vehica-relation-field <?php echo esc_attr($vehicaSearchField->getClass()); ?>">
    <vehica-taxonomy-search-field
            :taxonomy="<?php echo htmlspecialchars(json_encode($vehicaSearchField)); ?>"
            :filters="searchFormProps.filters"
            :terms="<?php echo htmlspecialchars(json_encode($vehicaSearchField->getTermsList())); ?>"
            when-empty="<?php echo esc_attr($vehicaSearchField->whenTermEmpty()); ?>"
            :terms-count="searchFormProps.terms"
            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
            :disable-sort="<?php echo esc_attr($vehicaSearchField->disableSort() ? 'true' : 'false'); ?>"
            :has-parent="<?php echo esc_attr($vehicaSearchField->hasParent() ? 'true' : 'false'); ?>"
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/terms/query')); ?>"
        <?php if ($vehicaSearchField->sortByCount()) : ?>
            :sort-by-count="true"
        <?php endif; ?>
    >
        <div
                slot-scope="taxonomyField"
                class="vehica-taxonomy-select"
                :class="{'vehica-active-taxonomy': taxonomyField.selectedTerms.length > 0}"
        >
            <?php if ($vehicaSearchField->isCheckboxControl()) : ?>
                <div>
                    <div class="vehica-form-button" @click.prevent="taxonomyField.onShowModal">
                        <span v-if="taxonomyField.selectedTermsLabel === ''">
                            <?php echo esc_html($vehicaSearchField->getPlaceholder()); ?>
                        </span>

                        <template v-if="taxonomyField.selectedTermsLabel !== ''">
                            {{ taxonomyField.selectedTermsLabel }}
                        </template>

                        <template v-if="taxonomyField.selectedTerms.length > 0">
                            <span class="vehica-form-button__clear">
                                <i class="fas fa-times" @click.stop="taxonomyField.clearSelection"></i>
                            </span>
                        </template>
                    </div>

                    <template>
                        <portal to="footer">
                            <div v-if="taxonomyField.showModal">
                                <div class="vehica-popup-checkbox">
                                    <div class="vehica-popup-checkbox__inner">
                                        <div class="vehica-popup-checkbox__position">
                                            <div class="vehica-popup-checkbox__top">
                                                <div class="vehica-popup-checkbox__name">
                                                    <?php echo esc_html($vehicaSearchField->getName()); ?>
                                                </div>

                                                <div class="vehica-popup-checkbox__close">
                                                    <div @click.prevent="taxonomyField.onCloseModal"
                                                         class="vehica-close-animated">
                                                        <div class="vehica-close-animated__leftright"></div>
                                                        <div class="vehica-close-animated__rightleft"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="vehica-popup-checkbox__middle">
                                                <div
                                                        v-for="term in taxonomyField.allOptions" :key="term.key"
                                                        class="vehica-checkbox"
                                                >
                                                    <input
                                                            :id="term.key"
                                                            @change="taxonomyField.addTerm(term)"
                                                            :checked="term.isSelected"
                                                            type="checkbox"
                                                            :disabled="term.isDisabled"
                                                    >
                                                    <label :for="term.key">
                                                        <span v-html="term.name"></span>

                                                        <?php if ($vehicaSearchField->showTermCount()) : ?>
                                                            <span class="vehica-checkbox__count">({{ term.count
                                                                }})</span>
                                                        <?php endif; ?>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="vehica-popup-checkbox__bottom">
                                                <div
                                                        class="vehica-popup-checkbox__clear"
                                                        @click.prevent="taxonomyField.clearSelection"
                                                >
                                                    <?php echo esc_html(vehicaApp('clear_all_string')); ?>
                                                    <span>"<?php echo esc_html($vehicaSearchField->getName()); ?>
                                                        "</span>
                                                </div>

                                                <button
                                                        class="vehica-button vehica-button--icon vehica-button--icon--check"
                                                        @click.prevent="taxonomyField.onCloseModal"
                                                >
                                                    <?php echo esc_html(vehicaApp('done_string')); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </portal>
                    </template>
                </div>
            <?php elseif ($vehicaSearchField->isSelectControl()) : ?>
                <div v-if="false" class="vehica-form-button">
                    <?php echo esc_html($vehicaSearchField->getPlaceholder()); ?>
                </div>

                <template>
                    <v-select
                            label="name"
                            :options="taxonomyField.options"
                            :value="taxonomyField.getTerm(taxonomyField.selectedTerm)"
                            @input="taxonomyField.setTerm"
                            :append-to-body="false"
                            :disabled="taxonomyField.disableField"
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
                            :selectable="option => option.count > 0"
                        <?php if ($vehicaSearchField->searchable()) : ?>
                            :searchable="true"
                        <?php else : ?>
                            :searchable="false"
                        <?php endif; ?>
                    >
                        <template #option="option">
                            <div class="vehica-option">
                                <span v-html="option.name"></span>
                                <?php if ($vehicaSearchField->showTermCount()) : ?>
                                    <template v-if="option.count">
                                        <span class="vehica-option__count">({{ option.count }})</span>
                                    </template>
                                <?php endif; ?>
                            </div>
                        </template>

                        <template #selected-option="option">
                            <span class="vehica-option-selected">
                                <span v-html="option.name"></span>
                            </span>
                        </template>

                        <template #no-options="{ search, searching, loading }">
                            <div class="vehica-option">
                                <span v-if="taxonomyField.inProgress">
                                    <i class="vehica-loading-options-spinner fas fa-circle-notch fa-spin"></i>

                                    <?php echo esc_html(vehicaApp('loading_string')); ?>
                                </span>

                                <span v-if="!taxonomyField.inProgress">
                                    <?php echo esc_html(vehicaApp('no_options_string')); ?>
                                </span>
                            </div>
                        </template>
                    </v-select>
                </template>
            <?php elseif ($vehicaSearchField->isMultiSelectControl()) : ?>
                <div v-if="false" class="vehica-form-button">
                    <?php echo esc_html($vehicaSearchField->getPlaceholder()); ?>
                </div>

                <template>
                    <v-select
                            label="name"
                            :options="taxonomyField.options"
                            :value="taxonomyField.getTerms(taxonomyField.selectedTerms)"
                            @input="taxonomyField.setTerms"
                            :append-to-body="false"
                            :disabled="taxonomyField.disableField"
                            :multiple="true"
                            placeholder="<?php echo esc_attr($vehicaSearchField->getPlaceholder()); ?>"
                            :selectable="option => option.count > 0"
                        <?php if ($vehicaSearchField->searchable()) : ?>
                            :searchable="true"
                        <?php else : ?>
                            :searchable="false"
                        <?php endif; ?>
                    >
                        <template #option="option">
                            <div class="vehica-option">
                                <span v-html="option.name"></span>
                                <?php if ($vehicaSearchField->showTermCount()) : ?>
                                    <template v-if="option.count">
                                        <span class="vehica-option__count">({{ option.count }})</span>
                                    </template>
                                <?php endif; ?>
                            </div>
                        </template>

                        <template #selected-option="option">
                            <span class="vehica-option-selected">
                                <span v-html="option.name"></span>
                            </span>
                        </template>

                        <template #selected-option-container="{ option, deselect, multiple, disabled }">
                            <div class="vs__selected">
                                <span class="vehica-option-selected"><span v-html="option.name"></span></span>
                            </div>
                        </template>

                        <template #no-options="{ search, searching, loading }">
                            <div class="vehica-option">
                                <span><?php echo esc_html(vehicaApp('no_options_string')); ?></span>
                            </div>
                        </template>
                    </v-select>
                </template>
            <?php endif; ?>
        </div>
    </vehica-taxonomy-search-field>
</div>