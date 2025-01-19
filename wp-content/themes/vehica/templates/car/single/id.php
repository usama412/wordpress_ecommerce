<?php
/* @var \Vehica\Widgets\Car\Single\IdSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;

if (!$vehicaCar) {
    return;
}
?>
<div class="vehica-car-offer-id">
    <?php if ($vehicaCurrentWidget->hasText()) : ?>
        <span class="vehica-car-offer-id__label">
            <?php echo esc_html($vehicaCurrentWidget->getText() . $vehicaCar->getId()); ?>
        </span>
    <?php else : ?>
        <span class="vehica-car-offer-id__value">
            <?php echo esc_html($vehicaCar->getId()); ?>
        </span>
    <?php endif; ?>
</div>