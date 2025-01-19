<?php
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaUser;
if (!$vehicaUser) {
    return;
}
?>
<div class="vehica-user-role">
    <?php echo esc_html($vehicaUser->getTitle()); ?>
</div>
