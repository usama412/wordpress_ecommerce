<?php
/* @var \Vehica\Widgets\General\ServicesGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaServices = $vehicaCurrentWidget->getServices();
?>
<div class="vehica-services vehica-services__count-<?php echo esc_attr(count($vehicaServices)); ?>">
    <?php foreach ($vehicaCurrentWidget->getServices() as $vehicaService) : ?>
        <div class="vehica-services__service-wrapper">
            <a
                    class="vehica-services__service"
                    href="<?php echo esc_url($vehicaService['link']['url']); ?>"
            >
                <?php if (!empty($vehicaService['image']['url'])) : ?>
                    <img
                            class="vehica-services__image"
                            src="<?php echo esc_url($vehicaService['image']['url']); ?>"
                            alt="<?php echo esc_attr($vehicaService['name']); ?>"
                    >
                <?php else : ?>
                    <div class="vehica-services__image vehica-services__image--placeholder"></div>
                <?php endif; ?>

                <?php if (!empty($vehicaService['name'])) : ?>
                    <h3 class="vehica-services__name">
                        <?php echo esc_html($vehicaService['name']); ?>
                    </h3>
                <?php endif; ?>

                <?php if (!empty($vehicaService['button_label'])) : ?>
                    <button class="vehica-button">
                        <?php echo esc_html($vehicaService['button_label']); ?>
                    </button>
                <?php endif; ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>
