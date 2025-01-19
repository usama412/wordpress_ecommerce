<?php
/* @var \Vehica\Widgets\Car\Single\FeaturesSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

if ( ! $vehicaCar || ! $vehicaCurrentWidget) {
    return;
}

$vehicaFeatures = $vehicaCurrentWidget->getFeatures($vehicaCar);
if ($vehicaFeatures->isEmpty()) {
    return;
}
?>
<div class="vehica-car-features">
    <?php foreach ($vehicaFeatures as $vehicaFeature) :/* @var \Vehica\Attribute\SimpleTextValue $vehicaFeature */ ?>
        <?php if ($vehicaFeature->isLink()) : ?>
            <a
                    class="vehica-car-feature"
                    title="<?php echo esc_attr($vehicaFeature); ?>"
                    href="<?php echo esc_url($vehicaFeature->getLink()); ?>"
            >
                <span>
                    <?php echo esc_html($vehicaFeature); ?>
                </span>
                <i class="fas fa-circle"></i>
            </a>
        <?php else : ?>
            <div class="vehica-car-feature">
                <span>
                    <?php echo esc_html($vehicaFeature); ?>
                </span>
                <i class="fas fa-circle"></i>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>