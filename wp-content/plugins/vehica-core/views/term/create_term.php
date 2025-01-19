<?php
/* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
?>
<input type="hidden" name="vehica_create_term" value="1">

<div class="vehica-app">
    <?php if ($vehicaTaxonomy->isUsedForCardLabels()) : ?>
        <div class="form-field">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>">
                <?php esc_html_e('Display as a label', 'vehica-core'); ?>
            </label>

            <input
                    id="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>"
                    name="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>"
                    type="checkbox"
                    value="1"
                    checked
            >
        </div>

        <div class="form-field form-field--label-color">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_COLOR); ?>">
                <?php esc_html_e('Card Label Text Color', 'vehica-core'); ?>
            </label>

            <template>
                <vehica-design-color
                        type="primary"
                        initial-color="#ffffff"
                >
                    <div slot-scope="colorProps" class="vehica-color">
                        <div class="vehica-color__sample">
                            <div
                                    @click="colorProps.onShowPicker"
                                    class="vehica-color-picker"
                                    :style="{'background-color': colorProps.currentColor}"
                            ></div>

                            <div
                                    v-if="colorProps.showPicker"
                                    @click.prevent
                                    class="vehica-color__sample__window"
                            >
                                <vehica-chrome-picker
                                        :disable-alpha="true"
                                        :value="colorProps.currentColor"
                                        @input="colorProps.setCurrentColor"
                                ></vehica-chrome-picker>

                                <div class="vehica-color__buttons">
                                    <button
                                            class="vehica-flat-button vehica-flat-button--cyan"
                                            @click.prevent="colorProps.onSave"
                                    >
                                        <?php esc_html_e('Choose', 'vehica-core'); ?>
                                    </button>

                                    <button
                                            class="vehica-flat-button vehica-flat-button--transparent"
                                            @click.prevent="colorProps.onCancel"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <input
                                    name="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_COLOR); ?>"
                                    :value="colorProps.color"
                                    type="hidden"
                            >
                        </div>
                    </div>
                </vehica-design-color>
            </template>
        </div>

        <div class="form-field form-field--label-color">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_BACKGROUND_COLOR); ?>">
                <?php esc_html_e('Card Label Background Color', 'vehica-core'); ?>
            </label>

            <template>
                <vehica-design-color
                        type="primary"
                        initial-color="<?php echo esc_attr(vehicaApp('primary_color')); ?>"
                >
                    <div slot-scope="colorProps" class="vehica-color">
                        <div class="vehica-color__sample">
                            <div
                                    @click="colorProps.onShowPicker"
                                    class="vehica-color-picker"
                                    :style="{'background-color': colorProps.currentColor}"
                            ></div>

                            <div
                                    v-if="colorProps.showPicker"
                                    @click.prevent
                                    class="vehica-color__sample__window"
                            >
                                <vehica-chrome-picker
                                        :disable-alpha="true"
                                        :value="colorProps.currentColor"
                                        @input="colorProps.setCurrentColor"
                                ></vehica-chrome-picker>

                                <div class="vehica-color__buttons">
                                    <button
                                            class="vehica-flat-button vehica-flat-button--cyan"
                                            @click.prevent="colorProps.onSave"
                                    >
                                        <?php esc_html_e('Choose', 'vehica-core'); ?>
                                    </button>

                                    <button
                                            class="vehica-flat-button vehica-flat-button--transparent"
                                            @click.prevent="colorProps.onCancel"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <input
                                    name="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_BACKGROUND_COLOR); ?>"
                                    :value="colorProps.color"
                                    type="hidden"
                            >
                        </div>
                    </div>
                </vehica-design-color>
            </template>
        </div>
    <?php endif; ?>

    <?php if ($vehicaTaxonomy->hasParentTaxonomy()) :
        foreach ($vehicaTaxonomy->getParentTaxonomies() as $parentTaxonomy) :
            ?>
            <div class="form-field">
                <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::PARENT_TERM); ?>">
                    <?php echo esc_html($parentTaxonomy->getSingularName()); ?>
                </label>

                <select
                        id="<?php echo esc_attr(\Vehica\Model\Term\Term::PARENT_TERM); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Term\Term::PARENT_TERM); ?>[]"
                        class="vehica-selectize"
                        multiple
                >
                    <option value="0">
                        <?php esc_html_e('Not set', 'vehica-core'); ?>
                    </option>
                    <?php foreach ($parentTaxonomy->getTerms() as $vehicaParentTerm) :
                        /* @var \Vehica\Model\Term\Term $vehicaParentTerm */
                        ?>
                        <option value="<?php echo esc_attr($vehicaParentTerm->getId()); ?>">
                            <?php echo esc_html($vehicaParentTerm->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($vehicaTaxonomy->isFieldsDependencyEnabled()) : ?>
        <?php foreach (vehicaApp('usable_car_fields') as $vehicaField) :
            /* @var \Vehica\Model\Post\Field\Field $vehicaField */
            if ($vehicaField->getId() !== $vehicaTaxonomy->getId()) :
                ?>
                <div>
                    <input
                            name="vehica_relation_<?php echo esc_attr($vehicaField->getId()); ?>"
                            type="checkbox"
                            value="1"
                            checked
                        <?php if ($vehicaField->isRequired()) : ?>
                            disabled
                        <?php endif; ?>
                    > <?php echo esc_html($vehicaField->getName()); ?>
                    <?php if ($vehicaField->isRequired()) : ?>
                        <span>(<?php esc_html_e('Currently it\'s not possible to create dependency with required fields.', 'vehica-core'); ?>)</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>