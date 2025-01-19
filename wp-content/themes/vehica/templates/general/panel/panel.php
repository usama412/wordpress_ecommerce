<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;
$vehicaUserImage = $vehicaCurrentWidget->getUserImage('thumbnail');
$vehicaUser = \Vehica\Model\User\User::getCurrent();
?>
<div class="vehica-app vehica-panel">

    <?php if (is_user_logged_in() && !$vehicaCurrentWidget->isBuyPackagePage()) : ?>
        <div class="vehica-panel-menu-desktop">
            <div class="vehica-container">
                <div class="vehica-panel-menu-desktop__inner">
                    <h1 class="vehica-panel-menu-desktop__title">
                        <?php echo esc_html($vehicaCurrentWidget->getPageTitle()); ?>
                    </h1>

                    <div class="vehica-panel-menu-desktop__navbar">
                        <?php if ($vehicaCurrentWidget->showSubmitCarButton()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isCreateCarPage()) : ?>
                                    class="vehica-panel-menu-desktop__button vehica-panel-menu-desktop__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-desktop__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCreateCarPageUrl()); ?>"
                                    title="<?php echo esc_attr(vehicaApp('submit_vehicle_string')); ?>"
                            >
                                <span><?php echo esc_html(vehicaApp('submit_vehicle_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if ($vehicaCurrentWidget->showCarListButton()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isCarListPage()) : ?>
                                    class="vehica-panel-menu-desktop__button vehica-panel-menu-desktop__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-desktop__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>"
                                    title="<?php echo esc_attr(vehicaApp('vehicles_string')); ?>"
                            >
                                <span><?php echo esc_html(vehicaApp('vehicles_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (vehicaApp('settings_config')->isMessageSystemEnabled()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isMessagesPage()) : ?>
                                    class="vehica-panel-menu-desktop__button vehica-panel-menu-desktop__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-desktop__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getMessagesPageUrl()); ?>"
                            >
                                <span><?php echo esc_html(vehicaApp('messages_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (vehicaApp('show_favorite')) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isFavoritePage()) : ?>
                                    class="vehica-panel-menu-desktop__button vehica-panel-menu-desktop__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-desktop__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getFavoritePageUrl()); ?>"
                                    title="<?php echo esc_attr(vehicaApp('favorite_string')); ?>"
                            >
                                <span><?php echo esc_html(vehicaApp('favorite_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <a
                            <?php if ($vehicaCurrentWidget->isAnyAccountPage()) : ?>
                                class="vehica-panel-menu-desktop__button vehica-panel-menu-desktop__button--active"
                            <?php else : ?>
                                class="vehica-panel-menu-desktop__button"
                            <?php endif; ?>
                                href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>"
                                title="<?php echo esc_attr(vehicaApp('account_string')); ?>"
                        >
                            <span><?php echo esc_html(vehicaApp('account_string')); ?></span>
                        </a>

                        <vehica-logout
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_logout')); ?>"
                                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_logout')); ?>"
                                redirect-url="<?php echo esc_url(site_url()); ?>"
                                in-progress-text="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
                                confirm-button-text="<?php echo esc_attr(vehicaApp('back_to_homepage_string')); ?>"
                                success-text="<?php echo esc_attr(vehicaApp('logout_success_string')); ?>"
                                class="vehica-panel-menu-desktop__button"
                        >
                            <span slot-scope="logout" @click.prevent="logout.onClick">
                                <span>
                                    <?php echo esc_html(vehicaApp('sign_out_string')); ?>
                                </span>
                            </span>
                        </vehica-logout>

                        <div class="vehica-panel-menu-desktop__avatar">
                            <a
                                    href="<?php echo esc_url($vehicaCurrentWidget->getUserUrl()); ?>"
                                    title="<?php echo esc_html(vehicaApp('my_profile_string')); ?>"
                                <?php if (!empty($vehicaUserImage)) : ?>
                                    class="vehica-panel-menu-desktop__avatar__image"
                                    style="background-image: url('<?php echo esc_url($vehicaUserImage); ?>');"
                                <?php else : ?>
                                    class="vehica-panel-menu-desktop__avatar__image vehica-panel-menu-desktop__avatar__image--placeholder"
                                <?php endif; ?>
                            >
                            </a>

                            <a
                                    class="vehica-panel-menu-desktop__avatar__icon"
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>"
                                    title="<?php echo esc_html(vehicaApp('set_new_profile_picture_string')); ?>"
                            >
                                <i class="fas fa-camera"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="vehica-panel-menu-mobile">
            <div class="vehica-panel-menu-mobile__inner">
                <div class="vehica-panel-menu-mobile__avatar">
                    <a
                            href="<?php echo esc_url($vehicaCurrentWidget->getUserUrl()); ?>"
                            class="vehica-panel-menu-mobile__avatar__image"
                            title="<?php echo esc_html(vehicaApp('my_profile_string')); ?>"
                        <?php if (!empty($vehicaUserImage)) : ?>
                            class="vehica-panel-menu-mobile___avatar__image"
                            style="background-image: url('<?php echo esc_url($vehicaUserImage); ?>');"
                        <?php else : ?>
                            class="vehica-panel-menu-mobile___avatar__image vehica-panel-menu-mobile___avatar__image--placeholder"
                        <?php endif; ?>
                    >
                    </a>
                    <a
                            class="vehica-panel-menu-mobile__avatar__icon"
                            href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>"
                            title="<?php echo esc_html(vehicaApp('set_new_profile_picture_string')); ?>"
                    >
                        <i class="fas fa-camera"></i>
                    </a>
                </div>

                <div class="vehica-panel-menu-mobile__navbar">
                    <div class="vehica-panel-menu-mobile__navbar__inner">
                        <?php if ($vehicaCurrentWidget->showSubmitCarButton()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isCreateCarPage()) : ?>
                                    class="vehica-panel-menu-mobile__button vehica-panel-menu-mobile__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-mobile__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCreateCarPageUrl()); ?>"
                                    title="<?php echo esc_attr(vehicaApp('submit_vehicle_string')); ?>"
                            >
                                <?php echo esc_html(vehicaApp('submit_vehicle_string')); ?>
                            </a>
                        <?php endif; ?>

                        <?php if ($vehicaCurrentWidget->showCarListButton()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isCarListPage()) : ?>
                                    class="vehica-panel-menu-mobile__button vehica-panel-menu-mobile__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-mobile__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>"
                                    title="<?php echo esc_attr(vehicaApp('vehicles_string')); ?>"
                            >
                                <?php echo esc_html(vehicaApp('vehicles_string')); ?>
                            </a>
                        <?php endif; ?>

                        <?php if (vehicaApp('settings_config')->isMessageSystemEnabled()) : ?>
                            <a
                                <?php if ($vehicaCurrentWidget->isMessagesPage()) : ?>
                                    class="vehica-panel-menu-mobile__button vehica-panel-menu-mobile__button--active"
                                <?php else : ?>
                                    class="vehica-panel-menu-mobile__button"
                                <?php endif; ?>
                                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getMessagesPageUrl()); ?>"
                            >
                                <span><?php echo esc_html(vehicaApp('messages_string')); ?></span>
                            </a>
                        <?php endif; ?>

                        <a
                            <?php if ($vehicaCurrentWidget->isAnyAccountPage()) : ?>
                                class="vehica-panel-menu-mobile__button vehica-panel-menu-mobile__button--active"
                            <?php else : ?>
                                class="vehica-panel-menu-mobile__button"
                            <?php endif; ?>
                                href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>"
                                title="<?php echo esc_attr(vehicaApp('account_string')); ?>"
                        >
                            <?php echo esc_html(vehicaApp('account_string')); ?>
                        </a>

                        <vehica-logout
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_logout')); ?>"
                                vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_logout')); ?>"
                                redirect-url="<?php echo esc_url(site_url()); ?>"
                                in-progress-text="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
                                confirm-button-text="<?php echo esc_attr(vehicaApp('back_to_homepage_string')); ?>"
                                success-text="<?php echo esc_attr(vehicaApp('logout_success_string')); ?>"
                                class="vehica-panel-menu-mobile__button"
                        >
                            <span slot-scope="logout" @click.prevent="logout.onClick">
                                <?php echo esc_html(vehicaApp('sign_out_string')); ?>
                            </span>
                        </vehica-logout>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php

    if ($vehicaCurrentWidget->isCreateCarPage()) :
        get_template_part('templates/general/panel/create_car');
    elseif (is_user_logged_in()) :
        if ($vehicaCurrentWidget->isEditCarPage()) :
            get_template_part('templates/general/panel/edit_car');
        elseif ($vehicaCurrentWidget->isAccountPage()) :
            get_template_part('templates/general/panel/account');
        elseif ($vehicaCurrentWidget->isAccountChangePasswordPage()) :
            get_template_part('templates/general/panel/account_change_password');
        elseif ($vehicaCurrentWidget->isAccountSocialPage()) :
            get_template_part('templates/general/panel/account_social');
        elseif ($vehicaCurrentWidget->isCarListPage()) :
            get_template_part('templates/general/panel/car_list');
        elseif ($vehicaCurrentWidget->isFavoritePage()) :
            get_template_part('templates/general/panel/favorite');
        elseif ($vehicaCurrentWidget->isBuyPackagePage()) :
            get_template_part('templates/general/panel/buy_package');
        elseif ($vehicaCurrentWidget->isMessagesPage()) :
            get_template_part('templates/general/panel/messages');
        endif;
    endif;
    ?>
</div>