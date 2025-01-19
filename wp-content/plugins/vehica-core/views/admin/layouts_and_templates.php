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
                            <div class="vehica-title">
                                <?php esc_html_e('Layouts & Templates', 'vehica-core'); ?>
                            </div>

                            <div>
                                <a
                                        class="vehica-doc-link vehica-doc-link--full-width"
                                        target="_blank"
                                        style="margin: 5px 0 20px 0;"
                                        href="https://support.vehica.com/support/solutions/articles/101000376543">
                                    <i class="fas fa-info-circle"></i> <span><?php esc_html_e('Elementor Page Builder Overview - how to edit pages, layouts and templates', 'vehica-core'); ?></span>
                                </a>
                            </div>

                            <div class="vehica-sum-up">
                                <?php foreach (vehicaApp('template_types') as $vehicaTemplateType) : ?>
                                    <div class="vehica-sum-up__row">
                                        <div class="vehica-sum-up__name">
                                            <a
                                                    title="<?php echo esc_attr($vehicaTemplateType['singular']); ?>"
                                                <?php if ($vehicaTemplateType['template']) : ?>
                                                    href="<?php echo esc_url($vehicaTemplateType['template']->getUrl()); ?>"
                                                    target="_blank"
                                                <?php else : ?>
                                                    href="#"
                                                <?php endif; ?>
                                            >
                                                <i class="<?php echo esc_attr($vehicaTemplateType['icon']); ?>"></i>
                                                <?php echo esc_html($vehicaTemplateType['singular']); ?>
                                            </a>
                                        </div>
                                        <div class="vehica-sum-up__edit">
                                            <?php if ($vehicaTemplateType['template']) : ?>
                                                <a
                                                        href="<?php echo esc_url($vehicaTemplateType['template']->getElementorEditUrl()); ?>"
                                                        target="_blank"
                                                        title="<?php esc_html_e('Edit using page builder', 'vehica-core'); ?>"
                                                        class="vehica-micro-button"
                                                >
                                                    <i class="fas fa-cog"></i>
                                                    <?php esc_html_e('Edit', 'vehica-core'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <a
                                                    href="#<?php echo esc_attr($vehicaTemplateType['type']); ?>"
                                                    title="<?php esc_attr_e('Scroll down to all options', 'vehica-core'); ?>"
                                                    class="vehica-micro-button vehica-micro-button--ghost vehica-smooth-click"
                                            >
                                                <?php esc_html_e('All Options', 'vehica-core'); ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <?php require 'partials/layouts.php'; ?>
                        </template>

                    </div>

                </div>
            </div>
        </div>
    </vehica-hide-when-loaded>

</div>