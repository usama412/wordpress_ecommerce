<?php get_template_part('templates/blog/header'); ?>

    <div class="vehica-404">

        <h2 class="vehica-404-title"><?php esc_html_e('Oops! That page can’t be found.', 'vehica'); ?></h2>

        <h1 class="vehica-404-big"><?php esc_attr_e('404', 'vehica'); ?></h1>

        <a class="vehica-button" href="<?php echo esc_url(home_url()); ?>">
            <?php esc_html_e('Back to home page', 'vehica'); ?>
        </a>

    </div>

<?php get_template_part('templates/blog/footer');