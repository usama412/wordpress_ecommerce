<?php
/* @var \Vehica\Widgets\Car\Single\UserDisplayAddressSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\User\User $vehicaUser */
$vehicaUser = $vehicaCurrentWidget->getUser();
if (!$vehicaUser) {
    return;
}

$vehicaUserAddress = $vehicaUser->getUserAddress();
if (empty($vehicaUserAddress)) {
    return;
}
?>
<div class="vehica-user-address">
    <?php if ($vehicaCurrentWidget->showIcon()) : ?>
        <i class="fas fa-map-marker-alt"></i>
    <?php endif; ?>
    <?php echo esc_html($vehicaUserAddress); ?>
</div>
