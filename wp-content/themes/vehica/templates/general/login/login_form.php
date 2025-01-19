<vehica-login
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_login')); ?>"
        vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_login')); ?>"
        redirect-url="<?php echo esc_url(\Vehica\Widgets\General\LoginGeneralWidget::getRedirectUrl()); ?>"
        :is-admin="<?php echo esc_attr(is_user_logged_in() && current_user_can('manage_options') ? 'true' : 'false'); ?>"
    <?php if (vehicaApp('recaptcha')) : ?>
        :re-captcha="true"
        re-captcha-key="<?php echo esc_attr(vehicaApp('settings_config')->getRecaptchaSite()); ?>"
    <?php endif; ?>
>
    <div slot-scope="form">
        <form @submit.prevent="form.onLogin">
            <template>
                <div
                        v-if="form.message && form.message !== ''"
                        class="vehica-register-login-notice"
                >
                    {{ form.message }}
                </div>

                <div
                        v-if="form.confirmationRequired"
                        class="vehica-register-login-notice vehica-register-login-notice--resend-email"
                >
                    <div>
                        <?php echo esc_html(vehicaApp('your_account_is_not_confirmed_string')); ?>
                    </div>

                    <div>
                        <?php echo esc_html(vehicaApp('please_check_email_string')); ?>
                    </div>

                    <a href="<?php echo esc_url(\Vehica\Widgets\General\LoginGeneralWidget::getSendConfirmationPageUrl()); ?>">
                        <i class="fas fa-envelope"></i> <?php echo esc_html(vehicaApp('send_confirmation_mail_string')); ?>
                    </a>
                </div>
            </template>

            <?php if (vehicaApp('show_social_auth')) : ?>
                <div class="vehica-social-login-wrapper">
                    <div class="vehica-social-login">
                        <div class="vehica-social-login__label">
                            <?php echo esc_html(vehicaApp('sing_in_with_string')); ?><span>:</span>
                        </div>

                        <?php if (vehicaApp('show_google_auth')) : ?>
                            <a
                                    class="vehica-social-login__button vehica-social-login__button--google"
                                    href="<?php echo esc_url(admin_url('admin-post.php?action=vehica/socialAuth/google')); ?>"
                            >
                                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/g.png'); ?>">
                                <span><?php echo esc_html(vehicaApp('google_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (vehicaApp('show_facebook_auth')) : ?>
                            <a class="vehica-social-login__button vehica-social-login__button--facebook"
                               href="<?php echo esc_url(admin_url('admin-post.php?action=vehica/socialAuth/facebook')); ?>">
                                <i class="fab fa-facebook-f"></i>
                                <span><?php echo esc_html(vehicaApp('facebook_string')); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="vehica-social-login-or"><?php echo esc_html(vehicaApp('or_string')); ?></div>
                </div>
            <?php endif; ?>

            <div class="vehica-fields">
                <div
                        class="vehica-field"
                        :class="{'vehica-field--validation-required': form.showLoginError}"
                >
                    <input
                            id="<?php echo esc_attr(\Vehica\Managers\LoginManager::LOGIN); ?>"
                            name="<?php echo esc_attr(\Vehica\Managers\LoginManager::LOGIN); ?>"
                            placeholder="<?php echo esc_attr(vehicaApp('email_or_login_string')); ?>"
                            @input="form.setLogin($event.target.value)"
                            :value="form.login"
                            type="text"
                    >

                    <template>
                        <div v-if="form.showErrors" class="vehica-field--validation-required__tip">
                            <div v-if="!form.errors.login.required"
                                 class="vehica-field--validation-required__tip__text">
                                <?php echo esc_html(vehicaApp('field_is_required_string')); ?>
                            </div>
                            <div v-if="!form.errors.login.minLength"
                                 class="vehica-field--validation-required__tip__text">
                                <?php echo esc_html(vehicaApp('login_min_letters_string')); ?>
                            </div>
                        </div>
                    </template>
                </div>

                <div
                        class="vehica-field"
                        :class="{'vehica-field--validation-required': form.showPasswordError}"
                >
                    <input
                            id="vehica-login-<?php echo esc_attr(\Vehica\Managers\LoginManager::PASSWORD); ?>"
                            name="<?php echo esc_attr(\Vehica\Managers\LoginManager::PASSWORD); ?>"
                            @input="form.setPassword($event.target.value)"
                            placeholder="<?php echo esc_attr(vehicaApp('password_string')); ?>"
                            :value="form.password"
                            type="password"
                    >

                    <template>
                        <div v-if="form.showErrors" class="vehica-field--validation-required__tip">
                            <div v-if="!form.errors.password.required"
                                 class="vehica-field--validation-required__tip__text">
                                <?php echo esc_html(vehicaApp('field_is_required_string')); ?>
                            </div>

                            <div v-if="!form.errors.password.minLength"
                                 class="vehica-field--validation-required__tip__text">
                                <?php echo esc_html(vehicaApp('password_min_letters_string')); ?>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="vehica-login__below-fields">
                <div class="vehica-login__remember">
                    <div class="vehica-checkbox">
                        <input
                                @change.prevent="form.setRemember"
                                id="<?php echo esc_attr(\Vehica\Managers\LoginManager::REMEMBER); ?>"
                                name="<?php echo esc_attr(\Vehica\Managers\LoginManager::REMEMBER); ?>"
                                type="checkbox"
                                :checked="form.remember"
                        >

                        <label for="<?php echo esc_attr(\Vehica\Managers\LoginManager::REMEMBER); ?>">
                            <?php echo esc_html(vehicaApp('remember_string')); ?>
                        </label>
                    </div>
                </div>

                <div class="vehica-login__forgotten-password">
                    <a href="<?php echo esc_url(\Vehica\Widgets\General\LoginGeneralWidget::getResetPasswordPageUrl()); ?>">
                        <?php echo esc_html(vehicaApp('forgotten_password_string')); ?>
                    </a>
                </div>
            </div>

            <button
                    class="vehica-button vehica-button--login vehica-button--with-progress-animation"
                    :class="{'vehica-button--with-progress-animation--active': form.inProgress}"
                    :disabled="form.inProgress"
            >
                <template>
                    <svg
                            v-if="form.inProgress"
                            width="120"
                            height="30"
                            wviewBox="0 0 120 30"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="#fff"
                    >
                        <circle cx="15" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                     begin="0s" dur="0.8s"
                                     values="15;9;15" calcMode="linear"
                                     repeatCount="indefinite"/>
                            <animate attributeName="fill-opacity" from="1" to="1"
                                     begin="0s" dur="0.8s"
                                     values="1;.5;1" calcMode="linear"
                                     repeatCount="indefinite"/>
                        </circle>
                        <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                            <animate attributeName="r" from="9" to="9"
                                     begin="0s" dur="0.8s"
                                     values="9;15;9" calcMode="linear"
                                     repeatCount="indefinite"/>
                            <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                     begin="0s" dur="0.8s"
                                     values=".5;1;.5" calcMode="linear"
                                     repeatCount="indefinite"/>
                        </circle>
                        <circle cx="105" cy="15" r="15">
                            <animate attributeName="r" from="15" to="15"
                                     begin="0s" dur="0.8s"
                                     values="15;9;15" calcMode="linear"
                                     repeatCount="indefinite"/>
                            <animate attributeName="fill-opacity" from="1" to="1"
                                     begin="0s" dur="0.8s"
                                     values="1;.5;1" calcMode="linear"
                                     repeatCount="indefinite"/>
                        </circle>
                    </svg>
                </template>

                <span><?php echo esc_html(vehicaApp('login_string')); ?></span>
            </button>
        </form>
    </div>
</vehica-login>
