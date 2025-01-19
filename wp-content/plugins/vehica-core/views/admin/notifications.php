<div class="vehica-panel vehica-app--styles vehica-app">
    <vehica-hide-when-loaded>
        <div slot-scope="hideWhenLoadedProps" class="vehica-panel__inner">

            <?php require 'partials/menu.php'; ?>

            <div class="vehica-panel__content">
                <div class="vehica-panel__content__inner">

                    <div v-if="hideWhenLoadedProps.show">
                        <div class="vehica-loading"></div>
                    </div>

                    <template>
                        <div class="vehica-title">
                            <?php esc_html_e('Email Notifications', 'vehica-core'); ?>
                        </div>

                        <div>
                            <a
                                    class="vehica-doc-link vehica-doc-link--full-width"
                                    target="_blank"
                                    href="https://support.vehica.com/support/solutions/articles/101000377048">
                                <i class="fas fa-info-circle"></i>
                                <span><?php esc_html_e('Click here if you are not receiving emails or emails goes to spam', 'vehica-core'); ?></span>
                            </a>
                        </div>

                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_save_notifications')); ?>"
                                method="post"
                        >
                            <div class="vehica-section">
                                <div class="vehica-section__left">
                                    <div class="vehica-section__left__inner">
                                        <h2 id="public-information"><?php esc_html_e('Basic', 'vehica-core'); ?></h2>
                                    </div>
                                </div>

                                <div class="vehica-section__right">
                                    <div class="vehica-section__right__inner">
                                        <div class="vehica-field">
                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_CONFIRMATION); ?>"
                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_CONFIRMATION); ?>"
                                                    type="checkbox"
                                                    value="1"
                                                <?php if (vehicaApp('is_user_confirmation_enabled')) : ?>
                                                    checked
                                                <?php endif; ?>
                                            >

                                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_CONFIRMATION); ?>">
                                                <?php esc_html_e('Require new users to verify account via email', 'vehica-core'); ?>
                                            </label>
                                        </div>

                                        <div class="vehica-notification-single__email-sendere">
                                            <label
                                                    class="vehica-notification-single__label"
                                                    for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_NAME); ?>"
                                                    style="font-weight: 700; margin-top: 20px; margin-bottom: 10px;"
                                            >
                                                <?php esc_html_e('Sender Name', 'vehica-core'); ?>
                                            </label>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_NAME); ?>"
                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_NAME); ?>"
                                                    type="text"
                                                    placeholder="<?php esc_attr_e('Type name here', 'vehica-core'); ?>"
                                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getSenderName()); ?>"
                                            >
                                        </div>

                                        <div class="vehica-notification-single__email-sender">
                                            <label
                                                    class="vehica-notification-single__label"
                                                    for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_MAIL); ?>"
                                                    style="font-weight: 700; margin-top: 20px; margin-bottom: 10px;"
                                            >
                                                <?php esc_html_e('Sender email', 'vehica-core'); ?>
                                            </label>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_MAIL); ?>"
                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SENDER_MAIL); ?>"
                                                    type="text"
                                                    placeholder="<?php esc_attr_e('name@domain.com', 'vehica-core'); ?>"
                                                    value="<?php echo esc_attr(vehicaApp('settings_config')->getSenderMail()); ?>"
                                            >
                                        </div>

                                        <div>
                                            <button class="vehica-button">
                                                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php foreach (vehicaApp('notifications') as $vehicaNotification) :
                                /* @var \Vehica\Core\Notification $vehicaNotification */
                                ?>

                                <div class="vehica-section">
                                    <div class="vehica-section__left">
                                        <div class="vehica-section__left__inner">
                                            <h2 id="public-information"><?php echo esc_html($vehicaNotification->label); ?></h2>

                                            <?php if (!empty($vehicaNotification->description)) : ?>
                                                <div>
                                                    <?php echo esc_html($vehicaNotification->description); ?>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (!empty($vehicaNotification->vars)) : ?>
                                                <div>
                                                    <div style="font-weight:700;margin-top:20px; margin-bottom:10px;"><?php esc_html_e('Available variables:', 'vehica-core'); ?></div>
                                                    <div>
                                                        <?php foreach ($vehicaNotification->vars as $vehicaVariable) : ?>
                                                            <div>
                                                                {<?php echo esc_html($vehicaVariable); ?>}
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="vehica-section__right">
                                        <div class="vehica-section__right__inner">

                                            <?php if ($vehicaNotification->optional) : ?>
                                                <div class="vehica-field">
                                                    <input
                                                            type="checkbox"
                                                            value="1"
                                                            name="notifications[<?php echo esc_attr($vehicaNotification->key); ?>][enabled]"
                                                        <?php if ($vehicaNotification->isEnabled()) : ?>
                                                            checked
                                                        <?php endif; ?>
                                                    >
                                                    <label>
                                                        <?php esc_html_e('Enable', 'vehica-core'); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>


                                            <div class="vehica-notification-single__email-title">
                                                <h3 class="vehica-notification-single__label">
                                                    <?php esc_html_e('Email Title', 'vehica-core'); ?>
                                                </h3>

                                                <input
                                                        name="notifications[<?php echo esc_attr($vehicaNotification->key); ?>][title]"
                                                        value="<?php echo esc_attr($vehicaNotification->title); ?>"
                                                        type="text"
                                                >
                                            </div>

                                            <div class="vehica-notification-single__email-message">
                                                <h3 class="vehica-notification-single__label">
                                                    <?php esc_html_e('Message', 'vehica-core'); ?>
                                                </h3>
                                                <textarea
                                                        name="notifications[<?php echo esc_attr($vehicaNotification->key); ?>][message]"
                                                ><?php echo wp_kses_post($vehicaNotification->message); ?></textarea>
                                            </div>
                                            <div>
                                                <button class="vehica-button">
                                                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </vehica-hide-when-loaded>
</div>