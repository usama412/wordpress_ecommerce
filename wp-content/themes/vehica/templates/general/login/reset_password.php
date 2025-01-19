<?php
/* @var \Vehica\Widgets\General\LoginGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<vehica-send-reset-password-link
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_send_reset_password')); ?>"
        vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_send_reset_password_link')); ?>"
        redirect-url="<?php echo esc_url(vehicaApp('login_page_url')); ?>"
        confirmation-button-text="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
    <?php if (vehicaApp('recaptcha')) : ?>
        :re-captcha="true"
        re-captcha-key="<?php echo esc_attr(vehicaApp('settings_config')->getRecaptchaSite()); ?>"
    <?php endif; ?>
>
    <div slot-scope="resetPassword" class="vehica-reset-password">
        <form @submit.prevent="resetPassword.onSend" class="vehica-reset-password__inner">
            <template>
                <div v-if="resetPassword.message && resetPassword.message !== ''">
                    {{ resetPassword.message }}
                </div>
            </template>

            <div>
                <h1 class="vehica-reset-password__title">
                    <label for="<?php echo esc_attr(\Vehica\Managers\LoginManager::EMAIL); ?>">
                        <?php echo esc_html(vehicaApp('reset_your_password_string')); ?>
                    </label>
                </h1>

                <div class="vehica-reset-password__subtitle">
                    <?php echo esc_html(vehicaApp('if_you_have_forgotten_password_string')); ?>
                </div>

                <div class="vehica-reset-password__form">
                    <div class="vehica-reset-password__field">
                        <input
                                id="<?php echo esc_attr(\Vehica\Managers\LoginManager::EMAIL); ?>"
                                name="<?php echo esc_attr(\Vehica\Managers\LoginManager::EMAIL); ?>"
                                type="text"
                                @input="resetPassword.setMail($event.target.value)"
                                :value="resetPassword.email"
                                placeholder="<?php echo esc_html(vehicaApp('enter_your_email_string')); ?>"
                        >
                    </div>

                    <template>
                        <div v-if="resetPassword.showErrors" class="vehica-field--validation-required__tip">
                            <div v-if="!resetPassword.errors.email.required"
                                 class="vehica-field--validation-required__tip__text">
                                <?php echo esc_html(vehicaApp('field_is_required_string')); ?>
                            </div>
                            <div
                                    v-if="!resetPassword.errors.email.email"
                                    class="vehica-field--validation-required__tip__text"
                            >
                                <?php echo esc_html(vehicaApp('email_invalid_format_string')); ?>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="vehica-reset-password__button">
                <button
                        class="vehica-button vehica-button--with-progress-animation"
                        :class="{'vehica-button--with-progress-animation--active': resetPassword.inProgress}"
                        :disabled="resetPassword.inProgress"
                >
                    <span><?php echo esc_html(vehicaApp('send_string')); ?></span>

                    <template>
                        <svg
                                v-if="resetPassword.inProgress"
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
                </button>
            </div>

            <div class="vehica-reset-password__back">
                <?php echo esc_html(vehicaApp('if_you_do_not_have_string')); ?>
                <a
                        class="vehica-reset-password__back__link"
                        href="<?php echo esc_url(vehicaApp('login_page_url')); ?>"
                >
                    <?php echo esc_html(vehicaApp('register_now_string')); ?>
                </a>
            </div>

        </form>
    </div>
</vehica-send-reset-password-link>