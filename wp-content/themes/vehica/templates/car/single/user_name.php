<?php
/* @var \Vehica\Widgets\Car\Single\TermsSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;
if (!$vehicaCar) {
    return;
}

$vehicaUser = $vehicaCar->getUser();
if (!$vehicaUser) {
    return;
}
?>
<div class="vehica-user-name">
    <?php if ($vehicaUser->isPrivateRole()) : ?>
        <?php echo esc_html($vehicaUser->getName()); ?>
    <?php else : ?>
        <a
                href="<?php echo esc_url($vehicaUser->getUrl()); ?>"
                title="<?php echo esc_attr($vehicaUser->getName()); ?>"
        >
            <?php echo esc_html($vehicaUser->getName()); ?>
        </a>
    <?php endif; ?>
</div>
