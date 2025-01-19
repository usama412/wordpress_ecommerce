<?php
/* @var \Vehica\Widgets\Car\Single\PriceSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaCar = $vehicaCurrentWidget->getCar();
if (!$vehicaCar) {
    return;
}

if (!$vehicaCurrentWidget->hasPriceField()) {
    return;
}

if ($vehicaCurrentWidget->hasValue()) :?>
    <div class="vehica-car-price">
        <?php echo esc_html($vehicaCurrentWidget->getValue()); ?>
    </div>
<?php elseif ($vehicaCurrentWidget->showContactForPrice()) : ?>
    <div class="vehica-car-price">
        <?php echo esc_html(vehicaApp('contact_for_price_string')); ?>
    </div>
<?php
endif;