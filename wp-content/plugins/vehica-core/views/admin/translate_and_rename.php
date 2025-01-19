<div class="vehica-panel vehica-app vehica-app--styles">

    <vehica-hide-when-loaded>
        <div slot-scope="hideWhenLoadedProps">

            <?php require 'partials/header.php'; ?>

            <div class="vehica-panel__inner">

                <?php require 'partials/menu.php'; ?>

                <div class="vehica-panel__content">
                    <div class="vehica-panel__content__inner">

                        <div v-if="hideWhenLoadedProps.show">
                            <div class="vehica-loading"></div>
                        </div>

                        <template>
                            <div class="vehica-title mb-8">
                                <?php esc_html_e('Translate & Rename', 'vehica-core'); ?>
                            </div>

                            <form
                                    action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_translate_save')); ?>"
                                    method="post"
                                    v-scroll-spy
                            >

                                <button class="vehica-button mb-6">Save all changes</button>
                                <div class="vehica-localization">
                                    <div class="vehica-localization__left">
                                        <?php foreach (vehicaApp('strings') as $vehicaString) : ?>
                                            <div class="vehica-field">
                                                <div>
                                                    <strong>
                                                        <?php echo esc_html($vehicaString['name']); ?>
                                                    </strong>
                                                </div>
                                                <input
                                                        name="<?php echo esc_attr($vehicaString['key']); ?>_string"
                                                        type="text"
                                                        placeholder="<?php esc_attr_e('Translate / rename here', 'vehica-core'); ?>"
                                                    <?php if ($vehicaString['name'] !== $vehicaString['value']) : ?>
                                                        value="<?php echo esc_attr($vehicaString['value']); ?>"
                                                    <?php endif; ?>
                                                >
                                            </div>
                                        <?php endforeach; ?>

                                        <button class="vehica-button mt-3">Save all changes</button>

                                    </div>
                                    <div class="vehica-localization__right">
                                        <div class="vehica-localization__right__inner">
                                            <h3><i class="fas fa-link"></i> Slugs (part of your site's URL)</h3>
                                            <?php foreach (vehicaApp('rewrites') as $vehicaRewrite) : ?>
                                                <div class="vehica-field">
                                                    <div><?php echo esc_html($vehicaRewrite['name']); ?></div>
                                                    <div>
                                                        <input
                                                                type="text"
                                                                name="<?php echo esc_attr($vehicaRewrite['key']); ?>_rewrite"
                                                                value="<?php echo esc_attr($vehicaRewrite['value']); ?>"
                                                        >
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <button class="vehica-button mt-3 mb-0">Save all changes</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                    </div>
                    </template>
                </div>
            </div>
        </div>


</div>
</vehica-hide-when-loaded>
</div>