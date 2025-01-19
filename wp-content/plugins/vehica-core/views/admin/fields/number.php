<?php /* @var \Vehica\Model\Post\Field\NumberField $vehicaField */ ?>
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
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DECIMAL_PLACES); ?>">
        <?php esc_html_e('Decimal places', 'vehica-core'); ?>
    </label>

    <input
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DECIMAL_PLACES); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DECIMAL_PLACES); ?>"
            type="text"
            value="<?php echo esc_attr($vehicaField->getDecimalPlaces()); ?>"
    >
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_BEFORE); ?>">
        <?php esc_html_e('Text before value', 'vehica-core'); ?>
    </label>
    <input
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_BEFORE); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_BEFORE); ?>"
            type="text"
            value="<?php echo esc_attr($vehicaField->getDisplayBefore()); ?>"
    >
</div>

<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_AFTER); ?>">
        <?php esc_html_e('Text after value', 'vehica-core'); ?>
    </label>
    <input
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_AFTER); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::DISPLAY_AFTER); ?>"
            type="text"
            value="<?php echo esc_attr($vehicaField->getDisplayAfter()); ?>"
    >
</div>

<div class="vehica-edit__field">
    <input
            id="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::HIDE_THOUSANDS_SEPARATOR); ?>"
            name="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::HIDE_THOUSANDS_SEPARATOR); ?>"
            type="checkbox"
            value="1"
        <?php if ($vehicaField->hideThousandsSeparator()) : ?>
            checked
        <?php endif; ?>
    >
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\NumberField::HIDE_THOUSANDS_SEPARATOR); ?>">
        <?php esc_html_e('Hide Thousands Separator', 'vehica-core'); ?>
    </label>
</div>
