<?php get_template_part('templates/blog/header'); ?>

<?php if (!is_front_page()) : ?>
    <?php if (is_search()) : ?>
        <h1 class="vehica-blog-title"><?php echo sprintf(esc_html__('Search: %s', 'vehica'), get_query_var('s')); ?></h1>
    <?php else : ?>
        <h1 class="vehica-blog-title"><?php the_archive_title(); ?></h1>
    <?php endif; ?>
<?php endif; ?>

    <div class="vehica-blog__inner">
        <div class="vehica-blog__content">
            <?php if (have_posts()) : ?>

                <div class="vehica-posts vehica-posts--v2 vehica-grid vehica-posts--archive">
                    <?php while (have_posts()) :the_post(); ?>
                        <div class="vehica-blog-card">
                            <article <?php post_class('vehica-blog-card__inner'); ?>>

                                <?php if (has_post_thumbnail()) : ?>
                                    <a
                                            href="<?php echo esc_url(get_the_permalink()); ?>"
                                            title="<?php the_title_attribute() ?>"
                                            class="vehica-blog-card__image-static"
                                    >
                                        <img src="<?php the_post_thumbnail_url('large'); ?>">
                                    </a>
                                <?php endif; ?>

                                <div class="vehica-blog-card__content">
                                    <?php if (!empty(get_the_title())) : ?>
                                        <h3>
                                            <a
                                                    href="<?php echo esc_url(get_the_permalink()); ?>"
                                                    title="<?php the_title_attribute() ?>"
                                                    class="vehica-blog-card__title"
                                            >
                                                <?php the_title(); ?>
                                            </a>
                                        </h3>
                                    <?php endif; ?>

                                    <div class="vehica-blog-card__content__top">
                                        <div class="vehica-blog-card__author">
                                            <div class="vehica-blog-card__author__name">
                                                <i class="far fa-user"></i>
                                                <span><?php echo esc_html(get_the_author_meta('display_name')); ?></span>
                                            </div>
                                        </div>

                                        <div class="vehica-blog-card__date">
                                            <i class="far fa-calendar"></i>
                                            <span>
                                                <?php echo esc_html(get_the_date()); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <?php if (!empty(get_the_excerpt())) : ?>
                                        <div class="vehica-blog-card__excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div>
                                        <a
                                                class="vehica-button"
                                                href="<?php echo esc_url(get_the_permalink()); ?>"
                                                title="<?php the_title_attribute(); ?>"
                                        >
                                            <?php esc_html_e('Read more', 'vehica'); ?>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php
                global $wp_query;

                if ($wp_query->max_num_pages > 1) :
                    $vehicaCurrentPage = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    ?>
                    <div class="vehica-pagination-mobile vehica-pagination-mobile--blog">
                        <a
                                href="<?php echo esc_url(get_pagenum_link($vehicaCurrentPage - 1)); ?>"
                            <?php if ($vehicaCurrentPage > 1) : ?>
                                class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--left"
                            <?php else : ?>
                                class="vehica-pagination-mobile__arrow vehica-pagination-mobile__arrow--disabled vehica-pagination-mobile__arrow--left"
                            <?php endif; ?>
                        >
                            <i class="fa fa-chevron-left"></i>
                        </a>

                        <span class="vehica-pagination-mobile__start"><?php echo esc_html($vehicaCurrentPage); ?></span>
                        <span class="vehica-pagination-mobile__middle">/</span>
                        <span class="vehica-pagination-mobile__end"><?php echo esc_html($wp_query->max_num_pages); ?></span>

                        <a
                                href="<?php echo esc_url(get_pagenum_link($vehicaCurrentPage + 1)); ?>"
                            <?php if ($vehicaCurrentPage < $wp_query->max_num_pages) : ?>
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
                            <?php if ($vehicaCurrentPage > 1) : ?>
                                <a href="<?php echo esc_url(get_pagenum_link($vehicaCurrentPage - 1)); ?>">
                                    <span class="vehica-pagination__arrow vehica-pagination__arrow--left">
                                        <i class="fa fa-chevron-left"></i>
                                    </span>
                                </a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $wp_query->max_num_pages; $i++) : ?>
                                <a
                                    <?php if ($i === $vehicaCurrentPage) : ?>
                                        class="vehica-pagination__page vehica-pagination__page--active"
                                    <?php else : ?>
                                        class="vehica-pagination__page"
                                    <?php endif; ?>
                                        href="<?php echo esc_url(get_pagenum_link($i)); ?>"
                                >
                                    <?php echo esc_html($i); ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($vehicaCurrentPage < $wp_query->max_num_pages) : ?>
                                <a href="<?php echo esc_url(get_pagenum_link($vehicaCurrentPage + 1)); ?>">
                                    <span class="vehica-pagination__arrow vehica-pagination__arrow--right">
                                        <i class="fa fa-chevron-right"></i>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>

                <div class="vehica-no-results-search">
                    <h3><?php esc_html_e("Couldn't find what you're looking for!", 'vehica'); ?></h3>
                    <h4><?php esc_html_e('If you want to rephrase your query, here is your chance:', 'vehica'); ?></h4>

                    <?php get_search_form(); ?>
                </div>
            <?php
            endif; ?>
        </div>

        <?php if (is_active_sidebar('vehica-sidebar')) : ?>
            <div class="vehica-blog__sidebar">
                <?php dynamic_sidebar('vehica-sidebar'); ?>
            </div>
        <?php endif; ?>
    </div>


<?php get_template_part('templates/blog/footer'); ?>