<?php
/* @var \Vehica\Widgets\Car\Single\NumberFieldSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if (!$vehicaCurrentWidget->hasNumberField()) {
    return;
}

if ($vehicaCurrentWidget->hasValue()) :?>
    <div class="vehica-car-number-field">
        <?php echo esc_html($vehicaCurrentWidget->getValue()); ?>
    </div>
<?php
endif;