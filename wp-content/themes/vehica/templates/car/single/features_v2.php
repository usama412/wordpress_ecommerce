<?php
/* @var \Vehica\Widgets\Car\Single\FeaturesV2SingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;
?>
<div class="vehica-car-features-v2">
    <?php foreach ($vehicaCurrentWidget->getFields() as $vehicaField) :
        /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaField */
        $vehicaFeatures = $vehicaField->getSimpleTextValues($vehicaCar);
        if ($vehicaFeatures->isNotEmpty())  :
            ?>
            <div class="vehica-car-features-v2__feature">
                <div class="vehica-car-features-v2__feature__label">
                    <?php echo esc_html($vehicaField->getName()); ?>
                </div>
                <div class="vehica-car-features-v2__feature__value">
                    <?php foreach ($vehicaFeatures as $vehicaFeature) :
                        /* @var \Vehica\Attribute\SimpleTextValue $vehicaFeature */ ?>
                        <?php echo esc_html($vehicaFeature); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

