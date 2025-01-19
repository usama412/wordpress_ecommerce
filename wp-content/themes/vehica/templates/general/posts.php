<?php
/* @var \Vehica\Widgets\General\PostsGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if ($vehicaCurrentWidget->hasPosts()) : ?>
    <div class="vehica-posts vehica-posts--v2 vehica-grid vehica-posts--archive">
        <?php
        global $vehicaCurrentPost;
        foreach ($vehicaCurrentWidget->getPosts() as $vehicaCurrentPost) : ?>
            <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                <?php get_template_part('templates/card/post/card_v2'); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <h1><?php echo esc_html(vehicaApp('no_results_blog_string')); ?></h1>
<?php endif; ?>

