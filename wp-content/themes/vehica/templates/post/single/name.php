<?php
/* @var \Vehica\Widgets\Post\Single\NameSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}
?>

<<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?> class="vehica-post-field__name">
<?php echo esc_html(get_the_title($vehicaPost->getModel())); ?>
</<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?>>
