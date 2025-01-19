<?php
/* @var \Vehica\Widgets\General\MenuGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-menu__desktop">
    <div class="vehica-menu__wrapper">
        <div class="vehica-menu__left">
            <?php if ($vehicaCurrentWidget->hasLogo()) : ?>
                <div class="vehica-logo">
                    <a
                            href="<?php echo esc_url(get_home_url()); ?>"
                            title="<?php echo esc_attr(get_bloginfo('name')); ?>"
                    >
                        <img
                                src="<?php echo esc_url($vehicaCurrentWidget->getLogoUrl()); ?>"
                                alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        >
                    </a>
                </div>

                <div class="vehica-logo vehica-logo--sticky">
                    <a
                            href="<?php echo esc_url(get_home_url()); ?>"
                            title="<?php echo esc_attr(get_bloginfo('name')); ?>"
                    >
                        <img
                                src="<?php echo esc_url($vehicaCurrentWidget->getStickyLogoUrl()); ?>"
                                alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        >
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($vehicaCurrentWidget->hasMenu()) : ?>
                <div class="vehica-menu__container">
                    <div class="vehica-menu-hover"></div>
                    <?php $vehicaCurrentWidget->displayMenu(); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="vehica-menu__sticky-submit">
            <?php if ($vehicaCurrentWidget->showAccountDetails() && vehicaApp('show_menu_account')) : ?>
                <div class="vehica-top-bar__element vehica-top-bar__element--panel">
                    <div class="vehica-menu-desktop-login-register-link">
                        <?php if (is_user_logged_in() && vehicaApp('settings_config')->isMessageSystemEnabled()) : ?>
                            <template>
                                <vehica-message-count>
                                    <div
                                            slot-scope="props"
                                            v-if="props.count > 0"
                                            class="vehica-menu-item-depth-0"
                                            :class="{'vehica-bell--active': props.count > 0}"
                                    >
                                        <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getMessagesPageUrl()); ?>">
                                        <span class="vehica-menu-desktop-login-register-link__user-icon"
                                              style="color:#fff;">
                                            <span class="vehica-menu-item-depth-0"><i class="far fa-bell"></i></span>
                                            <span v-if="false"><?php echo esc_html(vehicaApp('current_user')->getNotSeenConversationNumber()); ?></span>
                                        </span>
                                        </a>
                                    </div>
                                </vehica-message-count>
                            </template>
                        <?php endif; ?>

                        <?php if (is_user_logged_in()) : ?>
                            <div class="vehica-menu-item-depth-0">
                                <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>">
                                   <span class="vehica-menu-desktop-login-register-link__user-icon">
                                       <span class="vehica-menu-item-depth-0"><i class="far fa-user"></i></span>
                                   </span>
                                    <?php echo esc_html(vehicaApp('dashboard_string')); ?>
                                </a>
                            </div>

                            <div class="vehica-desktop-user-menu">
                                <div class="vehica-desktop-user-menu__inner">
                                    <div class="vehica-desktop-user-menu__top">
                                        <?php if (vehicaApp('current_user')->hasImageUrl('vehica_100_100')) : ?>
                                            <div class="vehica-desktop-user-menu__top__avatar">
                                                <img src="<?php echo esc_url(vehicaApp('current_user')->getImageUrl('vehica_100_100')); ?>">
                                            </div>
                                        <?php endif; ?>

                                        <div class="vehica-desktop-user-menu__top__info">
                                            <div class="vehica-desktop-user-menu__name">
                                                <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>">
                                                    <?php echo esc_html(vehicaApp('current_user')->getName()); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vehica-desktop-user-menu__menu-links">
                                        <?php if (vehicaApp('current_user')->canCreateCars()) : ?>
                                            <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCreateCarPageUrl()); ?>">
                                                <i class="fas fa-plus"></i><?php echo esc_html(vehicaApp('submit_vehicle_string')); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (vehicaApp('current_user')->canCreateCars()) : ?>
                                            <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>">
                                                <i class="fas fa-list"></i><?php echo esc_html(vehicaApp('vehicles_string')); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (vehicaApp('settings_config')->isMessageSystemEnabled()) : ?>
                                            <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getMessagesPageUrl()); ?>">
                                                <i class="fas fa-comments"></i><?php echo esc_html(vehicaApp('messages_string')); ?>
                                                <template>
                                                    <vehica-message-count>
                                                        <span
                                                                slot-scope="props"
                                                                v-if="props.count > 0"
                                                                class="vehica-desktop-user-menu__menu-links__count"
                                                        >
                                                            <span>{{ props.count }}</span>
                                                        </span>
                                                    </vehica-message-count>
                                                </template>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (vehicaApp('show_favorite')) : ?>
                                            <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getFavoritePageUrl()); ?>">
                                                <i class="fas fa-star"></i><?php echo esc_html(vehicaApp('favorite_string')); ?>
                                                <span class="vehica-desktop-user-menu__menu-links__count">
                                                    <span v-if="false"><?php echo esc_html(vehicaApp('current_user')->getFavoriteNumber()); ?></span>

                                                    <template>
                                                        <vehica-favorite-number
                                                                :initial-number="<?php echo esc_attr(vehicaApp('current_user')->getFavoriteNumber()); ?>"
                                                        >
                                                            <span slot-scope="props">{{ props.number }}</span>
                                                        </vehica-favorite-number>
                                                    </template>
                                                </span>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>">
                                            <i class="fas fa-cog"></i><?php echo esc_html(vehicaApp('settings_string')); ?>
                                        </a>

                                        <a href="<?php echo esc_url(\Vehica\Model\User\User::getLogoutUrl()); ?>">
                                            <i class="fas fa-sign-out-alt"></i><?php echo esc_html(vehicaApp('sign_out_string')); ?>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        <?php else : ?>
                            <?php if (vehicaApp('settings_config')->hasLoginPage()) : ?>
                                <span class="vehica-menu-desktop-login-register-link__user-icon">
                                    <i class="far fa-user"></i>
                                </span>

                                <div class="vehica-menu-item-depth-0">
                                    <a href="<?php echo esc_url(vehicaApp('settings_config')->getLoginPageUrl()); ?>">
                                        <span class="vehica-menu-desktop-login-register-link__login-text vehica-menu-item-depth-0">
                                            <?php echo esc_html(vehicaApp('log_in_string')); ?>
                                        </span>
                                    </a>
                                </div>

                                <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()) : ?>
                                    <span class="vehica-menu-desktop-login-register-link__separator"></span>

                                    <div class="vehica-menu-item-depth-0">
                                        <a href="<?php echo esc_url(vehicaApp('settings_config')->getRegisterPageUrl()); ?>">
                                            <span class="vehica-menu-desktop-login-register-link__register-text vehica-menu-item-depth-0">
                                                <?php echo esc_html(vehicaApp('register_string')); ?>
                                            </span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($vehicaCurrentWidget->showSubmitButton()) : ?>
                <a
                        class="vehica-button vehica-button--menu-submit"
                        href="<?php echo esc_url($vehicaCurrentWidget->getSubmitButtonUrl()); ?>"
                >
                        <span class="vehica-menu-item-depth-0">
                            <?php if ($vehicaCurrentWidget->hasButtonCustomIcon()) : ?>
                                <i class="<?php echo esc_attr($vehicaCurrentWidget->getButtonCustomIcon()); ?>"></i>
                            <?php else : ?>
                                <i class="fas fa-plus"></i>
                            <?php endif; ?>

                            <?php if (!empty(vehicaApp('settings_config')->getCustomCtaButtonText())) : ?>
                                <span><?php echo esc_html(vehicaApp('settings_config')->getCustomCtaButtonText()); ?></span>
                            <?php else : ?>
                                <span><?php echo esc_html(vehicaApp('submit_vehicle_string')); ?></span>
                            <?php endif; ?>
                        </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
