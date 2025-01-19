<?php
/* @var \Vehica\Widgets\General\LoginGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div
    <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()): ?>
        class="vehica-app vehica-panel"
    <?php else : ?>
        class="vehica-app vehica-panel vehica-register-closed"
    <?php endif; ?>
>
    <?php if ($vehicaCurrentWidget->isLoginPage()) : ?>
        <vehica-login-tabs>
            <div slot-scope="props" class="vehica-login-register-wide-container">

                <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()): ?>
                    <div class="vehica-login-register-tabs-wrapper">
                        <div class="vehica-login-register-tabs">
                            <div
                                    @click="props.setTab('login')"
                                    class="vehica-login-register-tabs__login"
                                    :class="{'vehica-active': props.currentTab === 'login'}"
                            >
                                <?php echo esc_html(vehicaApp('login_string')); ?>
                            </div>

                            <div
                                    @click="props.setTab('register')"
                                    class="vehica-login-register-tabs__register"
                                    :class="{'vehica-active': props.currentTab === 'register'}"
                            >
                                <?php echo esc_html(vehicaApp('register_string')); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="vehica-panel-login-register">
                    <div class="vehica-login" :class="{'vehica-active': props.currentTab === 'login'}">
                        <div class="vehica-login__inner">
                            <h2><?php echo esc_html(vehicaApp('login_in_your_account_string')); ?></h2>

                            <h3><?php echo esc_html(vehicaApp('welcome_back_string')); ?></h3>

                            <?php get_template_part('templates/general/login/login_form'); ?>
                        </div>
                    </div>

                    <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()): ?>
                        <?php if (!vehicaApp('show_social_auth')) : ?>
                            <div class="vehica-panel-login-register__or">
                                <?php echo esc_html(vehicaApp('or_string')); ?>
                            </div>
                        <?php endif; ?>

                        <div class="vehica-register" :class="{'vehica-active': props.currentTab === 'register'}">
                            <div class="vehica-register__inner">
                                <h2><?php echo esc_html(vehicaApp('register_string')); ?></h2>

                                <h3><?php echo esc_html(vehicaApp('create_new_account_today_string')); ?></h3>

                                <?php get_template_part('templates/general/login/register_form'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </vehica-login-tabs>
    <?php elseif ($vehicaCurrentWidget->isSendConfirmationMailPage()) : ?>
        <?php get_template_part('templates/general/login/send_confirmation'); ?>
    <?php elseif ($vehicaCurrentWidget->isResetPasswordPage()) : ?>
        <?php get_template_part('templates/general/login/reset_password'); ?>
    <?php elseif ($vehicaCurrentWidget->isSetPasswordPage()) : ?>
        <?php get_template_part('templates/general/login/set_password'); ?>
    <?php endif; ?>
</div>