<header>
    <div class="vehica-hide-mobile vehica-hide-tablet">
        <div class="vehica-menu__desktop">
            <div class="vehica-menu__wrapper">
                <div class="vehica-menu__left">
                    <div class="vehica-logo">
                        <a
                                href="<?php echo esc_url(get_site_url()); ?>"
                                title="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        >
                            <?php if (has_custom_logo()) : ?>
                                <img
                                        src="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'))); ?>"
                                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                                >
                            <?php else : ?>
                                <?php echo esc_html(get_bloginfo('name')); ?>
                            <?php endif; ?>
                        </a>
                    </div>

                    <div class="vehica-menu__container">
                        <div class="vehica-menu-hover"></div>
                        <?php
                        if (has_nav_menu('vehica-primary')) :
                            wp_nav_menu([
                                'theme_location' => 'vehica-primary',
                                'container' => 'div',
                                'container_class' => 'vehica-menu',
                                'container_id' => 'vehica-menu',
                                'walker' => new VehicaMenuWalker(),
                                'items_wrap' => '%3$s',
                                'depth' => 4,
                            ]);
                        else :
                            wp_nav_menu([
                                'container' => 'div',
                                'container_class' => 'vehica-menu',
                                'container_id' => 'vehica-menu',
                                'walker' => new VehicaMenuWalker(),
                                'items_wrap' => '%3$s',
                                'depth' => 4,
                            ]);
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="vehica-hide-desktop">
        <div class="vehica-app vehica-mobile-menu__wrapper vehica-hide-desktop">
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
                                      class="vehica-hamburger-fill"
                                      transform="translate(11925 -99)"
                                      fill="currentColor"/>
                                <rect id="Op_component_2" data-name="Op component 2" width="19.6" height="4.2" rx="1.5"
                                      class="vehica-hamburger-fill"
                                      transform="translate(11925 -90.6)"
                                      fill="currentColor"/>
                                <rect id="Op_component_3" data-name="Op component 3" width="14" height="4.2" rx="1.5"
                                      class="vehica-hamburger-fill"
                                      transform="translate(11925 -82.2)"
                                      fill="currentColor"/>
                            </g>
                        </svg>

                        <template>
                            <div :class="{'vehica-active': menu.show}" class="vehica-mobile-menu__open">
                                <div class="vehica-mobile-menu__open__content">
                                    <div class="vehica-mobile-menu__open__top">
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

                                    <div class="vehica-mobile-menu__nav">
                                        <?php
                                        if (has_nav_menu('vehica-primary')) :
                                            wp_nav_menu([
                                                'theme_location' => 'vehica-primary',
                                                'container' => 'div',
                                                'container_class' => 'vehica-menu',
                                                'container_id' => 'vehica-menu-mobile',
                                                'walker' => new VehicaMenuWalker(),
                                                'items_wrap' => '%3$s',
                                                'depth' => 4,
                                            ]);
                                        else :
                                            wp_nav_menu([
                                                'container' => 'div',
                                                'container_class' => 'vehica-menu',
                                                'container_id' => 'vehica-menu-mobile',
                                                'walker' => new VehicaMenuWalker(),
                                                'items_wrap' => '%3$s',
                                                'depth' => 4,
                                            ]);
                                        endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="vehica-mobile-menu-mask"></div>
                        </template>
                    </div>
                </vehica-mobile-menu>
            </div>

            <div class="vehica-mobile-menu__logo vehica-mobile-menu__logo--right">
                <div class="vehica-logo">
                    <a
                            href="<?php echo esc_url(get_site_url()); ?>"
                            title="<?php echo esc_attr(get_bloginfo('name')); ?>"
                    >
                        <?php if (has_custom_logo()) : ?>
                            <img
                                    src="<?php echo esc_url(wp_get_attachment_image_url(get_theme_mod('custom_logo'))); ?>"
                                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                            >
                        <?php else : ?>
                            <?php echo esc_html(get_bloginfo('name')); ?>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<div class="vehica-wrapper">
    <div class="vehica-blog<?php if (!is_active_sidebar('vehica-sidebar')) : ?> vehica-blog--no-sidebar<?php endif; ?>">
