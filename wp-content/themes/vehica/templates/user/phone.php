<?php
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaUser;
if (!$vehicaUser || !$vehicaUser->hasPhone() || $vehicaUser->hidePhone()) {
    return;
}
?>
<a
        class="vehica-user-phone"
        href="tel:<?php echo esc_attr($vehicaUser->getPhoneUrl()); ?>"
>
    <i class="fas fa-phone-alt"></i>
    <span><?php echo esc_html($vehicaUser->getPhone()); ?></span>
</a>