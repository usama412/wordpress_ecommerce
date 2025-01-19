<?php
/* @var \Vehica\Widgets\General\LoginGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<vehica-set-password
        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_set_password')); ?>"
        vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_set_password')); ?>"
        selector="<?php echo esc_attr($vehicaCurrentWidget->getSelector()); ?>"
        validator="<?php echo esc_attr($vehicaCurrentWidget->getValidator()); ?>"
        redirect-url="<?php echo esc_url(vehicaApp('login_page_url')); ?>"
>
    <div slot-scope="setPassword">
        <form @submit.prevent="setPassword.onSetPassword">

            <!--            <template v-if="setPassword.inProgress">-->
            <!--                In progress-->
            <!--            </template>-->

            <template>
                <div v-if="setPassword.message && setPassword.message !== ''">
                    {{ setPassword.message }}
                </div>
            </template>

            <div class="vehica-panel">
                <div class="vehica-reset-password">
                    <div class="vehica-reset-password__inner">
                        <h1 class="vehica-reset-password__title">
                            <?php echo esc_html(vehicaApp('set_new_password_string')); ?>
                        </h1>
                        <div class="vehica-set-password">
                            <input
                                    id="<?php echo esc_attr(\Vehica\Managers\LoginManager::PASSWORD); ?>"
                                    name="<?php echo esc_attr(\Vehica\Managers\LoginManager::PASSWORD); ?>"
                                    type="password"
                                    :value="setPassword.password"
                                    @input="setPassword.setPassword($event.target.value)"
                            >
                            <template>
                                <div v-if="setPassword.showErrors" class="vehica-field--validation-required__tip">
                                    <div v-if="!setPassword.errors.password.required"
                                         class="vehica-field--validation-required__tip__text">
                                        <?php echo esc_html(vehicaApp('field_is_required_string')); ?>
                                    </div>
                                    <div v-if="!setPassword.errors.password.minLength"
                                         class="vehica-field--validation-required__tip__text">
                                        <?php echo esc_html(vehicaApp('password_min_letters_string')); ?>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button class="vehica-button">
                            <?php echo esc_html(vehicaApp('set_password_string')); ?>
                        </button>
                    </div>
                </div>
            </div>


        </form>
    </div>
</vehica-set-password>