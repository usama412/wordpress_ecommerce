<?php
/* @var \Vehica\Widgets\Post\Single\DateSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}
?>
<div class="vehica-post-field__date">
    <i class="far fa-calendar"></i>
    <span>
    <?php echo esc_html($vehicaPost->getPublishDate()); ?>
    </span>
</div>
