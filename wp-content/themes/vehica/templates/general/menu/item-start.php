<?php
/* @var \Vehica\Components\Menu\MenuElement $vehicaMenuElement */
global $vehicaMenuElement;
?>
<div
        id="vehica-menu-element-<?php echo esc_attr($vehicaMenuElement->getElementId()); ?>"
        class="<?php echo esc_attr($vehicaMenuElement->getClass()); ?>"
>
    <a
            href="<?php echo esc_url($vehicaMenuElement->getLink()); ?>"
            title="<?php echo esc_attr($vehicaMenuElement->getName()); ?>"
            class="vehica-menu__link"
        <?php if ($vehicaMenuElement->isTargetBlank()) : ?>
            target="_blank"
        <?php endif; ?>
    >
        <?php echo esc_html($vehicaMenuElement->getName()); ?>
    </a>
