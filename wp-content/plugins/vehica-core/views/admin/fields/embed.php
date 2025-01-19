<?php
/* @var \Vehica\Model\Post\Field\EmbedField $vehicaField */
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

<div style="background: #FF8800; margin-bottom: 15px; padding: 10px; color: #222; border-radius: 8px;">
    <div class="vehica-edit__field">
        <input
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::ALLOW_RAW_HTML); ?>"
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::ALLOW_RAW_HTML); ?>"
                type="checkbox"
                value="1"
            <?php if ($vehicaField->allowRawHtml()) : ?>
                checked
            <?php endif; ?>
        >

        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\EmbedField::ALLOW_RAW_HTML); ?>">
            <?php esc_html_e('Allow Raw Html (e.g. Iframe, Scripts)', 'vehica-core'); ?>
        </label>
    </div>
    <div>
        <i class="fas fa-exclamation-triangle" style="margin-right:5px;"></i> <?php esc_html_e('Security warning - this option allows users to add any code via the Embed field to your single listing page - please turn it on only if you have moderation "on" or only verified users use the panel to eliminate the risk that someone will use it against listing page visitors.', 'vehica-core'); ?>
    </div>
</div>
