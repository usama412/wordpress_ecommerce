<?php
/* @var \Vehica\Widgets\General\RecentPostsGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-recent-posts">
    <?php foreach ($vehicaCurrentWidget->getPosts() as $vehicaPost) :
        /* @var \Vehica\Model\Post\Post $vehicaPost */
        ?>
        <div class="vehica-recent-posts__single">
            <div class="vehica-recent-posts__single__image">
                <a
                        href="<?php echo esc_url($vehicaPost->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaPost->getName()); ?>"
                >
                    <?php if ($vehicaPost->hasImageUrl('thumbnail')) : ?>
                        <img
                                src="<?php echo esc_url($vehicaPost->getImageUrl('thumbnail')); ?>"
                                alt="<?php echo esc_attr($vehicaPost->getName()); ?>"
                        >
                    <?php else : ?>
                        <div class="vehica-recent-posts__single__image-placeholder"></div>
                    <?php endif; ?>
                </a>
            </div>
            <div class="vehica-recent-posts__single__content">
                <a
                        href="<?php echo esc_url($vehicaPost->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaPost->getName()); ?>"
                        class="vehica-recent-posts__single__title"
                >
                    <?php echo esc_html($vehicaPost->getName()); ?>
                </a>

                <a
                        href="<?php echo esc_url($vehicaPost->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaPost->getName()); ?>"
                        class="vehica-recent-posts__single__read-more"
                >
                    <?php echo esc_html(vehicaApp('read_more_string')); ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
    <a
            href="<?php echo esc_url(get_post_type_archive_link('post')); ?>"
            title="<?php echo esc_attr(vehicaApp('read_all_posts_string')); ?>"
            class="vehica-recent-posts__view-all"
    >
        <?php echo esc_html(vehicaApp('read_all_posts_string')); ?>
    </a>
</div>
