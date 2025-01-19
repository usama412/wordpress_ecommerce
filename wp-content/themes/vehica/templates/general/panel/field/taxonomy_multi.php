<?php
/* @var \Vehica\Panel\PanelField\TaxonomyPanelField $vehicaPanelField */
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
global $vehicaPanelField, $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
$vehicaTaxonomy = $vehicaPanelField->getField();
$vehicaTerms = $vehicaTaxonomy->getTerms();
?>
<vehica-taxonomy-panel-field
        :car="carForm.car"
        :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
        :terms="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getTerms())); ?>"
        :multi="true"
        :max="<?php echo esc_attr($vehicaCurrentWidget->getMaxVisibleTerms()); ?>"
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/terms/query')); ?>"
>
    <div
            slot-scope="taxonomyField"
            v-if="!taxonomyField.hideField"
            class="vehica-relation-field vehica-car-form-field__<?php echo esc_attr($vehicaTaxonomy->getKey()); ?>"
    >
        <h3
            <?php if ($vehicaPanelField->isRequired()) : ?>
                class="vehica-car-form__section-title vehica-car-form__section-title--required"
            <?php else : ?>
                class="vehica-car-form__section-title"
            <?php endif; ?>
        >
            <?php echo esc_html($vehicaPanelField->getLabel()); ?>
        </h3>

        <div
                class="vehica-car-form__section"
                :class="{'vehica-has-error': carForm.showErrors && taxonomyField.hasError}"
        >
            <div class="vehica-car-form__multi-taxonomy">
                <template>
                    <div
                            v-for="option in taxonomyField.visibleOptions"
                            :key="option.id"
                            @click="taxonomyField.addTerm(option.id)"
                            class="vehica-car-form__multi-taxonomy__term"
                            :class="{'vehica-car-form__multi-taxonomy__term--active': taxonomyField.isTermSelected(option.id)}"
                    >
                        {{ option.name }}
                    </div>

                    <div
                            v-if="!taxonomyField.showAll && taxonomyField.visibleOptions.length !== taxonomyField.options.length"
                            @click.prevent="taxonomyField.onShowAll"
                            class="vehica-car-form__multi-taxonomy__load-more"
                    >
                        <?php echo esc_html(vehicaApp('load_all_string')); ?>
                    </div>
                </template>
            </div>
        </div>
    </div>
</vehica-taxonomy-panel-field>