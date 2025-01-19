<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;
$vehicaUser = vehicaApp('current_user');
if (!$vehicaUser instanceof \Vehica\Model\User\User) {
    return;
}
?>
<div class="vehica-container">
    <?php get_template_part('templates/general/panel/account_menu'); ?>

    <vehica-panel-change-social
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_save_account_social')); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_save_account_social')); ?>"
            in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
            success-string="<?php echo esc_attr(vehicaApp('success_string')); ?>"
            error-string="<?php echo esc_attr(vehicaApp('error_string')); ?>"
            initial-facebook="<?php echo esc_attr($vehicaUser->getFacebookProfile()); ?>"
            initial-instagram="<?php echo esc_attr($vehicaUser->getInstagramProfile()); ?>"
            initial-twitter="<?php echo esc_attr($vehicaUser->getTwitterProfile()); ?>"
            initial-linkedin="<?php echo esc_attr($vehicaUser->getLinkedinProfile()); ?>"
            initial-tiktok="<?php echo esc_attr($vehicaUser->getTiktokProfile()); ?>"
            initial-telegram="<?php echo esc_attr($vehicaUser->getTelegramProfile()); ?>"
    >
        <div slot-scope="props">
            <div class="vehica-panel-account">
                <div class="vehica-panel-account__inner">
                    <h3 class="vehica-panel-account__title">
                        <?php echo esc_html(vehicaApp('change_social_string')); ?>
                    </h3>

                    <div class="vehica-panel-account__social">
                        <form @submit.prevent="props.onChange">
                            <div class="vehica-grid">
                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::FACEBOOK_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('Facebook', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__facebook">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::FACEBOOK_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setFacebook($event.target.value)"
                                                    :value="props.facebook"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::INSTAGRAM_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('Instagram', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__instagram">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::INSTAGRAM_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setInstagram($event.target.value)"
                                                    :value="props.instagram"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::TWITTER_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('Twitter', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__twitter">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::TWITTER_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setTwitter($event.target.value)"
                                                    :value="props.twitter"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::LINKEDIN_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('LinkedIn', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__linkedin">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M100.3 448H7.4V148.9h92.9zM53.8 108.1C24.1 108.1 0 83.5 0 53.8a53.8 53.8 0 0 1 107.6 0c0 29.7-24.1 54.3-53.8 54.3zM447.9 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::LINKEDIN_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setLinkedin($event.target.value)"
                                                    :value="props.linkedin"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::TIKTOK_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('TikTok', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__linkedin">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::TIKTOK_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setTiktok($event.target.value)"
                                                    :value="props.tiktok"
                                            >
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of3">
                                    <div class="vehica-panel-account-field">
                                        <label
                                                for="<?php echo esc_attr(\Vehica\Model\User\User::TIKTOK_PROFILE); ?>"
                                                class="vehica-panel-account-field__label"
                                        >
                                            <?php esc_html_e('Telegram', 'vehica'); ?>
                                        </label>

                                        <div class="vehica-panel-account-field__linkedin">
                                            <div class="vehica-panel-account-field__icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                                                    <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                                    <path d="M248 8C111 8 0 119 0 256S111 504 248 504 496 393 496 256 385 8 248 8zM363 176.7c-3.7 39.2-19.9 134.4-28.1 178.3-3.5 18.6-10.3 24.8-16.9 25.4-14.4 1.3-25.3-9.5-39.3-18.7-21.8-14.3-34.2-23.2-55.3-37.2-24.5-16.1-8.6-25 5.3-39.5 3.7-3.8 67.1-61.5 68.3-66.7 .2-.7 .3-3.1-1.2-4.4s-3.6-.8-5.1-.5q-3.3 .7-104.6 69.1-14.8 10.2-26.9 9.9c-8.9-.2-25.9-5-38.6-9.1-15.5-5-27.9-7.7-26.8-16.3q.8-6.7 18.5-13.7 108.4-47.2 144.6-62.3c68.9-28.6 83.2-33.6 92.5-33.8 2.1 0 6.6 .5 9.6 2.9a10.5 10.5 0 0 1 3.5 6.7A43.8 43.8 0 0 1 363 176.7z"/>
                                                </svg>
                                            </div>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::TIKTOK_PROFILE); ?>"
                                                    class="vehica-panel-account-field__text-control vehica-panel-account-field__text-control--social"
                                                    type="text"
                                                    @input="props.setTelegram($event.target.value)"
                                                    :value="props.telegram"
                                            >
                                        </div>
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
    </vehica-panel-change-social>
</div>