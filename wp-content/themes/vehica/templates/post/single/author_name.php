<?php
/* @var \Vehica\Widgets\Post\Single\AuthorNamePostSingleWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaUser = $vehicaCurrentWidget->getUser();

if (!$vehicaUser) {
    return;
}
?>
<div class="vehica-post-author-name">
    <a
            href="<?php echo esc_url($vehicaUser->getUrl()); ?>"
            title="<?php echo esc_attr($vehicaUser->getName()); ?>"
    >
        <?php if ($vehicaCurrentWidget->showIcon()) : ?>
            <i class="far fa-user"></i>
        <?php endif; ?>

        <?php echo esc_html($vehicaUser->getName()); ?>
    </a>
</div>