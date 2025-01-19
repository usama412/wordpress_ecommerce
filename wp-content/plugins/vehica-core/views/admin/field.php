<?php /* @var \Vehica\Model\Post\Field\Field $vehicaField */ ?>
<div class="vehica-panel vehica-app vehica-app--styles">
    <vehica-hide-when-loaded>
        <div slot-scope="hideWhenLoadedProps" class="vehica-panel__inner">

            <?php require 'partials/menu.php'; ?>

            <div class="vehica-panel__content">
                <div class="vehica-panel__content__inner">

                    <div v-if="hideWhenLoadedProps.show">
                        <div class="vehica-loading"></div>
                    </div>

                    <template>
                        <div class="vehica-title">
                            <?php echo esc_html($vehicaField->getName()); ?>
                        </div>

                        <div class="vehica-edit__subtitle">
                            <span><?php esc_html_e('Field Type', 'vehica-core'); ?>:</span>
                            <?php echo esc_html($vehicaField->getTypeName()); ?>
                        </div>

                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_field_save')); ?>"
                                method="post"
                        >
                            <input name="fieldId" value="<?php echo esc_attr($vehicaField->getId()); ?>" type="hidden">

                            <?php require 'fields/field.php'; ?>

                            <div>
                                <button class="vehica-button">
                                    <?php esc_html_e('Save changes', 'vehica-core'); ?>
                                </button>

                                <a
                                        class="vehica-button vehica-button--transparent"
                                        href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_car_fields')); ?>"
                                >
                                    <?php esc_html_e('Back to "Custom Fields"', 'vehica-core'); ?>
                                </a>
                            </div>
                        </form>
                    </template>
                </div>
            </div>

        </div>
    </vehica-hide-when-loaded>
</div>