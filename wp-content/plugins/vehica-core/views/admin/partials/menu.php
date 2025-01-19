<?php if (!defined('VEHICA_DEMO') && !vehicaApp('settings_config')->hideHelp()) : ?>
    <script>
        window.fwSettings = {
            'widget_id': 101000002800
        };
        !function () {
            if ("function" != typeof window.FreshworksWidget) {
                var n = function () {
                    n.q.push(arguments)
                };
                n.q = [], window.FreshworksWidget = n
            }
        }()
    </script>
    <script type='text/javascript' src='https://euc-widget.freshworks.com/widgets/101000002800.js' async defer></script>
<?php endif; ?>

<div class="vehica-menu">
    <div class="vehica-menu__inner">

        <div class="vehica-menu__brand">
            <a href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel')); ?>">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="">
            </a>
        </div>

        <div>
            <a
                <?php if ($_GET['page'] === 'vehica_panel') : ?>
                    class="vehica-menu__link vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel')); ?>"
            >
                <i class="material-icons">apps</i> <?php esc_html_e('Basic Setup', 'vehica-core'); ?>
            </a>

            <?php if ($_GET['page'] === 'vehica_panel') : ?>
                <div
                        class="vehica-menu__submenu"
                        v-scroll-spy-active="{selector: 'a', class: 'vehica-menu__submenu__link--active'}"
                        v-scroll-spy-link
                >
                    <a href="#logo">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Logo', 'vehica-core'); ?>
                    </a>

                    <a href="#color">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Color', 'vehica-core'); ?>
                    </a>

                    <a href="#public-information">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Public Information', 'vehica-core'); ?>
                    </a>

                    <a href="#user-panel">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('User Panel', 'vehica-core'); ?>
                    </a>

                    <a href="#header">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Header', 'vehica-core'); ?>
                    </a>

                    <a href="#footer">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Footer', 'vehica-core'); ?>
                    </a>

                    <a href="#fonts">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Fonts', 'vehica-core'); ?>
                    </a>

                    <a href="#listings-general">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Listings General', 'vehica-core'); ?>
                    </a>

                    <a href="#listing-card">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Listing Card', 'vehica-core'); ?>
                    </a>

                    <a href="#social-media">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Social Media', 'vehica-core'); ?>
                    </a>

                    <a href="#currencies">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Currencies', 'vehica-core'); ?>
                    </a>

                    <a href="#number-format">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Number Format', 'vehica-core'); ?>
                    </a>

                    <a href="#other">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Other', 'vehica-core'); ?>
                    </a>
                </div>
            <?php endif; ?>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_user_panel') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_user_panel')); ?>"
            >
                <i class="material-icons">supervisor_account</i> <?php esc_html_e('User Panel', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_layouts_and_templates') : ?>
                    class="vehica-menu__link vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_layouts_and_templates')); ?>"
            >
                <i class="material-icons">view_compact</i> <?php esc_html_e('Layouts & Templates', 'vehica-core'); ?>
            </a>

            <?php if ($_GET['page'] === 'vehica_panel_layouts_and_templates') : ?>
                <div
                        class="vehica-menu__submenu"
                        v-scroll-spy-active="{selector: 'a', class: 'vehica-menu__submenu__link--active'}"
                        v-scroll-spy-link
                >
                    <a href="#layouts">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Layouts', 'vehica-core'); ?>
                    </a>
                    <a href="#car_single">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Car single', 'vehica-core'); ?>
                    </a>
                    <a href="#car_archive">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Car archive', 'vehica-core'); ?>
                    </a>
                    <a href="#seller">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Seller', 'vehica-core'); ?>
                    </a>
                    <a href="#post_single">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Post single', 'vehica-core'); ?>
                    </a>
                    <a href="#post_archive">
                        <i class="material-icons">chevron_right</i>
                        <?php esc_html_e('Post archive', 'vehica-core'); ?>
                    </a>
                </div>
            <?php endif; ?>
            <a
                <?php if ($_GET['page'] === 'vehica_panel_car_fields' || (isset($_GET['vehica_type']) && $_GET['vehica_type'] === 'field')) : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_car_fields')); ?>"
            >
                <i class="material-icons">build</i> <?php esc_html_e('Custom Fields', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_monetization') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_monetization')); ?>"
            >
                <i class="material-icons">attach_money</i> <?php esc_html_e('Monetization', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_maps') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_maps')); ?>"
            >
                <i class="material-icons">location_on</i> <?php esc_html_e('Google Maps', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_notifications') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_notifications')); ?>"
            >
                <i class="material-icons">mail</i> <?php esc_html_e('Notifications', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_rename_and_translate') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_rename_and_translate')); ?>"
            >
                <i class="material-icons">text_fields</i> <?php esc_html_e('Translate & Rename', 'vehica-core'); ?>
            </a>

            <a
                <?php if ($_GET['page'] === 'vehica_panel_advanced') : ?>
                    class="vehica-menu__link vehica-menu__link--rounded vehica-menu__link--active"
                <?php else : ?>
                    class="vehica-menu__link"
                <?php endif; ?>
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_advanced')); ?>"
            >
                <i class="material-icons">settings</i> <?php esc_html_e('Advanced', 'vehica-core'); ?>
            </a>

        </div>
    </div>
</div>