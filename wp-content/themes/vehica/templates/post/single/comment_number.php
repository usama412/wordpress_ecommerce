<?php
/* @var \Vehica\Widgets\Post\Single\NameSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if ( ! $vehicaPost) {
    return;
}
$vehicaCommentsCount = $vehicaPost->getCommentsCount();
?>

<div class="vehica-number-of-comments">
    <i class="far fa-comment-alt"></i>

    <?php if ($vehicaCommentsCount > 0) : ?>
        <a href="#vehica-comments">
            <span>
                <?php echo esc_html($vehicaPost->getCommentsCount()); ?>
                <?php echo esc_html(vehicaApp('comments_string')); ?>
            </span>
        </a>
    <?php else : ?>
        <span>
            <?php echo esc_html($vehicaPost->getCommentsCount()); ?>
            <?php echo esc_html(vehicaApp('comments_string')); ?>
        </span>
    <?php endif; ?>
</div>
