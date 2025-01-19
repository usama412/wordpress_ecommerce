<?php
/* @var \Vehica\Model\Post\Car $vehicaCar */

global $vehicaCar;
?>
<div class="vehica-panel-card">
    <a
        class="vehica-panel-card__image"
        href="<?php echo esc_url($vehicaCar->getUrl()); ?>"
        title="<?php echo esc_html($vehicaCar->getName()); ?>"
        target="_blank"
    >
        <?php if ($vehicaCar->hasImageUrl()) : ?>
            <img
                src="<?php echo esc_url($vehicaCar->getImageUrl()); ?>"
                alt="<?php echo esc_attr($vehicaCar->getName()); ?>"
            >
        <?php else: ?>
            <div class="vehica-panel-card__image-no-photo"></div>
        <?php endif; ?>
    </a>
    <div class="vehica-panel-card__details">
        <h3 class="vehica-panel-card__title">
            <a
                href="<?php echo esc_url($vehicaCar->getUrl()); ?>"
                title="<?php echo esc_attr($vehicaCar->getName()); ?>"
                target="_blank"
            >
                <?php echo esc_html($vehicaCar->getName()); ?>
            </a>
        </h3>
    </div>
</div>
