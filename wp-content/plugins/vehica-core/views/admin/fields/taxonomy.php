<?php /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaField */ ?>
    <div class="vehica-edit__field">
        <input
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>"
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>"
                type="checkbox"
                value="1"
            <?php if ($vehicaField->isRequired()) : ?>
                checked
            <?php endif; ?>
        >
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::IS_REQUIRED); ?>">
            <?php esc_html_e('Required', 'vehica-core'); ?>
        </label>
    </div>

    <div class="vehica-edit__field">
        <div>
            <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::COMPARE_LOGIC); ?>">
                <?php esc_html_e('Search form compare logic', 'vehica-core'); ?>
            </label>
        </div>

        <div class="vehica-select">
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::COMPARE_LOGIC); ?>"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::COMPARE_LOGIC); ?>"
            >
                <option
                        value="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::COMPARE_LOGIC_OR); ?>"
                    <?php if ($vehicaField->isCompareLogicOr()) : ?>
                        selected
                    <?php endif; ?>
                >
                    <?php esc_html_e('or (example: "SUV" or "Coupe")', 'vehica-core'); ?>
                </option>
                <option
                        value="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::COMPARE_LOGIC_AND); ?>"
                    <?php if ($vehicaField->isCompareLogicAnd()) : ?>
                        selected
                    <?php endif; ?>
                >
                    <?php esc_html_e('and (example: "Keyless start" and "Navigation")', 'vehica-core'); ?>
                </option>
            </select>
        </div>
    </div>

    <div class="vehica-edit__field">
        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_MULTIPLE); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_MULTIPLE); ?>"
                value="1"
                type="checkbox"
            <?php if ($vehicaField->allowMultiple()): ?>
                checked
            <?php endif; ?>
        >
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_MULTIPLE); ?>">
            <?php esc_html_e('Allow multiple values', 'vehica-core'); ?>
        </label>
    </div>

    <div class="vehica-edit__field">
        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::FIELDS_DEPENDENCY); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::FIELDS_DEPENDENCY); ?>"
                value="1"
                type="checkbox"
            <?php if ($vehicaField->isFieldsDependencyEnabled()): ?>
                checked
            <?php endif; ?>
        >

        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::FIELDS_DEPENDENCY); ?>">
            <?php esc_html_e('Fields dependency', 'vehica-core'); ?>
            <a
                    class="vehica-doc-link"
                    target="_blank"
                    href="https://support.vehica.com/support/solutions/articles/101000377011">
                <i class="fas fa-info-circle"></i>
                <span><?php esc_html_e('Read documentation', 'vehica-core'); ?></span>
            </a>
        </label>
    </div>

    <div class="vehica-edit__field">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PARENT_TAXONOMY); ?>">
            <?php esc_html_e('Parent field', 'vehica-core'); ?>
            <a
                    class="vehica-doc-link"
                    target="_blank"
                    href="https://support.vehica.com/support/solutions/articles/101000377013">
                <i class="fas fa-info-circle"></i>
                <span><?php esc_html_e('Read documentation', 'vehica-core'); ?></span>
            </a>
        </label>

        <div class="vehica-select">
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PARENT_TAXONOMY); ?>"
                    class="vehica-selectize"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PARENT_TAXONOMY); ?>[]"
                    multiple
            >
                <option value="0">
                    <?php esc_html_e('No parent field', 'vehica-core'); ?>
                </option>
                <?php foreach (vehicaApp('taxonomies') as $vehicaParentTaxonomy) :
                    /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaParentTaxonomy */
                    if (!$vehicaField->isChildTaxonomy($vehicaParentTaxonomy) && $vehicaParentTaxonomy->getId() !== $vehicaField->getId()) :?>
                        <option
                                value="<?php echo esc_attr($vehicaParentTaxonomy->getId()); ?>"
                            <?php if ($vehicaField->isParentTaxonomy($vehicaParentTaxonomy)) : ?>
                                selected="selected"
                            <?php endif; ?>
                        >
                            <?php echo esc_html($vehicaParentTaxonomy->getName()); ?>
                        </option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="vehica-edit__field">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PANEL_TERMS); ?>">
            <?php esc_html_e('Frontend Panel - Options', 'vehica-core'); ?>
        </label>

        <div>
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PANEL_TERMS); ?>"
                    class="vehica-selectize"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::PANEL_TERMS); ?>[]"
                    placeholder="<?php esc_attr_e('All in alphabetical order', 'vehica-core'); ?>"
                    multiple
            >
                <?php
                $vehicaPanelTerms = $vehicaField->getPanelTermIds();

                foreach ($vehicaField->getPanelTerms() as $vehicaTerm) : ?>
                    <option
                            value="<?php echo esc_attr($vehicaTerm->getId()); ?>"
                            selected
                    >
                        <?php echo esc_html($vehicaTerm->getName()); ?>
                    </option>
                <?php endforeach; ?>

                <?php foreach ($vehicaField->getTerms() as $vehicaTerm) : /* @var \Vehica\Model\Term\Term $vehicaTerm */
                    if (in_array($vehicaTerm->getId(), $vehicaPanelTerms, true)) {
                        continue;
                    }
                    ?>
                    <option value="<?php echo esc_attr($vehicaTerm->getId()); ?>">
                        <?php echo esc_html($vehicaTerm->getName()); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

<?php if (!$vehicaField->allowMultiple()) : ?>
    <div class="vehica-edit__field">
        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_NEW_VALUES); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_NEW_VALUES); ?>"
                value="1"
                type="checkbox"
            <?php if ($vehicaField->allowNewValues()) : ?>
                checked
            <?php endif; ?>
        >

        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Taxonomy\Taxonomy::ALLOW_NEW_VALUES); ?>">
            <?php esc_html_e('Allow users to add new values via the front-end panel - available only for single choice (1 value)', 'vehica-core'); ?>
        </label>
    </div>
<?php
endif;