<?php
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;
if (!$vehicaCar) {
    return;
}
?>
<div class="vehica-car-views">
    <?php echo esc_html($vehicaCar->getViews()); ?>
</div>
