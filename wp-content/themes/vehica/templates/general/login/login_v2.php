<?php
/* @var \Vehica\Widgets\General\LoginV2GeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-app vehica-panel">
    <?php if ($vehicaCurrentWidget->isLoginPage()) : ?>
        <div class="vehica-login">
            <div class="vehica-login__inner">
                <?php get_template_part('templates/general/login/login_form'); ?>
            </div>
        </div>
    <?php elseif ($vehicaCurrentWidget->isSendConfirmationMailPage()) : ?>
        <?php get_template_part('templates/general/login/send_confirmation'); ?>
    <?php elseif ($vehicaCurrentWidget->isResetPasswordPage()) : ?>
        <?php get_template_part('templates/general/login/reset_password'); ?>
    <?php elseif ($vehicaCurrentWidget->isSetPasswordPage()) : ?>
        <?php get_template_part('templates/general/login/set_password'); ?>
    <?php endif; ?>
</div>