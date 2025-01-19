<?php
/* @var \Vehica\Model\Post\Field\DateTimeField $vehicaField */
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

<vehica-date-time-field
        :opt-is-range="<?php echo esc_attr($vehicaField->isRange() ? 'true' : 'false'); ?>"
        opt-type="<?php echo esc_attr($vehicaField->getValueType()); ?>"
        :opt-week-start="<?php echo esc_attr($vehicaField->getWeekStart()); ?>"
>
    <div slot-scope="fieldProps">
        <div class="vehica-edit__field">
            <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::VALUE_TYPE); ?>">
                <?php esc_html_e('Type', 'vehica-core'); ?>
            </label>

            <div class="vehica-select">
                <select
                        id="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::VALUE_TYPE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::VALUE_TYPE); ?>"
                        :value="fieldProps.type"
                        @change="fieldProps.setType($event.target.value)"
                >
                    <option value="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::TYPE_DATE_TIME); ?>">
                        <?php esc_html_e('Date and time', 'vehica-core'); ?>
                    </option>

                    <option value="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::TYPE_DATE); ?>">
                        <?php esc_html_e('Date', 'vehica-core'); ?>
                    </option>

                    <option value="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::TYPE_TIME); ?>">
                        <?php esc_html_e('Time', 'vehica-core'); ?>
                    </option>
                </select>
            </div>
        </div>

        <div class="vehica-edit__field">
            <input
                    id="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::IS_RANGE); ?>"
                    name="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::IS_RANGE); ?>"
                    type="checkbox"
                    value="1"
                    :checked="fieldProps.isRange"
                    @click="fieldProps.setIsRange"
            >
            <label for="<?php echo esc_attr(\Vehica\Model\Post\Field\DateTimeField::IS_RANGE); ?>">
                <?php esc_html_e('Is range', 'vehica-core'); ?>
            </label>
        </div>

        <div class="clear"></div>
    </div>
</vehica-date-time-field>