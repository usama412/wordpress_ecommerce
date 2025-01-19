<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Import any XML or CSV File', 'vehica-core'); ?>
            </h2>

            <?php esc_html_e('Vehica is compatible with free version of WP All Import Plugin. You can use this plugin e.g. to import your listings and users from other website', 'vehica-core'); ?>

            <div>
                <a
                        class="vehica-doc-link vehica-doc-link--full-width"
                        target="_blank"
                        href="https://support.vehica.com/support/solutions/articles/101000377058"
                >
                    <i class="fas fa-info-circle"></i>
                    <span><?php esc_html_e('Click here to read how to Import any XML or CSV File', 'vehica-core'); ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <?php if (!defined('PMXI_VERSION')) : ?>
                <?php esc_html_e('Please install free version of: ', 'vehica-core'); ?>

                <a
                        href="https://wordpress.org/plugins/wp-all-import/"
                        target="_blank"
                        style="text-decoration: underline;"
                >
                    <strong><?php esc_html_e('WP All Import Plugin', 'vehica-core'); ?></strong>
                </a>

                <vehica-install-plugin
                        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/plugins/install')); ?>"
                        plugin="wp-all-import"
                >
                    <div style="margin-top:20px;" slot-scope="props">
                        <button class="vehica-button" v-if="!props.inProgress" @click.prevent="props.install">
                            <?php esc_html_e('Click to Install', 'vehica-core'); ?>
                        </button>

                        <div v-if="props.inProgress">
                            <i class="fas fa-sync fa-spin"></i>
                        </div>
                    </div>
                </vehica-install-plugin>
            <?php endif; ?>

            <?php if (defined('PMXI_VERSION')) : ?>
                <a
                        href="<?php echo esc_url(admin_url('admin.php?page=pmxi-admin-import')); ?>"
                        style="text-decoration: underline; color: green;"
                >
                    <strong><?php esc_html_e('Click here to upload your CSV/XML file ', 'vehica-core'); ?></strong>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Cookie Notice', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">

            <?php if (!class_exists('Cookie_Notice')) : ?>
                <?php esc_html_e('Please install free version of: ', 'vehica-core'); ?>

                <a
                        href="https://wordpress.org/plugins/cookie-notice/"
                        target="_blank"
                        style="text-decoration: underline"
                >
                    <strong><?php esc_html_e('Cookie Notice for GDPR & CCPA by dFactory', 'vehica-core'); ?></strong>
                </a>

                <vehica-install-plugin
                        request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica/plugins/install')); ?>"
                        plugin="cookie-notice"
                >
                    <div style="margin-top:20px;" slot-scope="props">
                        <button class="vehica-button" v-if="!props.inProgress" @click.prevent="props.install">
                            <?php esc_html_e('Click to Install', 'vehica-core'); ?>
                        </button>

                        <div v-if="props.inProgress">
                            <i class="fas fa-sync fa-spin"></i>
                        </div>
                    </div>
                </vehica-install-plugin>
            <?php else : ?>
                <a
                        href="<?php echo esc_url(admin_url('options-general.php?page=cookie-notice')); ?>"
                        style="text-decoration: underline; color:green;"
                >
                    <strong><?php esc_html_e('Click here to configure', 'vehica-core'); ?></strong>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Bulk Add Terms', 'vehica-core'); ?>
            </h2>

            <a
                    target="_blank"
                    href="https://support.vehica.com/support/solutions/articles/101000377059"
                    class="vehica-doc-link vehica-doc-link--warning vehica-doc-link--full-width"
            >
                <i class="fas fa-exclamation-circle"></i>
                <span><?php esc_html_e('Click here to read documentation before you use it', 'vehica-core'); ?></span>
            </a>

            <?php esc_html_e('This module can be used in 2 ways:', 'vehica-core'); ?>
            <br><br><?php esc_html_e('1. You can import list of terms e.g. 100 vehicle features via plain text (each term in new line)', 'vehica-core'); ?>
            <br><br>
            <?php esc_html_e('2. You can use it to quickly create Parent-Child relationship e.g. you can add Make (Lamborghini) and connect to it many childs (Aventador, Gallardo, Murcielago, Diablo, Huracan)', 'vehica-core'); ?>

        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <form
                    action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_term_importer')); ?>"
                    method="post"
            >
                <vehica-term-importer>
                    <div slot-scope="importer">
                        <div class="vehica-term-importer">
                            <div class="vehica-term-importer__parent">
                                <div>
                                    <div style="margin-bottom:8px;">
                                        <label for="parent_taxonomy">
                                            <?php esc_html_e('Taxonomy', 'vehica-core'); ?>
                                        </label>
                                    </div>

                                    <select
                                            name="parent_taxonomy"
                                            id="parent_taxonomy"
                                            @change="importer.setTaxonomy($event.target.value)"
                                            :value="importer.taxonomy"

                                    >
                                        <option value="0">
                                            <?php esc_html_e('Select', 'vehica-core'); ?>
                                        </option>

                                        <?php foreach (vehicaApp('taxonomies') as $vehicaTaxonomy):
                                            /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
                                            ?>
                                            <option value="<?php echo esc_attr($vehicaTaxonomy->getKey()); ?>">
                                                <?php echo esc_html($vehicaTaxonomy->getName()); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div>
                                    <div style="margin-top:10px;">
                                        <label for="parent_terms">
                                            <?php esc_html_e('Terms', 'vehica-core'); ?>
                                        </label>
                                    </div>

                                    <textarea name="parent_terms" id="parent_terms" cols="30" rows="10"></textarea>
                                </div>
                            </div>

                            <?php foreach (vehicaApp('child_taxonomies') as $vehicaTaxonomy) :
                                /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
                                foreach ($vehicaTaxonomy->getParentTaxonomies() as $vehicaParentTaxonomy) :?>
                                    <div
                                            v-if="importer.taxonomy === '<?php echo esc_attr($vehicaParentTaxonomy->getKey()); ?>'"
                                            class="vehica-term-importer__child"
                                    >
                                        <div>
                                            <label for="child_taxonomy">
                                                <?php esc_html_e('Child Field (Optional)'); ?>
                                            </label>
                                        </div>

                                        <div style="font-weight: 700;margin-top: 15px;margin-bottom: 21px;">
                                            <?php echo esc_html($vehicaTaxonomy->getName()); ?>
                                        </div>

                                        <input
                                                type="hidden"
                                                name="child_taxonomy[]"
                                                value="<?php echo esc_attr($vehicaTaxonomy->getKey()); ?>"
                                        >

                                        <div>
                                            <div style="margin-top:10px;">
                                                <label for="child_terms">
                                                    <?php esc_html_e('Child Terms', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <textarea name="child_terms[]" id="child_terms" cols="30"
                                                      rows="10"></textarea>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </vehica-term-importer>

                <button class="vehica-button vehica-button--accent">
                    <i class="fas fa-file-import"></i> <?php esc_html_e('Import', 'vehica-core'); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Connect Terms (Parent/Child)', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('By clicking "Connect" button Vehica will assign all child terms (e.g. Banana) to Parents (e.g. Fruit) basing on listings that are in your database. It can be useful if you imported database of listings (e.g. via WP All Import) and you wish quickly create this type of parent-child relations for your search form or "submit listing" form.', 'vehica-core'); ?>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <vehica-connect-terms request-url="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <div slot-scope="props">
                    <button
                            class="vehica-button vehica-button--accent"
                            @click.prevent="props.onStart"
                            :disabled="props.inProgress"
                    >
                        <span v-if="!props.inProgress">
                            <?php esc_html_e('Connect', 'vehica-core'); ?>
                        </span>

                        <template v-if="props.inProgress">
                            <?php esc_html_e('Connecting...', 'vehica-core'); ?>
                        </template>
                    </button>
                </div>
            </vehica-connect-terms>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Clean up the images', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Delete images that were added by the front-end panel and are not assigned to any listing.', 'vehica-core'); ?>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <a
                    class="vehica-button vehica-button--accent"
                    href="<?php echo esc_url(admin_url('admin-post.php?action=vehica/images/cleanUp')); ?>"
            >
                <?php esc_html_e('Delete images', 'vehica-core'); ?>
            </a>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Check expire listings', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Automatically checking if listings have expired should work right away. The exception is when your hosting provider blocks cron jobs. In this case you can set up an external cron using a site like ', 'vehica-core'); ?>
                <a href="https://cron-job.org/en/" target="_blank"><strong>cron-job.org</strong></a>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div style="margin-bottom: 30px;">
                <?php if (!empty(get_option('vehica_expire_hash'))) : ?>
                    <div style="font-size: 12px;margin-bottom: 15px;">
                        <?php echo esc_url(admin_url('admin-post.php?action=vehica/checkExpire&hash=' . get_option('vehica_expire_hash'))); ?>
                    </div>
                <?php endif; ?>

                <a href="<?php echo esc_url(admin_url('admin-post.php?action=vehica/generateExpireHash')); ?>"
                   class="vehica-button">
                    <?php esc_html_e('Generate new cron url', 'vehica-core'); ?>
                </a>
            </div>

            <div>
                <a href="<?php echo esc_url(admin_url('admin-post.php?action=vehica/checkExpire')); ?>"
                   class="vehica-button">
                    <?php esc_html_e('Check expire listings', 'vehica-core'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Global Assignments', 'vehica-core'); ?>
            </h2>

            <div><?php esc_html_e('Thanks to this settings Vehica Theme assign correct information across website. Changing it can be useful only in some advanced customizations so it is highly recommended to not change this settings without clear reason.', 'vehica-core'); ?></div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">


            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_ARCHIVE_PAGES); ?>">
                        <?php esc_html_e('Additional Search Results Pages', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_ARCHIVE_PAGES); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_ARCHIVE_PAGES); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Page $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (in_array($vehicaPage->getId(), vehicaApp('settings_config')->getCustomArchivePageIds(), true)) : ?>
                                    selected="selected"
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PAGE); ?>">
                        <?php esc_html_e('Panel Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isPanelPage($vehicaPage)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGIN_PAGE); ?>">
                        <?php esc_html_e('Login Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGIN_PAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGIN_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isLoginPage($vehicaPage)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PAGE); ?>">
                        <?php esc_html_e('Register Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REGISTER_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isRegisterPage($vehicaPage)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BLOG_PAGE); ?>">
                        <?php esc_html_e('Blog Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BLOG_PAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BLOG_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isBlogPage($vehicaPage)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ERROR_PAGE); ?>">
                        <?php esc_html_e('404 Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ERROR_PAGE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ERROR_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Default', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages_list') as $vehicaPageId => $vehicaPageName) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPageId); ?>"
                                <?php if (vehicaApp('404_page_id') === $vehicaPageId) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPageName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE_PAGE); ?>">
                        <?php esc_html_e('Compare Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE_PAGE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages_list') as $vehicaPageId => $vehicaPageName) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPageId); ?>"
                                <?php if (vehicaApp('settings_config')->getComparePageId() === $vehicaPageId) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPageName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CALCULATOR_PAGE); ?>">
                        <?php esc_html_e('Calculator Page', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CALCULATOR_PAGE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CALCULATOR_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Disabled', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages_list') as $vehicaPageId => $vehicaPageName) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPageId); ?>"
                                <?php if (vehicaApp('settings_config')->getCalculatorPageId() === $vehicaPageId) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPageName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PRIVATE_USER_ROLE); ?>">
                        <?php esc_html_e('Private User Role', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PRIVATE_USER_ROLE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PRIVATE_USER_ROLE); ?>"
                    >
                        <?php foreach (vehicaApp('user_roles') as $key => $name) : ?>
                            <option
                                    value="<?php echo esc_attr($key); ?>"
                                <?php if (vehicaApp('settings_config')->getPrivateUserRole() === $key) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BUSINESS_USER_ROLE); ?>">
                        <?php esc_html_e('Business User Role', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BUSINESS_USER_ROLE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::BUSINESS_USER_ROLE); ?>"
                    >
                        <?php foreach (vehicaApp('user_roles') as $key => $name) : ?>
                            <option
                                    value="<?php echo esc_attr($key); ?>"
                                <?php if (vehicaApp('settings_config')->getBusinessUserRole() === $key) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <button class="vehica-button">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>