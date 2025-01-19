<?php
/* @var \Vehica\Widgets\Car\Single\NameSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;
if (!$vehicaCar) {
    return;
}

if ($vehicaCar->hasName()) :?>
    <<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?> class="vehica-car-name">
    <?php echo esc_html($vehicaCar->getName()); ?>
    </<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?>>
<?php elseif (\Elementor\Plugin::instance()->editor->is_edit_mode()) : ?>
    <<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?> class="vehica-car-name">
    <?php esc_html_e('Listing Title', 'vehica'); ?>
    </<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?>>
<?php
endif;