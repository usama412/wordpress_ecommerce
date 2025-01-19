<?php
/* @var \Vehica\Widgets\User\CarsUserWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget, $vehicaCarCard;

$vehicaCarCard = $vehicaCurrentWidget->getCardV1();
$vehicaCars    = $vehicaCurrentWidget->getCars();

if ($vehicaCars->isEmpty()) : ?>
<?php else : ?>
    <div class="vehica-app vehica-user-vehicles">
        <h3 class="vehica-user-vehicles__title"><?php echo esc_html(vehicaApp('available_cars_in_our_offer_string')); ?></h3>
        <div class="vehica-grid">
            <?php foreach ($vehicaCars as $vehicaCar) : ?>
                <?php $vehicaCarCard->loadTemplate($vehicaCar); ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php
endif;