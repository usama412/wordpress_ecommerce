<?php
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaUser;
if (!$vehicaUser) {
    return;
}

$vehicaUserAddress = $vehicaUser->getUserAddress();
if (empty($vehicaUserAddress)) {
    return;
}
?>
<div class="vehica-user-address">
    <i class="fas fa-map-marker-alt"></i>
    <span><?php echo esc_html($vehicaUserAddress); ?></span>
</div>
