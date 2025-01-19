<?php
/* @var \Vehica\Model\Post\Field\Price\PriceField $vehicaField */
?>
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
