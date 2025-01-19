<?php /* @var \Vehica\Model\Post\Field\TextField $vehicaField */ ?>
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
        <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\TextField::COMPARE_LOGIC); ?>">
            <?php esc_html_e('Search form compare logic', 'vehica-core'); ?>
        </label>
    </div>

    <div class="vehica-select">
        <select
                name="<?php echo esc_attr(\Vehica\Model\Post\Field\TextField::COMPARE_LOGIC); ?>"
                id="<?php echo esc_attr(\Vehica\Model\Post\Field\TextField::COMPARE_LOGIC); ?>"
        >
            <option
                    value="<?php echo esc_attr(\Vehica\Model\Post\Field\TextField::COMPARE_LOGIC_LIKE); ?>"
                <?php if ($vehicaField instanceof \Vehica\Model\Post\Field\TextField && $vehicaField->isCompareLogicLike()) : ?>
                    selected="selected"
                <?php endif; ?>
            >
                <?php esc_html_e('Like', 'vehica-core'); ?>
            </option>
            <option
                    value="<?php echo esc_attr(\Vehica\Model\Post\Field\TextField::COMPARE_LOGIC_EQUAL); ?>"
                <?php if ($vehicaField->isCompareLogicEqual()) : ?>
                    selected="selected"
                <?php endif; ?>
            >
                <?php esc_html_e('Equal', 'vehica-core'); ?>
            </option>
        </select>
    </div>
</div>

