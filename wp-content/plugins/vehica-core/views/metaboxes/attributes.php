<?php
if (!defined('ABSPATH')) {
    exit;
}

/* @var \Vehica\Model\Post\Car $vehicaPost */
if ($vehicaPost instanceof \Vehica\Model\Post\Car) {
    $vehicaFieldsManager = vehicaApp('car_config');
    $vehicaFieldsUser = $vehicaPost;
}
?>
<div class="vehica-app container vehica-v-hide">
    <?php wp_nonce_field($vehicaPost->getEditNonce(), $vehicaPost->getEditNonce()); ?>
    <?php require vehicaApp('views_path') . 'forms/attributes.php'; ?>
</div>
