<?php
/* @var \Vehica\Widgets\General\PhoneGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if (empty(vehicaApp('phone'))) {
    return;
}
?>
<?php if ($vehicaCurrentWidget->isStyleV1()) : ?>
    <div class="vehica-phone">
        <div class="vehica-phone-v1">
            <a
                    class="vehica-phone-highlight"
                    href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>"
            >
                <?php echo esc_html(vehicaApp('phone')); ?>
            </a>
        </div>
    </div>
<?php elseif ($vehicaCurrentWidget->isStyleV2()) : ?>
    <div class="vehica-phone">
        <div class="vehica-phone-v2">
            <a
                    class="vehica-phone-highlight"
                    href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>"
            >
                <?php echo esc_html(vehicaApp('phone')); ?>
            </a>
        </div>
    </div>
<?php elseif ($vehicaCurrentWidget->isStyleV3()) : ?>
    <div class="vehica-phone">
        <div class="vehica-phone-v3">
            <a href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>">
                <i class="fa fa-phone-alt"></i>
                <?php echo esc_html(vehicaApp('phone')); ?>
            </a>
        </div>
    </div>
<?php elseif ($vehicaCurrentWidget->isStyleV4()) : ?>
    <div class="vehica-phone">
        <div class="vehica-phone-v4">
            <a href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>">
                <?php echo esc_html(vehicaApp('phone')); ?>
            </a>
        </div>
    </div>
<?php endif; ?>