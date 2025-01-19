<?php
/* @var \Vehica\Widgets\General\SearchV2GeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaSearchFields = $vehicaCurrentWidget->getSearchFields();
?>
<div class="vehica-app vehica-search-classic-v2 vehica-search-classic-v2--fields-<?php echo esc_attr($vehicaSearchFields->count()); ?>">
    <div
        <?php if ($vehicaCurrentWidget->showShadow()) : ?>
            class="vehica-search-classic-v2__inner"
        <?php else : ?>
            class="vehica-search-classic-v2__inner vehica-search-classic-v2__inner--hide-shadow"
        <?php endif; ?>
    >
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
                            <div slot-scope="taxonomyField" class="vehica-search-classic-v2__top">
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

                    <div class="vehica-search-classic-v2__fields--wrapper">
                        <div class="vehica-search-classic-v2__fields">
                            <?php
                            global $vehicaSearchField;
                            foreach ($vehicaSearchFields as $vehicaSearchField) :
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
                            <div class="vehica-search-classic-v2__search-button-wrapper">
                                <button
                                        class="vehica-button"
                                        @click.prevent="searchFormProps.onSearch"
                                        disabled
                                >
                                    <span class="vehica-button__text"><?php echo esc_attr(vehicaApp('search_string')); ?></span>
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="vehica-search-classic-v2-mask-bottom"></div>
                    </div>
                </form>
            </div>
        </vehica-search-form>
    </div>
</div>