<?php get_template_part('templates/blog/header'); ?>

<?php while (have_posts()):the_post(); ?>
    <div class="vehica-blog-page">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h1 class="vehica-blog-title"><?php the_title(); ?></h1>

            <div>

                <div class="vehica-post-field__text">
                    <?php the_content(); ?>

                    <?php wp_link_pages(); ?>
                </div>

                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="vehica-comments">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
            </div>

        </article>
    </div>
<?php endwhile;

get_template_part('templates/blog/footer');