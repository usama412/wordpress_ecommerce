<?php /** @noinspection NotOptimalIfConditionsInspection */
/* @var \Vehica\Model\Post\Post $vehicaCurrentPost */
/* @var \Vehica\Widgets\Post\Archive\PostsV2PostArchiveWidget $vehicaCurrentWidget */
global $vehicaCurrentPost, $vehicaCurrentWidget;
?>
<article <?php post_class('vehica-blog-card__inner', $vehicaCurrentPost->getId()); ?>>
    <a
            class="vehica-blog-card__image"
            href="<?php echo esc_url($vehicaCurrentPost->getUrl()); ?>"
            title="<?php echo esc_attr($vehicaCurrentPost->getName()); ?>"
    >
        <?php if (has_post_thumbnail($vehicaCurrentPost->getId())) : ?>
            <img
                    class="lazyload"
                    src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                    data-srcset="<?php echo esc_attr(wp_get_attachment_image_srcset($vehicaCurrentPost->getImageId(), 'large')); ?>"
                    data-sizes="auto"
                    alt="<?php echo esc_attr($vehicaCurrentPost->getName()); ?>"
            >
            <div class="vehica-blog-card__image-mask"></div>
        <?php else : ?>
            <div class="vehica-blog-card__image-placeholder"></div>
        <?php endif; ?>
    </a>

    <div class="vehica-blog-card__content">

        <h3 <?php $vehicaCurrentWidget->print_render_attribute_string('post_title'); ?>>
            <a
                    class="vehica-blog-card__title"
                    href="<?php echo esc_url($vehicaCurrentPost->getUrl()); ?>"
                    title="<?php echo esc_attr($vehicaCurrentPost->getName()); ?>"
            >
                <?php echo esc_html($vehicaCurrentPost->getName()); ?>
            </a>
        </h3>

        <?php if ($vehicaCurrentWidget->showAuthor() || $vehicaCurrentWidget->showPublishDate()) : ?>
            <div class="vehica-blog-card__content__top">
                <?php if ($vehicaCurrentWidget->showAuthor()) : ?>
                    <div class="vehica-blog-card__author">
                        <?php if ($vehicaCurrentWidget->hasAuthorImage($vehicaCurrentPost)) : ?>
                            <div class="vehica-blog-card__author__image">
                                <img
                                        src="<?php echo esc_url($vehicaCurrentWidget->getAuthorImage($vehicaCurrentPost)); ?>"
                                        alt="<?php echo esc_attr($vehicaCurrentPost->getUserName()); ?>"
                                >
                            </div>
                        <?php endif; ?>

                        <div class="vehica-blog-card__author__name">
                            <?php if (!$vehicaCurrentWidget->hasAuthorImage($vehicaCurrentPost)) : ?>
                                <i class="far fa-user"></i>
                            <?php endif; ?>
                            <?php echo esc_attr($vehicaCurrentPost->getUserName()); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($vehicaCurrentWidget->showPublishDate()) : ?>
                    <div class="vehica-blog-card__date">
                        <i class="far fa-calendar"></i>
                        <span><?php echo esc_html($vehicaCurrentPost->getPublishDate()); ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($vehicaCurrentWidget->showExcerpt()) : ?>
            <div class="vehica-blog-card__excerpt">
                <?php $vehicaCurrentWidget->displayExcerpt($vehicaCurrentPost); ?>
            </div>
        <?php endif; ?>

        <?php if ($vehicaCurrentWidget->showReadMoreButton()) : ?>
            <div class="vehica-blog-card-v2__button-wrapper">
                <a
                        class="vehica-button"
                        href="<?php echo esc_url($vehicaCurrentPost->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaCurrentPost->getName()); ?>"
                >
                    <?php echo esc_html(vehicaApp('read_more_string')); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>
