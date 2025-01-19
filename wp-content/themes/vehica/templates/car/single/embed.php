<?php
/* @var \Vehica\Widgets\Car\Single\EmbedSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */


global $vehicaCurrentWidget, $vehicaCar;
if (!$vehicaCar) {
    return;
}

$vehicaEmbedField = $vehicaCurrentWidget->getEmbedField();
if (!$vehicaEmbedField) {
    return;
}

$vehicaEmbedCode = $vehicaEmbedField->getEmbedCode($vehicaCar);
if (empty($vehicaEmbedCode)) {
    return;
}
?>
<div class="vehica-car-embed-wrapper">
    <?php if ($vehicaCurrentWidget->showTitle()) : ?>
        <h3 class="vehica-section-label"><?php echo esc_html($vehicaEmbedField->getName()); ?></h3>
    <?php endif; ?>

    <div class="vehica-car-embed">
        <div class="vehica-car-embed__inner">
            <?php echo vehica_embed($vehicaEmbedCode); ?>
        </div>
    </div>
</div>
