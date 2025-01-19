<?php
/* @var \Vehica\Widgets\General\FeaturesGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-heading">
    <?php if ($vehicaCurrentWidget->hasIcon()) : ?>
        <div class="vehica-heading__icon">
            <i class="<?php echo esc_attr($vehicaCurrentWidget->getIcon()); ?>"></i>
        </div>
    <?php endif; ?>

    <h3 class="vehica-heading__title">
        <?php echo esc_html($vehicaCurrentWidget->getHeading()); ?>
    </h3>

    <?php if ($vehicaCurrentWidget->hasSubheading()) : ?>
        <div class="vehica-heading__text">
            <?php echo wp_kses_post($vehicaCurrentWidget->getSubheading()); ?>
        </div>
    <?php endif; ?>
</div>

<div class="vehica-features">
    <?php foreach ($vehicaCurrentWidget->getFeatures() as $vehicaFeature) : ?>
        <div class="vehica-features__feature">
            <div class="vehica-features__icon">
                <i class="<?php echo esc_attr($vehicaFeature['icon']['value']); ?>"></i>
            </div>

            <div class="vehica-features__content">
                <div class="vehica-features__label">
                    <?php echo esc_html($vehicaFeature['label']); ?>
                </div>

                <div class="vehica-features__text">
                    <?php echo wp_kses_post($vehicaFeature['text']); ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>