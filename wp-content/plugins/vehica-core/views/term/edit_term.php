<?php
/* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
/* @var \Vehica\Model\Term\Term $vehicaTerm */
?>

<input
        type="hidden"
        name="vehica_nonce"
        value="<?php echo esc_attr(wp_create_nonce('vehica_update_term')); ?>"
>

<?php if (vehicaApp('settings_config')->customTemplatesEnabled()) : ?>
    <tr class="form-field">
        <th scope="row">
            <?php esc_html_e('Single Listing Custom Template', 'vehica-core'); ?>
        </th>

        <td>
            <?php
            $vehicaCurrentTemplate = $vehicaTerm->getCarSingleCustomTemplate();
            ?>
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Term\Term::CAR_SINGLE_CUSTOM_TEMPLATE); ?>"
                    name="<?php echo esc_attr(\Vehica\Model\Term\Term::CAR_SINGLE_CUSTOM_TEMPLATE); ?>"
                    class="vehica-selectize"
            >
                <option value="0">
                    <?php esc_html_e('Not set', 'vehica-core'); ?>
                </option>

                <?php foreach (vehicaApp('car_single_templates') as $vehicaTemplate) :
                    /* @var \Vehica\Model\Post\Template\Template $vehicaTemplate */
                    ?>
                    <option
                            value="<?php echo esc_attr($vehicaTemplate->getId()); ?>"
                        <?php if ($vehicaCurrentTemplate && $vehicaCurrentTemplate->getId() === $vehicaTemplate->getId()): ?>
                            selected
                        <?php endif; ?>
                    >
                        <?php echo esc_html($vehicaTemplate->getName()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
<?php endif; ?>

<?php if ($vehicaTaxonomy->isUsedForCardLabels()) : ?>
    <tr class="form-field form-field--label-color">
        <th scope="row">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>">
                <?php esc_html_e('Display as a label', 'vehica-core'); ?>
            </label>
        </th>

        <td>
            <input
                    id="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>"
                    name="<?php echo esc_attr(\Vehica\Model\Term\Term::USE_AS_LABEL); ?>"
                    type="checkbox"
                    value="1"
                <?php if ($vehicaTerm->useAsLabel()) : ?>
                    checked
                <?php endif; ?>
            >
        </td>
    </tr>

    <tr class="form-field form-field--label-color">
        <th scope="row">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_COLOR); ?>">
                <?php esc_html_e('Card Label Text Color', 'vehica-core'); ?>
            </label>
        </th>

        <td class="vehica-app">
            <template>
                <vehica-design-color
                        type="primary"
                        initial-color="<?php echo esc_attr($vehicaTerm->getLabelColor()); ?>"
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
        </td>
    </tr>

    <tr class="form-field">
        <th scope="row">
            <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::LABEL_BACKGROUND_COLOR); ?>">
                <?php esc_html_e('Card Label Background Color', 'vehica-core'); ?>
            </label>
        </th>

        <td class="vehica-app">
            <template>
                <vehica-design-color
                        type="primary"
                        initial-color="<?php echo esc_attr($vehicaTerm->getLabelBackgroundColor()); ?>"
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
        </td>
    </tr>
<?php endif; ?>

<?php if ($vehicaTaxonomy->hasParentTaxonomy()) :
    foreach ($vehicaTaxonomy->getParentTaxonomies() as $parentTaxonomy) :
        ?>
        <tr class="form-field">
            <th scope="row">
                <label for="<?php echo esc_attr(\Vehica\Model\Term\Term::PARENT_TERM); ?>">
                    <?php echo esc_html($parentTaxonomy->getSingularName()); ?>
                </label>
            </th>

            <td>
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
                        <option
                                value="<?php echo esc_attr($vehicaParentTerm->getId()); ?>"
                            <?php if ($vehicaTerm->isParentTerm($vehicaParentTerm)): ?>
                                selected="selected"
                            <?php endif; ?>
                        >
                            <?php echo esc_html($vehicaParentTerm->getName()); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

<?php if ($vehicaTaxonomy->isFieldsDependencyEnabled()) : ?>
    <tr>
        <td colspan="2">
            <div class="flex flex-wrap">
                <div class="w-1/3">
                    <table>
                        <tr>
                            <th colspan="2">
                                <h2><?php echo esc_html(vehicaApp('car_config')->getName()); ?></h2>
                            </th>
                        </tr>
                        <?php foreach ($vehicaTerm->getTaxonomy()->getRelations($vehicaTerm) as $vehicaFieldRelation) :
                            /* @var \Vehica\Model\Term\Relation\FieldRelation $vehicaFieldRelation */
                            ?>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr($vehicaFieldRelation->getKey()); ?>">
                                        <?php echo esc_html($vehicaFieldRelation->getName()); ?>
                                    </label>
                                </th>
                                <td>
                                    <input
                                            id="<?php echo esc_attr($vehicaFieldRelation->getKey()); ?>"
                                            name="<?php echo esc_attr($vehicaFieldRelation->getKey()); ?>"
                                            value="<?php echo esc_attr(\Vehica\Model\Term\Relation\Relation::DEFAULT_VALUE); ?>"
                                            type="checkbox"
                                        <?php if ($vehicaFieldRelation->isFieldRequired()) : ?>
                                            checked
                                            disabled
                                        <?php elseif ($vehicaFieldRelation->isChecked()) : ?>
                                            checked
                                        <?php endif; ?>
                                    >

                                    <?php if ($vehicaFieldRelation->isFieldRequired()) : ?>
                                        <span>(<?php esc_html_e('Currently it\'s not possible to create dependency with required fields.', 'vehica-core'); ?>)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </td>
    </tr>
<?php endif; ?>
