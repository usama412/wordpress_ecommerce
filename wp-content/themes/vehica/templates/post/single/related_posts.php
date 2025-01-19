<?php
/* @var \Vehica\Widgets\Post\Single\RelatedPostsSinglePostWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if ( ! $vehicaCurrentWidget->hasPosts()) {
    return;
}

?>
<div class="vehica-posts">
    <h3 class="vehica-posts__related-title"><?php echo esc_html(vehicaApp('related_posts_string')); ?></h3>

    <div class="vehica-posts vehica-grid vehica-posts--archive">
        <?php
        global $vehicaCurrentPost;
        foreach ($vehicaCurrentWidget->getPosts() as $vehicaCurrentPost) : ?>
            <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                <?php get_template_part('templates/card/post/card_v1'); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
