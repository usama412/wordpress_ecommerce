<?php
/* @var \Vehica\Widgets\Car\Single\UserRoleSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\User\User $vehicaUser */
$vehicaUser = $vehicaCurrentWidget->getUser();
if (!$vehicaUser) {
    return;
}
?>
<div class="vehica-user-role">
    <?php echo esc_html($vehicaUser->getTitle()); ?>
</div>
