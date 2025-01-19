<?php
/* @var \Vehica\Widgets\Car\Single\PhoneSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget) {
    return;
}

$vehicaUser = $vehicaCar->getUser();
if (!$vehicaUser || !$vehicaUser->hasPhone() || $vehicaUser->hidePhone()) {
    return;
}
?>
<div class="vehica-user-simple-phone-wrapper">
    <a
            class="vehica-user-simple-phone"
            href="tel:<?php echo esc_attr($vehicaUser->getPhoneUrl()); ?>"
    >
        <?php echo esc_html($vehicaUser->getPhone()); ?>
    </a>
</div>
