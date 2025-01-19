<?php
/* @var \Vehica\Widgets\General\MenuGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div
    <?php if ($vehicaCurrentWidget->showAccount()) : ?>
        class="vehica-mobile-menu__wrapper vehica-hide-desktop"
    <?php else : ?>
        class="vehica-mobile-menu__wrapper vehica-mobile-menu__wrapper--mobile-simple-menu vehica-hide-desktop"
    <?php endif; ?>
>
    <?php if ($vehicaCurrentWidget->hasMenu()) : ?>
        <div class="vehica-mobile-menu__hamburger">
            <vehica-mobile-menu>
                <div slot-scope="menu">
                    <svg
                            @click.prevent="menu.onShow"
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="15"
                            viewBox="0 0 28 21"
                            class="vehica-menu-icon"
                    >
                        <g id="vehica-menu-svg" transform="translate(-11925 99)">
                            <rect id="Op_component_1" data-name="Op component 1" width="28" height="4.2" rx="1.5"
                                  transform="translate(11925 -99)"
                                  fill="<?php echo esc_attr(vehicaApp('primary_color')); ?>"/>
                            <rect id="Op_component_2" data-name="Op component 2" width="19.6" height="4.2" rx="1.5"
                                  transform="translate(11925 -90.6)"
                                  fill="<?php echo esc_attr(vehicaApp('primary_color')); ?>"/>
                            <rect id="Op_component_3" data-name="Op component 3" width="14" height="4.2" rx="1.5"
                                  transform="translate(11925 -82.2)"
                                  fill="<?php echo esc_attr(vehicaApp('primary_color')); ?>"/>
                        </g>
                    </svg>

                    <template>
                        <div :class="{'vehica-active': menu.show}" class="vehica-mobile-menu__open">
                            <div class="vehica-mobile-menu__open__content">
                                <div class="vehica-mobile-menu__open__top">
                                    <?php if ($vehicaCurrentWidget->showSubmitButton()) : ?>
                                        <div class="vehica-mobile-menu__open__top__submit-button">
                                            <a
                                                    href="<?php echo esc_url($vehicaCurrentWidget->getSubmitButtonUrl()); ?>"
                                                    class="vehica-button"
                                            >
                                                <?php if ($vehicaCurrentWidget->hasButtonMobileCustomIcon()) : ?>
                                                    <i class="<?php echo esc_attr($vehicaCurrentWidget->getButtonMobileCustomIcon()); ?>"></i>
                                                <?php else : ?>
                                                    <i class="fas fa-plus"></i>
                                                <?php endif; ?>

                                                <?php if (!empty(vehicaApp('settings_config')->getCustomCtaButtonText())) : ?>
                                                    <?php echo esc_html(vehicaApp('settings_config')->getCustomCtaButtonText()); ?>
                                                <?php else : ?>
                                                    <?php echo esc_html(vehicaApp('submit_vehicle_string')); ?>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="vehica-mobile-menu__open__top__x">
                                        <svg
                                                @click="menu.onShow"
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="20.124"
                                                height="21.636"
                                                viewBox="0 0 20.124 21.636"
                                        >
                                            <g id="close" transform="translate(-11872.422 99.636)">
                                                <path id="Path_19" data-name="Path 19"
                                                      d="M20.163-1.122a2.038,2.038,0,0,1,.61,1.388A1.989,1.989,0,0,1,20.05,1.79a2.4,2.4,0,0,1-1.653.649,2.116,2.116,0,0,1-1.637-.754l-6.034-6.94-6.1,6.94a2.18,2.18,0,0,1-1.637.754A2.364,2.364,0,0,1,1.37,1.79,1.989,1.989,0,0,1,.648.266a2.02,2.02,0,0,1,.578-1.388l6.58-7.363L1.45-15.636a2.038,2.038,0,0,1-.61-1.388,1.989,1.989,0,0,1,.722-1.524A2.364,2.364,0,0,1,3.184-19.2a2.177,2.177,0,0,1,1.669.785l5.874,6.669,5.809-6.669A2.177,2.177,0,0,1,18.2-19.2a2.364,2.364,0,0,1,1.621.649,1.989,1.989,0,0,1,.722,1.524,2.02,2.02,0,0,1-.578,1.388L13.615-8.485Z"
                                                      transform="translate(11871.773 -80.439)" fill="#ff4605"/>
                                            </g>
                                        </svg>
                                    </div>
                                </div>

                                <?php if ($vehicaCurrentWidget->hasMenu()) : ?>
                                    <div class="vehica-mobile-menu__nav">
                                        <?php $vehicaCurrentWidget->displayMenu('vehica-menu-mobile'); ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty(vehicaApp('phone')) || !empty(vehicaApp('email'))) : ?>
                                    <div class="vehica-mobile-menu__info">
                                        <?php if (!empty(vehicaApp('phone'))) : ?>
                                            <a href="tel:<?php echo esc_attr(vehicaApp('phone_url')); ?>">
                                                <i class="fas fa-phone-alt vehica-text-primary"></i> <?php echo esc_html(vehicaApp('phone')); ?>
                                            </a>
                                        <?php endif; ?>

                                        <?php if (!empty(vehicaApp('email'))) : ?>
                                            <a href="mailto:<?php echo esc_attr(vehicaApp('email')); ?>">
                                                <i class="far fa-envelope vehica-text-primary"></i> <?php echo esc_html(vehicaApp('email')); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (count(vehicaApp('currencies')) > 1) : ?>
                                    <vehica-currency-switcher
                                            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_change')); ?>"
                                            :disable-position="true"
                                    >
                                        <div slot-scope="props" class="vehica-mobile-menu__currency-switcher">
                                            <?php echo esc_html(vehicaApp('currency_string')); ?>
                                            <select @change="props.onChange($event.target.value)">
                                                <?php foreach (vehicaApp('currencies') as $vehicaCurrency) :
                                                    /* @var \Vehica\Field\Fields\Price\Currency $vehicaCurrency */
                                                    ?>
                                                    <option
                                                            value="<?php echo esc_attr($vehicaCurrency->getKey()); ?>"
                                                        <?php if (vehicaApp('current_currency')->getKey() === $vehicaCurrency->getKey()) : ?>
                                                            selected
                                                        <?php endif; ?>
                                                    >
                                                        <?php echo esc_html($vehicaCurrency->getName()); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <i class="fas fa-angle-down vehica-text-primary"></i>
                                        </div>
                                    </vehica-currency-switcher>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="vehica-mobile-menu-mask"></div>
                    </template>
                </div>
            </vehica-mobile-menu>
        </div>
    <?php endif; ?>

    <div
        <?php if (!$vehicaCurrentWidget->hasMenu() && $vehicaCurrentWidget->showAccount()) : ?>
            class="vehica-mobile-menu__logo vehica-mobile-menu__logo--left"
        <?php elseif ($vehicaCurrentWidget->showAccount()) : ?>
            class="vehica-mobile-menu__logo"
        <?php else : ?>
            class="vehica-mobile-menu__logo vehica-mobile-menu__logo--right"
        <?php endif; ?>
    >
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
        <?php endif; ?>
    </div>

    <?php if ($vehicaCurrentWidget->showAccount()) : ?>
        <div class="vehica-mobile-menu__login">
            <?php if (is_user_logged_in()) : ?>
                <a href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getCarListPageUrl()); ?>">
                    <i class="fas fa-user-friends fa-2x"></i>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(vehicaApp('settings_config')->getLoginPageUrl()); ?>">
                    <i class="fas fa-user-friends fa-2x"></i>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
