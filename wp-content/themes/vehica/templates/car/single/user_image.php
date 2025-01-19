<?php
/* @var \Vehica\Widgets\Car\Single\UserImageSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\User\User $vehicaUser */
$vehicaUser = $vehicaCurrentWidget->getUser();
if (!$vehicaUser) {
    return;
}

$vehicaImage = $vehicaUser->getImageUrl($vehicaCurrentWidget->getImageSize());
if (empty($vehicaImage)) {
    return;
}
?>
<div class="vehica-user-image-v2__align-wrapper">
    <div class="vehica-user-image-v2__align">
        <div class="vehica-user-image-v2__wrapper">
            <?php if ($vehicaUser->isPrivateRole()) : ?>
                <div
                        class="vehica-user-image-v2"
                        style="padding-top:<?php echo esc_attr($vehicaCurrentWidget->getImagePadding()); ?>;"
                >
                    <?php if (empty($vehicaImage)) : ?>
                        <span class="vehica-user-image-v2-placeholder vehica-user-image__image-radius"></span>
                    <?php else : ?>
                        <img
                                class="vehica-user-image__image-radius"
                                src="<?php echo esc_url($vehicaImage); ?>"
                                alt="<?php echo esc_attr($vehicaUser->getName()); ?>"
                        >
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <a
                        class="vehica-user-image-v2"
                        style="padding-top:<?php echo esc_attr($vehicaCurrentWidget->getImagePadding()); ?>;"
                        href="<?php echo esc_url($vehicaUser->getUrl()); ?>"
                        title="<?php echo esc_attr($vehicaUser->getName()); ?>"
                >
                    <?php if (empty($vehicaImage)) : ?>
                        <span class="vehica-user-image-v2-placeholder vehica-user-image__image-radius"></span>
                    <?php else : ?>
                        <img
                                class="vehica-user-image__image-radius"
                                src="<?php echo esc_url($vehicaImage); ?>"
                                alt="<?php echo esc_attr($vehicaUser->getName()); ?>"
                        >
                    <?php endif; ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>