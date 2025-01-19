<div class="vehica-panel vehica-app vehica-app--styles">

    <vehica-hide-when-loaded>
        <div slot-scope="hideWhenLoadedProps">
            <vehica-car-fields
                    :initial-fields="<?php echo htmlspecialchars(json_encode(vehicaApp('car_fields')->all())); ?>"
                    save-fields-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_car_fields_update')); ?>"
                    duplicate-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_duplicate_post')); ?>"
                    duplicate-in-progress-text="<?php esc_attr_e('Duplicate in progress', 'vehica-core'); ?>"
                    duplicate-success-text="<?php esc_attr_e('Duplicate success', 'vehica-core'); ?>"
            >
                <div class="vehica-panel__inner" slot-scope="carFieldsProps">

                    <?php require 'partials/menu.php'; ?>

                    <div class="vehica-panel__content">
                        <div class="vehica-panel__content__inner">

                            <div v-if="hideWhenLoadedProps.show">
                                <div class="vehica-loading"></div>
                            </div>

                            <?php require 'partials/car_fields_settings.php'; ?>
                        </div>
                    </div>
                </div>
            </vehica-car-fields>
        </div>
    </vehica-hide-when-loaded>
</div>
