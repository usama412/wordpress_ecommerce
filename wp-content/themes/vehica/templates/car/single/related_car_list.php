<?php
/* @var \Vehica\Widgets\Car\Single\RelatedCarListSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
global $vehicaCarCard;
/* @var \Vehica\Components\Card\Car\CardV1 $vehicaCarCard */
$vehicaCarCard = $vehicaCurrentWidget->getCardV1();

if (!$vehicaCurrentWidget->hasRelatedCars()) {
    return;
}
?>
<div class="vehica-app">
    <?php if ($vehicaCurrentWidget->showHeading()) : ?>
        <h3 class="vehica-section-label">
            <?php echo esc_html($vehicaCurrentWidget->getHeading()); ?>
        </h3>
    <?php endif; ?>

    <div class="vehica-grid">
        <?php foreach ($vehicaCurrentWidget->getRelatedCars() as $vehicaCurrentCar) :
            /* @var \Vehica\Model\Post\Car $vehicaCurrentCar */
            ?>
            <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                <?php $vehicaCarCard->loadTemplate($vehicaCurrentCar); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
