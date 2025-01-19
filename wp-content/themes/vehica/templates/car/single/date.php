<?php
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;
if (!$vehicaCar) {
    return;
}
?>
<div class="vehica-car-date">
    <?php echo esc_html($vehicaCar->getPublishDate()); ?>
</div>
