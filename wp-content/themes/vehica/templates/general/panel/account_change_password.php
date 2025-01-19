<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;
?>
<div class="vehica-container">
    <?php get_template_part('templates/general/panel/account_menu'); ?>

    <vehica-panel-change-password
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_save_account_password')); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_save_account_password')); ?>"
            in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
            success-string="<?php echo esc_attr(vehicaApp('success_string')); ?>"
            error-string="<?php echo esc_attr(vehicaApp('error_string')); ?>"
            confirm-string="<?php echo esc_attr(vehicaApp('ok_string')); ?>"
    >
        <div slot-scope="props">
            <div class="vehica-panel-account">
                <div class="vehica-panel-account__inner">
                    <h3 class="vehica-panel-account__title">
                        <?php echo esc_html(vehicaApp('change_password_string')); ?>
                    </h3>

                    <div class="vehica-panel-account__change-password">
                        <form @submit.prevent="props.onChange">
                            <div class="vehica-grid">
                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label class="vehica-panel-account-field__label" for="vehica-old-password">
                                            <?php echo esc_html(vehicaApp('old_password_string')); ?>
                                        </label>
                                        <input
                                                id="vehica-old-password"
                                                class="vehica-panel-account-field__text-control"
                                                type="password"
                                                @input="props.setOldPassword($event.target.value)"
                                                :value="props.oldPassword"
                                                required
                                        >
                                    </div>
                                </div>
                                <div class="vehica-panel-account__change-password__space"></div>
                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label class="vehica-panel-account-field__label" for="vehica-new-password">
                                            <?php echo esc_html(vehicaApp('new_password_string')); ?>
                                        </label>
                                        <input
                                                id="vehica-new-password"
                                                class="vehica-panel-account-field__text-control"
                                                type="password"
                                                @input="props.setNewPassword($event.target.value)"
                                                :value="props.newPassword"
                                                required
                                        >
                                    </div>
                                </div>
                            </div>

                            <div class="vehica-panel-account__button-save">
                                <button
                                        class="vehica-button vehica-button--with-progress-animation"
                                        :class="{'vehica-button--with-progress-animation--active': props.inProgress}"
                                        :disabled="props.inProgress"
                                >
                                    <span><?php echo esc_html(vehicaApp('save_string')); ?></span>

                                    <template>
                                        <svg
                                                v-if="props.inProgress"
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </vehica-panel-change-password>
</div>