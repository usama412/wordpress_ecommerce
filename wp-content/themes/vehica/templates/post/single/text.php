<?php
/* @var \Vehica\Widgets\Post\Single\TextSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}
?>
<div class="vehica-post-field__text">
    <?php $vehicaPost->displayContent(); ?>
</div>
