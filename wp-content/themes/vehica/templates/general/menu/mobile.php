<?php
/* @var \Vehica\Widgets\General\MenuGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-menu vehica-menu-mobile-bar">
    <div class="vehica-menu-mobile-bar__menu-icon vehica-menu__mobile-bars__open">
        <svg class="vehica-menu-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="15" viewBox="0 0 28 21">
            <g id="menu" transform="translate(-11925 99)">
                <rect id="Op_component_1" data-name="Op component 1" width="28" height="4.2" rx="1.5"
                      transform="translate(11925 -99)" fill="#ff4605"/>
                <rect id="Op_component_2" data-name="Op component 2" width="19.6" height="4.2" rx="1.5"
                      transform="translate(11925 -90.6)" fill="#ff4605"/>
                <rect id="Op_component_3" data-name="Op component 3" width="14" height="4.2" rx="1.5"
                      transform="translate(11925 -82.2)" fill="#ff4605"/>
            </g>
        </svg>
    </div>

    <div class="vehica-menu-mobile-bar__logo">
        <?php if ($vehicaCurrentWidget->showLogo()) : ?>
            <a
                    href="<?php echo esc_url(get_site_url()); ?>"
                    title="<?php echo esc_attr(get_bloginfo('name')); ?>"
            >
                <?php if ($vehicaCurrentWidget->hasLogo()) : ?>
                    <img
                            src="<?php echo esc_url($vehicaCurrentWidget->getLogoUrl()); ?>"
                            alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                            class="vehica-menu-mobile-bar__logo__image"
                    >
                <?php endif; ?>
                <?php if ($vehicaCurrentWidget->hasStickyLogo()) : ?>
                    <img
                            src="<?php echo esc_url($vehicaCurrentWidget->getStickyLogoUrl()); ?>"
                            alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                            class="vehica-menu-mobile-bar__logo__image vehica-menu-mobile-bar__logo__image--sticky"
                    >
                <?php endif; ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="vehica-menu-mobile-bar__user-icon">
        <a href="#">
            <i class="far fa-user-circle"></i>
        </a>
    </div>
</div>

<div class="vehica-menu-mobile-open">
    <div class="vehica-menu-mobile-open__top">
        <div class="vehica-mobile-menu__open__top__x">
            <svg xmlns="http://www.w3.org/2000/svg" width="20.124" height="21.636" viewBox="0 0 20.124 21.636">
                <g id="close" transform="translate(-11872.422 99.636)">
                    <path id="Path_19" data-name="Path 19" d="M20.163-1.122a2.038,2.038,0,0,1,.61,1.388A1.989,1.989,0,0,1,20.05,1.79a2.4,2.4,0,0,1-1.653.649,2.116,2.116,0,0,1-1.637-.754l-6.034-6.94-6.1,6.94a2.18,2.18,0,0,1-1.637.754A2.364,2.364,0,0,1,1.37,1.79,1.989,1.989,0,0,1,.648.266a2.02,2.02,0,0,1,.578-1.388l6.58-7.363L1.45-15.636a2.038,2.038,0,0,1-.61-1.388,1.989,1.989,0,0,1,.722-1.524A2.364,2.364,0,0,1,3.184-19.2a2.177,2.177,0,0,1,1.669.785l5.874,6.669,5.809-6.669A2.177,2.177,0,0,1,18.2-19.2a2.364,2.364,0,0,1,1.621.649,1.989,1.989,0,0,1,.722,1.524,2.02,2.02,0,0,1-.578,1.388L13.615-8.485Z" transform="translate(11871.773 -80.439)" fill="#ff4605"/>
                </g>
            </svg>
        </div>
        <div class="vehica-menu-mobile-open__top__submit">
            <button class="vehica-button">
                + Submit Vehicle
            </button>
        </div>
    </div>

    <div class="vehica-menu-mobile-open__links">
        <div <?php $vehicaCurrentWidget->print_render_attribute_string('menu_wrapper'); ?>>
            <?php if ($vehicaCurrentWidget->hasMenu()) : ?>
                <?php $vehicaCurrentWidget->displayMenu(); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
