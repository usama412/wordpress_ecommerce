<div class="vehica-app">
    <template>
        <vehica-login-modal>
            <div slot-scope="loginModal" v-if="loginModal.show">
                <div class="vehica-user-popup">
                    <div class="vehica-user-popup__inner">
                        <div class="vehica-user-popup__position">
                            <div class="vehica-user-popup__close">
                                <div class="vehica-popup-checkbox__close">
                                    <div
                                            class="vehica-close-animated"
                                            @click.prevent="loginModal.hide"
                                    >
                                        <div class="vehica-close-animated__leftright"></div>
                                        <div class="vehica-close-animated__rightleft"></div>
                                    </div>
                                </div>
                            </div>

                            <div
                                <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()): ?>
                                    class="vehica-panel"
                                <?php else : ?>
                                    class="vehica-panel vehica-register-closed"
                                <?php endif; ?>
                            >

                                <vehica-login-tabs>
                                    <div slot-scope="props">
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
                                            <div class="vehica-login"
                                                 :class="{'vehica-active': props.currentTab === 'login'}">
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

                                                <div
                                                        class="vehica-register"
                                                        :class="{'vehica-active': props.currentTab === 'register'}"
                                                >
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </vehica-login-modal>
    </template>
</div>