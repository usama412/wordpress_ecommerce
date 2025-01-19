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
                            <?php esc_html_e('Basic Setup', 'vehica-core'); ?>
                        </div>

                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_save_basic_setup')); ?>"
                                method="post"
                        >
                            <input id="vehica-hook" type="hidden" name="hook" value="">

                            <div v-scroll-spy="{time: 1000}">
                                <?php require 'partials/settings.php'; ?>
                            </div>
                        </form>
                    </template>
                </div>

            </div>

        </div>
    </vehica-hide-when-loaded>
</div>