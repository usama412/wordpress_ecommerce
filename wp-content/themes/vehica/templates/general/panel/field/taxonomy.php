<?php
/* @var \Vehica\Panel\PanelField\TaxonomyPanelField $vehicaPanelField */
global $vehicaPanelField;
/* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
$vehicaTaxonomy = $vehicaPanelField->getField();
?>
<vehica-taxonomy-panel-field
        :car="carForm.car"
        :field="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getField())); ?>"
        :terms="<?php echo htmlspecialchars(json_encode($vehicaPanelField->getTerms())); ?>"
        :multi="false"
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/terms/query')); ?>"
>
    <div
            slot-scope="taxonomyField"
            :class="{'vehica-has-error': carForm.showErrors && taxonomyField.hasError}"
    >
        <label
                for="<?php echo esc_attr($vehicaPanelField->getKey()); ?>"
            <?php if ($vehicaPanelField->isRequired()) : ?>
                class="vehica-car-form__label vehica-car-form__label--required"
            <?php else : ?>
                class="vehica-car-form__label"
            <?php endif; ?>
        >
            <?php echo esc_html($vehicaPanelField->getLabel()); ?>
        </label>

        <div
                class="vehica-car-form__field-select-single"
                :class="{'vehica-car-form__field-select-single--active': taxonomyField.value.length > 0}"
        >
            <div v-if="false" class="vehica-form-button">
                <?php echo esc_html(vehicaApp('select_string')); ?>
            </div>

            <?php if ($vehicaTaxonomy->hasParentTaxonomy() && $vehicaTaxonomy->getParentTaxonomies()->isNotEmpty()) : ?>
                <template>
                    <div
                            v-if="taxonomyField.options.length === 0 && taxonomyField.parentValues.length === 0"
                            class="vehica-form-button vehica-form-button--disabled"
                    >
                        <?php echo esc_html(vehicaApp('select_string') . ' ' . $vehicaTaxonomy->getParentTaxonomies()->first()->getName() . ' ' . ucfirst(vehicaApp('first_string'))); ?>
                    </div>

                    <div
                            v-if="taxonomyField.options.length === 0 && taxonomyField.parentValues.length > 0 && !taxonomyField.allowNewValues && !taxonomyField.inProgress"
                            class="vehica-form-button vehica-form-button--disabled"
                    >
                        <?php echo esc_html(vehicaApp('no_options_string')); ?>
                    </div>
                </template>
            <?php endif; ?>

            <template>
                <v-select
                    <?php if ($vehicaTaxonomy->hasParentTaxonomy()) : ?>
                        v-if="taxonomyField.options.length > 0 || (taxonomyField.parentValues.length && taxonomyField.allowNewValues) || (taxonomyField.inProgress && taxonomyField.parentValues.length)"
                    <?php endif; ?>
                        label="name"
                        :reduce="option => option.id"
                        @input="taxonomyField.setValue"
                        :value="taxonomyField.selectedTermId"
                        :options="taxonomyField.options"
                        :append-to-body="false"
                        :searchable="true"
                        :filter="taxonomyField.filter"
                    <?php if ($vehicaTaxonomy->allowNewValues()) : ?>
                        :taggable="true"
                        :create-option="taxonomyField.createOption"
                    <?php endif; ?>
                    <?php if ($vehicaPanelField->hasPlaceholder()) : ?>
                        placeholder="<?php echo esc_attr($vehicaPanelField->getPlaceholder()); ?>"
                    <?php else : ?>
                        placeholder="<?php echo esc_attr(vehicaApp('select_string')); ?>"
                    <?php endif; ?>
                >
                    <template #option="option">
                        <div
                                v-if="option.id > 0"
                                class="vehica-option"
                        >
                            <span v-html="option.name"></span>
                        </div>

                        <div
                                v-if="option.id <= 0"
                                class="vehica-option"
                        >
                            <?php echo esc_html(vehicaApp('create_string')); ?> <span v-html="option.name"></span>
                        </div>
                    </template>

                    <template #selected-option="option">
                        <div class="vehica-option">
                            <span v-if="taxonomyField.options.length > 0 || taxonomyField.newOption !== false" v-html="option.name"></span>
                        </div>
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
        </div>
    </div>
</vehica-taxonomy-panel-field>