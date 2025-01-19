<?php
/* @var \Vehica\Widgets\General\SearchV1GeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-app vehica-search-classic-v1">
    <div class="vehica-search-classic-v1__inner">
        <vehica-search-form
                :initial-terms-count="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getTermsCount())); ?>"
                :initial-results-count="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getResultsCount())); ?>"
                archive-url="<?php echo esc_url($vehicaCurrentWidget->getResultsPageUrl()); ?>"
                :initial-filters="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getInitialFilters())); ?>"
                :taxonomy-terms-count-ids="<?php echo json_encode($vehicaCurrentWidget->getTaxonomyTermsCountIds()); ?>"
        >
            <div slot-scope="searchFormProps">
                <form @submit.prevent="searchFormProps.onSearch">
                    <?php if ($vehicaCurrentWidget->hasMainSearchField()) :
                        /* @var \Vehica\Search\Field\TaxonomySearchField $vehicaMainSearchField */
                        $vehicaMainSearchField = $vehicaCurrentWidget->getMainSearchField();
                        ?>
                        <vehica-taxonomy-search-field
                                :taxonomy="<?php echo htmlspecialchars(json_encode($vehicaMainSearchField)); ?>"
                                :filters="searchFormProps.filters"
                                :terms="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getMainFieldTerms())); ?>"
                                when-empty="<?php echo esc_attr($vehicaMainSearchField->whenTermEmpty()); ?>"
                                :terms-count="searchFormProps.terms"
                                :disable-sort="true"
                        >
                            <div slot-scope="taxonomyField" class="vehica-search-classic-v1__top">
                                <?php if ($vehicaCurrentWidget->showMainFieldAllOption()) : ?>
                                    <div
                                            class="vehica-radio"
                                            :class="{'vehica-radio--active': taxonomyField.showAll}"
                                    >
                                        <input
                                                @change="taxonomyField.clearSelection"
                                                :checked="taxonomyField.showAll"
                                                type="radio"
                                                :id="taxonomyField.key"
                                        >

                                        <label :for="taxonomyField.key">
                                            <?php echo esc_html($vehicaCurrentWidget->getMainFieldAllOptionText()); ?>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($vehicaCurrentWidget->getMainFieldTerms() as $vehicaTerm) :
                                    /* @var \Vehica\Model\Term\Term $vehicaTerm */ ?>
                                    <div
                                            class="vehica-radio"
                                            :class="{
                                                'vehica-radio--active': taxonomyField.isTermSelected(<?php echo esc_attr($vehicaTerm->getId()); ?>),
                                                'vehica-radio--disabled': taxonomyField.isTermDisabled(<?php echo esc_attr($vehicaTerm->getId()); ?>)
                                            }"
                                    >
                                        <input
                                                @change="taxonomyField.setTermId(<?php echo esc_attr($vehicaTerm->getId()); ?>)"
                                                :checked="taxonomyField.isTermSelected(<?php echo esc_attr($vehicaTerm->getId()); ?>)"
                                                type="radio"
                                                id="<?php echo esc_attr($vehicaTerm->getKey()); ?>"
                                                :disabled="taxonomyField.isTermDisabled(<?php echo esc_attr($vehicaTerm->getId()); ?>)"
                                        >
                                        <label for="<?php echo esc_attr($vehicaTerm->getKey()); ?>">
                                            <?php echo esc_html($vehicaTerm->getName()); ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </vehica-taxonomy-search-field>
                    <?php endif; ?>

                    <div class="vehica-search-classic-v1__inner">
                        <div class="vehica-search-classic-v1__fields">
                            <?php
                            global $vehicaSearchField;
                            foreach ($vehicaCurrentWidget->getSearchFields() as $vehicaSearchField) :
                                /* @var \Vehica\Search\Field\SearchField|\Vehica\Search\Field\SearchField $vehicaSearchField */ ?>
                                <?php
                                if ($vehicaSearchField->isTaxonomy()) :
                                    get_template_part('templates/general/search/fields/taxonomy');
                                elseif ($vehicaSearchField->isNumberField()) :
                                    get_template_part('templates/general/search/fields/number');
                                elseif ($vehicaSearchField->isPriceField()) :
                                    get_template_part('templates/general/search/fields/price');
                                elseif ($vehicaSearchField->isTextField()) :
                                    get_template_part('templates/general/search/fields/text');
                                elseif ($vehicaSearchField->isDateField()) :
                                    get_template_part('templates/general/search/fields/date');
                                elseif ($vehicaSearchField->isLocationField()) :
                                    get_template_part('templates/general/search/fields/location');
                                endif;
                                ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="vehica-search-classic-v1__divider"></div>

                        <div class="vehica-search-classic-v1__action">
                            <div class="vehica-number-range-v2">
                                <div class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right">
                                    <a
                                            class="vehica-search-classic-v1__advanced-link"
                                            href="<?php echo esc_url($vehicaCurrentWidget->getAdvancedSearchLink()); ?>"
                                    >
                                        <?php echo esc_html($vehicaCurrentWidget->getAdvancedSearchLinkText()); ?>
                                    </a>
                                </div>

                                <div class="vehica-number-range-v2__1of2 vehica-number-range-v2__1of2--right">
                                    <button
                                            class="vehica-button vehica-button--icon vehica-button--icon--search"
                                            @click.prevent="searchFormProps.onSearch"
                                            disabled
                                    >
                                        <?php echo esc_html($vehicaCurrentWidget->getSearchButtonText()); ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php if ($vehicaCurrentWidget->showCarsNumber()) : ?>
                            <div class="vehica-search-classic-v1__bottom">
                                <?php echo esc_html(vehicaApp('we_found_string')); ?>
                                <template>
                                    <span class="vehica-text-primary">{{ searchFormProps.resultsCount }}</span>
                                </template>
                                <?php echo esc_html(vehicaApp('vehicles_for_you_string')); ?>
                            </div>
                        <?php endif; ?>

                        <div class="vehica-search-classic-v1__shadow"></div>
                    </div>
                </form>
            </div>
        </vehica-search-form>
    </div>
</div>