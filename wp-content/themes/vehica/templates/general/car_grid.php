<?php
/* @var \Vehica\Widgets\General\CarGridGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Components\Card\Car\CardV1 $vehicaCarCard */
global $vehicaCarCard;
$vehicaCarCard = $vehicaCurrentWidget->getCard();

if (!$vehicaCurrentWidget->hasCars()) {
    return;
}
?>
<div class="vehica-app">
    <div class="vehica-grid">
        <?php foreach ($vehicaCurrentWidget->getCars() as $vehicaCurrentCar) :
            /* @var \Vehica\Model\Post\Car $vehicaCurrentCar */
            if ($vehicaCarCard->getType() === \Vehica\Components\Card\Car\Card::TYPE_V3) :
                $vehicaCarCard->loadTemplate($vehicaCurrentCar);
            else : ?>
                <div <?php $vehicaCurrentWidget->print_render_attribute_string('column'); ?>>
                    <?php $vehicaCarCard->loadTemplate($vehicaCurrentCar); ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
