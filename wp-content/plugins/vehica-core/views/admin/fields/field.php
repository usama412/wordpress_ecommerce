<?php /* @var \Vehica\Model\Post\Field\Field $vehicaField */ ?>

    <div class="vehica-edit__field">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::NAME); ?>">
            <?php esc_html_e('Name', 'vehica-core'); ?>
        </label>

        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::NAME); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::NAME); ?>"
                type="text"
                value="<?php echo esc_attr($vehicaField->getName()); ?>"
        >
    </div>

<?php if ($vehicaField instanceof \Vehica\Model\Post\Field\RewritableField) : ?>
    <div class="vehica-edit__field">
        <label for="<?php echo esc_attr(\Vehica\Core\Rewrite\Rewrite::REWRITE); ?>">
            <?php esc_html_e('Slug', 'vehica-core'); ?>
        </label>

        <input
                id="<?php echo esc_attr(\Vehica\Core\Rewrite\Rewrite::REWRITE); ?>"
                name="<?php echo esc_attr(\Vehica\Core\Rewrite\Rewrite::REWRITE); ?>"
                type="text"
                value="<?php echo esc_attr($vehicaField->getRewrite()); ?>"
        >
    </div>
<?php endif; ?>

<?php require $vehicaField->getType() . '.php' ?>

    <div class="vehica-edit__field vehica-edit__field--field-visibility">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::VISIBILITY); ?>">
            <strong><?php esc_html_e('Field Visibility', 'vehica-core'); ?></strong><br>
            <?php esc_html_e('Who can see this field on the single listing page?', 'vehica-core'); ?>
        </label>

        <div>
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::VISIBILITY); ?>"
                    class="vehica-selectize"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::VISIBILITY); ?>[]"
                    placeholder="<?php esc_attr_e('Everyone', 'vehica-core'); ?>"
                    multiple
            >
                <?php foreach (\Vehica\Model\Post\Field\Field::getVisibilityOptions() as $vehicaKey => $vehicaName) : ?>
                    <option
                            value="<?php echo esc_attr($vehicaKey); ?>"
                        <?php if ($vehicaField->isVisibilitySettingSet($vehicaKey)) : ?>
                            selected
                        <?php endif; ?>
                    ><?php echo esc_html($vehicaName); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="vehica-edit__field vehica-edit__field--field-visibility">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_VISIBILITY); ?>">
            <strong><?php esc_html_e('Frontend Panel - Visibility', 'vehica-core'); ?></strong><br>
            <?php esc_html_e('Who can add this field value via front-end panel?', 'vehica-core'); ?>
        </label>

        <div>
            <select
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_VISIBILITY); ?>"
                    class="vehica-selectize"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_VISIBILITY); ?>[]"
                    placeholder="<?php esc_attr_e('Everyone', 'vehica-core'); ?>"
                    multiple
            >
                <?php foreach (\Vehica\Model\Post\Field\Field::getPanelVisibilityOptions() as $vehicaKey => $vehicaName) : ?>
                    <option
                            value="<?php echo esc_attr($vehicaKey); ?>"
                        <?php if ($vehicaField->isPanelVisibilitySettingSet($vehicaKey)) : ?>
                            selected
                        <?php endif; ?>
                    ><?php echo esc_html($vehicaName); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

<?php
if (
    !$vehicaField instanceof \Vehica\Model\Post\Field\DateTimeField
    && !$vehicaField instanceof \Vehica\Model\Post\Field\GalleryField
    && !$vehicaField instanceof \Vehica\Model\Post\Field\AttachmentsField
    && !$vehicaField instanceof \Vehica\Model\Post\Field\HeadingField
    && !$vehicaField instanceof \Vehica\Model\Post\Field\LocationField
    && !$vehicaField instanceof \Vehica\Model\Post\Field\TrueFalseField
):
    ?>
    <div class="vehica-edit__field">
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_PLACEHOLDER); ?>">
            <?php esc_html_e('Frontend Panel - Placeholder', 'vehica-core'); ?>
        </label>

        <input
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_PLACEHOLDER); ?>"
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\Field::PANEL_PLACEHOLDER); ?>"
                type="text"
                value="<?php echo esc_attr($vehicaField->getPanelPlaceholder()); ?>"
        >
    </div>
<?php
endif;
