<?php
/* @var \Vehica\Widgets\Car\Single\BigFeaturesSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar) {
    return;
}

$vehicaFeatures = $vehicaCurrentWidget->getFeatures($vehicaCar);
if ($vehicaFeatures->isEmpty()) {
    return;
}
?>
<div class="vehica-app">
    <vehica-show>
        <div slot-scope="props" class="vehica-car-features-pills">
            <?php if ($vehicaCurrentWidget->isInitialLimitEnabled() && $vehicaCurrentWidget->getInitialLimit() < $vehicaFeatures->count()) : ?>
                <div v-if="!props.show">
                    <div class="vehica-car-features-pills">
                        <?php foreach ($vehicaFeatures->take($vehicaCurrentWidget->getInitialLimit()) as $vehicaFeature) : ?>
                            <div class="vehica-car-features-pills__single">
                                <?php if ($vehicaCurrentWidget->showElement('icon')) : ?>
                                    <i class="far fa-check-circle"></i>
                                <?php endif; ?>

                                <span><?php echo esc_html($vehicaFeature); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="vehica-color-text-primary">
                        <button class="vehica-show-more" @click.prevent="props.onClick">
                            <?php echo esc_html(vehicaApp('show_more_string')); ?>
                        </button>
                    </div>
                </div>

                <template>
                    <div v-if="props.show" class="vehica-car-features-pills">
                        <?php foreach ($vehicaFeatures as $vehicaFeature) : ?>
                            <div class="vehica-car-features-pills__single">
                                <?php if ($vehicaCurrentWidget->showElement('icon')) : ?>
                                    <i class="far fa-check-circle"></i>
                                <?php endif; ?>

                                <span><?php echo esc_html($vehicaFeature); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </template>
            <?php else : ?>
                <?php foreach ($vehicaFeatures as $vehicaFeature) : ?>
                    <div class="vehica-car-features-pills__single">
                        <?php if ($vehicaCurrentWidget->showElement('icon')) : ?>
                            <i class="far fa-check-circle"></i>
                        <?php endif; ?>

                        <span><?php echo esc_html($vehicaFeature); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </vehica-show>
</div>