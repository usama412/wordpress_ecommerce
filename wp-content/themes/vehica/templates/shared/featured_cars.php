<?php
/* @var \Vehica\Components\Card\Car\Card $vehicaCarCard */
global $vehicaCarCard, $vehicaFeaturedCars, $vehicaCurrentCar;
$vehicaCurrentCar = $vehicaFeaturedCars->getMainCar();
$vehicaCars = $vehicaFeaturedCars->getCars();
?>
<div class="vehica-featured-v1__vehicles vehica-featured-v1__vehicles--count-<?php echo esc_attr(count($vehicaCars)); ?>">
    <div class="vehica-featured-v1__big-card">
        <?php $vehicaCarCard->loadBigCardTemplate(); ?>
    </div>

    <div class="vehica-featured-v1__grid">
        <?php foreach ($vehicaCars as $vehicaIndex => $vehicaCurrentCar) : ?>
            <div class="vehica-featured-v1__vehicle vehica-featured-v1__vehicle--<?php echo esc_attr($vehicaIndex + 1); ?>">
                <?php $vehicaCarCard->loadTemplate($vehicaCurrentCar); ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
