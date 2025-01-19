<?php
/* @var \Vehica\Widgets\Post\Archive\PostsPostArchiveWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;

if ($vehicaCurrentWidget->hasPosts()) : ?>
    <div class="vehica-posts vehica-grid vehica-posts--archive">
        <?php
        global $vehicaCurrentPost;
        foreach ($vehicaCurrentWidget->getPosts() as $vehicaCurrentPost) : ?>
            <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                <?php get_template_part('templates/card/post/card_v1'); ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($vehicaCurrentWidget->hasPagination()) : ?>

        <div class="vehica-pagination-mobile vehica-pagination-mobile--blog">
            <a
                    href="<?php echo esc_url($vehicaCurrentWidget->getPaginationUrl($vehicaCurrentWidget->getCurrentPage()
                                                                                - 1)); ?>"
                <?php if ($vehicaCurrentWidget->hasPaginationPrev()) : ?>
                    class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--left"
                <?php else : ?>
                    class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--disabled vehica-pagination-mobile__arrow--left"
                <?php endif; ?>
            >
                <i class="fa fa-chevron-left"></i>
            </a>

            <span class="vehica-pagination-mobile__start"><?php echo esc_html($vehicaCurrentWidget->getCurrentPage()); ?></span>
            <span class="vehica-pagination-mobile__middle">/</span>
            <span class="vehica-pagination-mobile__end"><?php echo esc_html($vehicaCurrentWidget->getPageCount()); ?></span>

            <a
                    href="<?php echo esc_url($vehicaCurrentWidget->getPaginationUrl($vehicaCurrentWidget->getCurrentPage()
                                                                                + 1)); ?>"
                <?php if ($vehicaCurrentWidget->hasPaginationNext()) : ?>
                    class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--right"
                <?php else : ?>
                    class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--disabled vehica-pagination-mobile__arrow--right"
                <?php endif; ?>
            >
                <i class="fa fa-chevron-right"></i>
            </a>
        </div>

        <div class="vehica-pagination vehica-pagination--blog">
            <div class="vehica-pagination__inner">
                <?php if ($vehicaCurrentWidget->hasPaginationPrev()) : ?>
                    <a href="<?php echo esc_url($vehicaCurrentWidget->getPaginationUrl($vehicaCurrentWidget->getCurrentPage()
                                                                                   - 1)); ?>">
                            <span class="vehica-pagination__arrow vehica-pagination__arrow--left">
                                <i class="fa fa-chevron-left"></i>
                            </span>
                    </a>
                <?php endif; ?>


                <?php foreach ($vehicaCurrentWidget->getPages() as $vehicaPage) : ?>
                    <a
                        <?php if ($vehicaCurrentWidget->isActivePage($vehicaPage)) : ?>
                            class="vehica-pagination__page vehica-pagination__page--active"
                        <?php else : ?>
                            class="vehica-pagination__page"
                        <?php endif; ?>
                            href="<?php echo esc_url($vehicaCurrentWidget->getPaginationUrl($vehicaPage)); ?>"
                    >
                        <?php echo esc_html($vehicaPage); ?>
                    </a>
                <?php endforeach; ?>

                <?php if ($vehicaCurrentWidget->hasPaginationNext()) : ?>
                    <a href="<?php echo esc_url($vehicaCurrentWidget->getPaginationUrl($vehicaCurrentWidget->getCurrentPage()
                                                                                   + 1)); ?>">
                            <span class="vehica-pagination__arrow vehica-pagination__arrow--right">
                                <i class="fa fa-chevron-right"></i>
                            </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
<?php else : ?>
    <h1><?php echo esc_html(vehicaApp('no_results_blog_string')); ?></h1>
<?php endif; ?>
