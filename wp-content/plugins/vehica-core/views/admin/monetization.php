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
                            <?php esc_html_e('Monetization', 'vehica-core'); ?>
                        </div>

                        <div style="margin-bottom:20px">
                            <?php esc_html_e('You can use "monetization system" to require payments when users submit listings on your website. If you decide to use WooCommerce Payment Gateways, please install: ', 'vehica-core'); ?>

                            <a href="https://wordpress.org/plugins/woocommerce/" style="text-decoration: underline; font-weight:bold;"><?php esc_html_e('WooCommerce Plugin', 'vehica-core'); ?></a>.

                                <?php esc_html_e('It will be responsible for all payments and you can integrate any WooCommerce Payment Gateways.', 'vehica-core'); ?>
                        </div>

                        <div>
                            <a
                                    class="vehica-doc-link vehica-doc-link--full-width"
                                    target="_blank"
                                    href="https://support.vehica.com/support/solutions/articles/101000376990">
                                <i class="fas fa-info-circle"></i> <span><?php esc_html_e('Click here to learn more about Monetization Systems', 'vehica-core'); ?></span>
                            </a>
                        </div>


                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_save_monetization')); ?>"
                                method="post"
                        >
                            <input id="vehica-hook" type="hidden" name="hook" value="">

                            <div v-scroll-spy="{time: 1000}">
                                <?php require 'partials/monetization/monetization_settings.php'; ?>

                                <?php require 'partials/monetization/monetization_packages.php'; ?>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </vehica-hide-when-loaded>
</div>