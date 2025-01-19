<?php /* @var \Vehica\Model\Post\Field\TrueFalseField $vehicaField */ ?>
<div class="vehica-edit__field">
    <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::INITIAL_VALUE); ?>">
        <?php esc_html_e('Initial value', 'vehica-core'); ?>
    </label>
    <p>
        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::INITIAL_VALUE); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::INITIAL_VALUE); ?>"
                type="radio"
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::TRUE); ?>"
            <?php if ($vehicaField->isInitialValueTrue()) : ?>
                checked
            <?php endif; ?>
        > <?php esc_html_e('True', 'vehica-core'); ?>
    </p>
    <p>
        <input
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::INITIAL_VALUE); ?>"
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::INITIAL_VALUE); ?>"
                type="radio"
                value="<?php echo esc_attr(\Vehica\Model\Post\Field\TrueFalseField::FALSE); ?>"
            <?php if ($vehicaField->isInitialValueFalse()) : ?>
                checked
            <?php endif; ?>
        > <?php esc_html_e('False', 'vehica-core'); ?>
    </p>
</div>