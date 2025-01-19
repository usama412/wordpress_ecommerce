<?php
/* @var \Vehica\Widgets\User\ImageUserWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaCurrentWidget, $vehicaUser;

if (!$vehicaUser || !$vehicaCurrentWidget) {
    return;
}

$vehicaImage = $vehicaUser->getImageUrl($vehicaCurrentWidget->getImageSize());
?>
<div class="vehica-user-image__align-wrapper">
    <div class="vehica-user-image__align">
        <div class="vehica-user-image__wrapper">
            <div
                    class="vehica-user-image"
                    style="padding-top:<?php echo esc_attr($vehicaCurrentWidget->getImagePadding()); ?>;"
            >
                <?php if (empty($vehicaImage)) : ?>
                    <span class="vehica-user-image-placeholder vehica-user-image__image-radius"></span>
                <?php else : ?>
                    <img
                            class="vehica-user-image-v2__image vehica-user-image__image-radius"
                            src="<?php echo esc_url($vehicaImage); ?>"
                            alt="<?php echo esc_attr($vehicaUser->getName()); ?>"
                    >
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>