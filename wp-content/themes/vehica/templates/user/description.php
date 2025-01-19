<?php
global $vehicaUser;

if (!$vehicaUser || !$vehicaUser->hasDescription()) {
    return;
}
?>
<div class="vehica-user-description">
    <?php echo wp_kses_post($vehicaUser->getDescription()); ?>
</div>
