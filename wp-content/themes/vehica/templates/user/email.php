<?php
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaUser;

if (!$vehicaUser) {
    return;
}
?>
<a class="vehica-user-email" href="mailto:<?php echo esc_attr($vehicaUser->getMail()); ?>">
    <i class="fas fa-envelope"></i>
    <span><?php echo esc_html($vehicaUser->getMail()); ?></span>
</a>
