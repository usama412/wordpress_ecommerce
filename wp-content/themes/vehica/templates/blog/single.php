<?php get_template_part('templates/blog/header'); ?>

<?php while (have_posts()) :the_post(); ?>
    <div class="vehica-blog__content">

        <?php if (has_post_thumbnail()) : ?>
            <div class="vehica-post-single__image">
                <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title_attribute(); ?>">
            </div>
        <?php endif; ?>

        <h1 class="vehica-post-field__name">
            <?php the_title(); ?>
        </h1>

        <div class="vehica-post-single__meta">
            <div class="vehica-post-single__meta__single">
                <div class="vehica-post-author-name">
                    <i class="far fa-user"></i>
                    <?php the_author_posts_link(); ?>
                </div>
            </div>

            <div class="vehica-post-single__meta__single">
                <div class="vehica-post-field__date">
                    <i class="far fa-calendar"></i>
                    <span><?php echo esc_html(get_the_date()); ?></span>
                </div>
            </div>

            <div class="vehica-post-single__meta__single">
                <div class="vehica-number-of-comments">
                    <i class="far fa-comment-alt"></i>
                    <span><?php comments_number(); ?></span>
                </div>
            </div>

            <div class="vehica-post-single__meta__single">
                <div class="vehica-post-single__category">
                    <i class="far fa-folder-open"></i>
                    <?php the_category(', '); ?>
                </div>
            </div>
        </div>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <div>
                <div class="vehica-post-field__text">
                    <?php the_content(); ?>
                </div>

                <div class="vehica-post-single__tags">
                    <?php the_tags('<i class="fas fa-tags"></i>', ' '); ?>
                </div>

                <?php the_posts_navigation(); ?>

                <?php wp_link_pages(); ?>

                <?php comments_template(); ?>
            </div>
        </article>
    </div>
<?php endwhile; ?>

<?php if (is_active_sidebar('vehica-sidebar')) : ?>
    <div class="vehica-blog__sidebar">
        <?php dynamic_sidebar('vehica-sidebar'); ?>
    </div>
<?php endif; ?>

<?php get_template_part('templates/blog/footer');