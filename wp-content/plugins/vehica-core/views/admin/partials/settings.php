<?php /** @noinspection ALL */
wp_enqueue_script('vehica-design-config');

?>
<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="logo">
                <?php esc_html_e('Logo', 'vehica-core'); ?>
            </h2>
            <?php esc_html_e('Upload your logo files (jpg/png). A reversed logo is another version of the logo that can be placed on a dark background.', 'vehica-core'); ?>

            <div style="margin-top:10px;">
                <a
                        class="vehica-doc-link vehica-doc-link--full-width"
                        target="_blank"
                        style="margin: 5px 0 20px 0;"
                        href="https://support.vehica.com/support/solutions/articles/101000376553"
                >
                    <i class="fas fa-info-circle"></i>
                    <span><?php esc_html_e('Need to change logo size? Click here to read more', 'vehica-core'); ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-logo">
                <div class="vehica-logo__single">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGO); ?>">
                        <strong><?php esc_html_e('Default', 'vehica-core'); ?></strong>
                    </label>

                    <vehica-set-logo
                            title-text="<?php esc_attr_e('Select or Upload Logo', 'vehica-core'); ?>"
                            button-text="<?php esc_attr_e('Use this media', 'vehica-core'); ?>"
                        <?php if (vehicaApp('settings_config')->hasLogo()) : ?>
                            :initial-logo-id="<?php echo esc_attr(vehicaApp('settings_config')->getLogoId()); ?>"
                        <?php endif; ?>
                    >
                        <div slot-scope="setLogo">
                            <div v-if="setLogo.logoId">
                                <div class="vehica-logo__single-inner">
                                    <img :src="setLogo.logoUrl" alt="">
                                    <i class="fas fa-trash" v-if="setLogo.logoId"
                                       @click.prevent="setLogo.remove"></i>
                                </div>
                                <input
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGO); ?>"
                                        :value="setLogo.logoId"
                                        type="hidden"
                                >
                            </div>

                            <div
                                    v-if="!setLogo.logoId"
                                    @click.prevent="setLogo.openUploader"
                                    class="vehica-logo__placeholder"
                            >
                                <?php esc_html_e('Click to add logo', 'vehica-core'); ?>
                            </div>

                            <button
                                    @click.prevent="setLogo.openUploader"
                                    class="vehica-button vehica-button--add-new"
                            >
                                <i class="fas fa-plus-circle"></i> <?php esc_html_e('Add new logo', 'vehica-core'); ?>
                            </button>
                        </div>
                    </vehica-set-logo>
                </div>

                <div class="vehica-logo__single">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGO_INVERSE); ?>">
                        <strong><?php esc_html_e('Reversed', 'vehica-core'); ?></strong>
                    </label>

                    <vehica-set-logo
                            title-text="<?php esc_attr_e('Select or Upload Logo', 'vehica-core'); ?>"
                            button-text="<?php esc_attr_e('Use this media', 'vehica-core'); ?>"
                        <?php if (vehicaApp('settings_config')->hasLogoInverse()) : ?>
                            :initial-logo-id="<?php echo esc_attr(vehicaApp('settings_config')->getLogoInverseId()); ?>"
                        <?php endif; ?>
                    >
                        <div slot-scope="setLogo">
                            <div v-if="setLogo.logoId">
                                <div class="vehica-logo__single-inner">
                                    <img :src="setLogo.logoUrl" alt="">
                                    <i v-if="setLogo.logoId" @click.prevent="setLogo.remove" class="fas fa-trash"></i>
                                </div>

                                <input
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LOGO_INVERSE); ?>"
                                        :value="setLogo.logoId"
                                        type="hidden"
                                >
                            </div>

                            <div
                                    v-if="!setLogo.logoId"
                                    @click.prevent="setLogo.openUploader"
                                    class="vehica-logo__placeholder"
                            >
                                <span><?php esc_html_e('Click to add logo', 'vehica-core'); ?></span>
                            </div>

                            <button
                                    @click.prevent="setLogo.openUploader"
                                    class="vehica-button  vehica-button--add-new"
                            >
                                <i class="fas fa-plus-circle"></i>
                                <?php esc_html_e('Add new logo', 'vehica-core'); ?>
                            </button>
                        </div>
                    </vehica-set-logo>
                </div>
            </div>

            <div>
                <button data-hook="logo" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="color">
                <?php esc_html_e('Primary Color', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Default color is', 'vehica-core'); ?>: <span style="color:#ff4605">#ff4605</span>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-colors-wrapper">
                <div>
                    <vehica-design-color
                            type="primary"
                            initial-color="<?php echo esc_attr(vehicaApp('settings_config')->getPrimaryColor()); ?>"
                    >
                        <div slot-scope="colorProps" class="vehica-color">
                            <div class="vehica-color__name"
                                 style="margin-top: 8px;  font-weight: 700; font-size: 16px;">
                                <label
                                        @click="colorProps.onShowPicker"
                                        for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PRIMARY_COLOR); ?>"
                                >
                                    <?php esc_html_e('Click to select', 'vehica-core'); ?>
                                </label>
                            </div>

                            <div class="vehica-color__sample">
                                <div
                                        @click="colorProps.onShowPicker"
                                        class="vehica-color-picker"
                                        :style="{'background-color': colorProps.currentColor}"
                                ></div>

                                <div
                                        v-if="colorProps.showPicker"
                                        @click.prevent
                                        class="vehica-color__sample__window"
                                >
                                    <vehica-chrome-picker
                                            :disable-alpha="true"
                                            :value="colorProps.currentColor"
                                            @input="colorProps.setCurrentColor"
                                    ></vehica-chrome-picker>
                                    <div class="vehica-color__buttons">
                                        <button
                                                class="vehica-flat-button vehica-flat-button--cyan"
                                                @click.prevent="colorProps.onSave"
                                        >
                                            <?php esc_html_e('Choose', 'vehica-core'); ?>
                                        </button>

                                        <button
                                                class="vehica-flat-button vehica-flat-button--transparent"
                                                @click.prevent="colorProps.onCancel"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <input
                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PRIMARY_COLOR); ?>"
                                        :value="colorProps.color"
                                        type="hidden"
                                >
                            </div>
                        </div>
                    </vehica-design-color>
                </div>
            </div>

            <div>
                <button data-hook="color" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="public-information">
                <?php esc_html_e('Public information', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('These information will be displayed across your website e.g. on the contact us page or footer.', 'vehica-core'); ?>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HOMEPAGE); ?>">
                        <i class="material-icons">home</i> <?php esc_html_e('Homepage', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HOMEPAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HOMEPAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            if (vehicaApp('settings_config')->isBlogPage($vehicaPage)) {
                                continue;
                            }
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isHomepage($vehicaPage)) : ?>
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
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EMAIL); ?>">
                        <i class="material-icons">email</i> <?php esc_html_e('Email', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EMAIL); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EMAIL); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getMail()); ?>"
                            type="text"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PHONE); ?>">
                        <i class="material-icons">local_phone</i> <?php esc_html_e('Phone', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PHONE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PHONE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getPhone()); ?>"
                            type="text"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ADDRESS); ?>">
                        <i class="material-icons">add_location</i> <?php esc_html_e('Address', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <textarea
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ADDRESS); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ADDRESS); ?>"
                            cols="3"
                            rows="3"
                    ><?php echo wp_kses_post(vehicaApp('settings_config')->getAddress()); ?></textarea>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_ABOUT_US); ?>">
                        <i class="material-icons">description</i> <?php esc_html_e('About', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <textarea
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_ABOUT_US); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_ABOUT_US); ?>"
                            cols="3"
                            rows="6"
                    ><?php echo wp_kses_post(vehicaApp('settings_config')->getFooterAboutUs()); ?></textarea>
                </div>
            </div>

            <div>
                <button data-hook="public-information" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="user-panel">
                <?php esc_html_e('User Panel', 'vehica-core'); ?>
            </h2>

            <div style="margin-bottom:20px">
                <?php esc_html_e('Configure Front-end User Panel', 'vehica-core'); ?>
            </div>

            <a
                    href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_user_panel')); ?>"
                    class="vehica-button vehica-button--accent"
            >
                <?php esc_html_e('All User Panel settings here', 'vehica-core'); ?>
            </a>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div class="vehica-field">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_USER_REGISTER); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_USER_REGISTER); ?>"
                            type="checkbox"
                            value="1"
                        <?php if (vehicaApp('settings_config')->isUserRegisterEnabled()) : ?>
                            checked
                        <?php endif; ?>
                    >

                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_USER_REGISTER); ?>">
                        <?php esc_html_e('Registration - enable user registration', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MESSAGE_SYSTEM); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MESSAGE_SYSTEM); ?>"
                            type="checkbox"
                            value="1"
                        <?php if (vehicaApp('settings_config')->isMessageSystemEnabled())  : ?>
                            checked
                        <?php endif; ?>
                    >

                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MESSAGE_SYSTEM); ?>">
                        <?php esc_html_e('Private Message System', 'vehica-core'); ?>
                    </label>
                    <a
                            class="vehica-doc-link vehica-doc-link--full-width"
                            target="_blank"
                            style="margin: 5px 0 0 10px;"
                            href="https://support.vehica.com/support/solutions/articles/101000376996">
                        <i class="fas fa-info-circle"></i>
                        <span><?php esc_html_e('Read how it works', 'vehica-core'); ?></span>
                    </a>
                </div>

                <!--                <div class="vehica-field">-->
                <!--                    <div class="vehica-field__col-1">-->
                <!--                        <label for="-->
                <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--">-->
                <!--                            --><?php //esc_html_e('Listing Owner Contact Type', 'vehica-core'); ?>
                <!--                        </label>-->
                <!--                    </div>-->
                <!---->
                <!--                    <div class="vehica-field__col-2">-->
                <!--                        <select-->
                <!--                                class="vehica-selectize"-->
                <!--                                name="-->
                <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--"-->
                <!--                                id="-->
                <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--"-->
                <!--                        >-->
                <!--                            <option-->
                <!--                                    value="messages"-->
                <!--                                --><?php //if (vehicaApp('settings_config')->getContactOwnerType() === 'messages') : ?>
                <!--                                    selected-->
                <!--                                --><?php //endif; ?>
                <!--                            >-->
                <!--                                --><?php //esc_html_e('Private Message (make sure you activated it above)', 'vehica-core'); ?>
                <!--                            </option>-->
                <!---->
                <!--                            --><?php //foreach (vehicaApp('contact_forms_list') as $vehicaKey => $vehicaLabel) : ?>
                <!--                                <option-->
                <!--                                        value="--><?php //echo esc_attr($vehicaKey); ?><!--"-->
                <!--                                    --><?php //if (vehicaApp('settings_config')->getContactOwnerType() === $vehicaKey) : ?>
                <!--                                        selected-->
                <!--                                    --><?php //endif; ?>
                <!--                                >-->
                <!--                                    --><?php //echo esc_html($vehicaLabel); ?>
                <!--                                </option>-->
                <!--                            --><?php //endforeach; ?>
                <!--                        </select>-->
                <!--                    </div>-->
                <!--                </div>-->

                <div class="vehica-field">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MODERATION); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MODERATION); ?>"
                            type="checkbox"
                            value="1"
                        <?php if (vehicaApp('settings_config')->isModerationEnabled()) : ?>
                            checked
                        <?php endif; ?>
                    >

                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MODERATION); ?>">
                        <?php esc_html_e('Moderation - admin needs to approve listings', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SUBMIT_WITHOUT_LOGIN); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SUBMIT_WITHOUT_LOGIN); ?>"
                            type="checkbox"
                            value="1"
                        <?php if (vehicaApp('settings_config')->isSubmitWithoutLoginEnabled()) : ?>
                            checked
                        <?php endif; ?>
                    >

                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SUBMIT_WITHOUT_LOGIN); ?>">
                        <?php esc_html_e('Allow adding listing before login', 'vehica-core'); ?>
                    </label>
                </div>

                <button data-hook="user-panel" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="header">
                <?php esc_html_e('Header', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Choose your menu. If you check "Sticky Menu" it will be sticky on all your pages.',
                    'vehica-core'); ?>
            </div>

            <div style="margin-top:10px;">
                <a
                        class="vehica-doc-link vehica-doc-link--full-width"
                        target="_blank"
                        style="margin: 5px 0 20px 0;"
                        href="https://support.vehica.com/support/solutions/articles/101000376550">
                    <i class="fas fa-info-circle"></i>
                    <span><?php esc_html_e('Need to change menu colors and size? Click here to read more', 'vehica-core'); ?></span>
                </a>
            </div>

        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAIN_MENU); ?>">
                        <i class="material-icons">menu</i> <?php esc_html_e('Main menu', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAIN_MENU); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAIN_MENU); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('menus') as $vehicaMenu) :
                            /* @var \Vehica\Model\Term\Term $vehicaMenu */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaMenu->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isMainMenu($vehicaMenu)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaMenu->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_BUTTON); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_BUTTON); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->displayMenuButton()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_BUTTON); ?>">
                    <?php esc_html_e('Display CTA Button', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_CTA_BUTTON_TEXT); ?>">
                        <?php esc_html_e('Custom CTA Button Text', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_CTA_BUTTON_TEXT); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CUSTOM_CTA_BUTTON_TEXT); ?>"
                            type="text"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getCustomCtaButtonText()); ?>"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CTA_PAGE); ?>">
                        <?php esc_html_e('Menu CTA Button Link', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CTA_PAGE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CTA_PAGE); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Submit Listing Page (Default)', 'vehica-core'); ?>
                        </option>

                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                            /* @var \Vehica\Model\Post\Post $vehicaPage */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isCtaPage($vehicaPage)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaPage->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_ACCOUNT); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_ACCOUNT); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->displayMenuAccount()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DISPLAY_MENU_ACCOUNT); ?>">
                    <?php esc_html_e('Display User Menu for logged in users and "Login / Register" links for not logged in', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STICKY_MENU); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STICKY_MENU); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->isStickyMenuEnabled()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::STICKY_MENU); ?>">
                    <?php esc_html_e('Sticky menu', 'vehica-core'); ?>
                </label>
            </div>

            <button data-hook="header" class="vehica-button vehica-hook">
                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="footer">
                <?php esc_html_e('Footer', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Footer is the last section of any page, choose additional menu and add your own copyright text.',
                    'vehica-core') ?>
            </div>


            <div>
                <a
                        class="vehica-doc-link vehica-doc-link--full-width"
                        target="_blank"
                        href="https://support.vehica.com/support/solutions/articles/101000376985">
                    <i class="fas fa-info-circle"></i>
                    <span><?php esc_html_e('You can edit footer sections and color via page builder - click here to read more', 'vehica-core'); ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_MENU); ?>">
                        <i class="material-icons">menu</i> <?php esc_html_e('Footer menu', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_MENU); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FOOTER_MENU); ?>"
                    >
                        <option value="0">
                            <?php esc_html_e('Not set', 'vehica-core'); ?>
                        </option>
                        <?php foreach (vehicaApp('menus') as $vehicaMenu) :
                            /* @var \Vehica\Model\Term\Term $vehicaMenu */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaMenu->getId()); ?>"
                                <?php if (vehicaApp('settings_config')->isFooterMenu($vehicaMenu)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaMenu->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COPYRIGHTS_TEXT); ?>">
                        <i class="material-icons">copyright</i> <?php esc_html_e('Copyrights text', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COPYRIGHTS_TEXT); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COPYRIGHTS_TEXT); ?>"
                            type="text"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getCopyrightsText()); ?>"
                    >
                </div>
            </div>

            <div>
                <button data-hook="footer" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="fonts">
                <?php esc_html_e('Fonts', 'vehica-core'); ?>
            </h2>
            <div><?php esc_html_e('You have access to 800+ Google Fonts. You can also use any custom fonts if you wish', 'vehica-core'); ?>
                <a class="vehica-doc-link-solo" target="_blank"
                   href="https://support.vehica.com/support/solutions/articles/101000377071">
                    <?php esc_html_e('- click here to read more', 'vehica-core'); ?>
                </a>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HEADING_FONT); ?>">
                        <?php esc_html_e('Font family - heading', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HEADING_FONT); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HEADING_FONT); ?>"
                    >
                        <?php foreach (vehicaApp('settings_config')->getFonts() as $vehicaFontType => $vehicaFonts) : ?>
                            <optgroup label="<?php echo esc_attr($vehicaFontType); ?>">
                                <?php foreach ($vehicaFonts as $vehicaFont) : ?>
                                    <option
                                            value="<?php echo esc_attr($vehicaFont); ?>"
                                        <?php if (vehicaApp('settings_config')->isHeadingFont($vehicaFont)) : ?>
                                            selected="selected"
                                        <?php endif; ?>
                                    ><?php echo esc_html($vehicaFont); ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TEXT_FONT); ?>">
                        <?php esc_html_e('Font family - body text', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TEXT_FONT); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TEXT_FONT); ?>"
                    >
                        <?php foreach (vehicaApp('settings_config')->getFonts() as $vehicaFontType => $vehicaFonts) : ?>
                            <optgroup label="<?php echo esc_attr($vehicaFontType); ?>">
                                <?php foreach ($vehicaFonts as $vehicaFont) : ?>
                                    <option
                                            value="<?php echo esc_attr($vehicaFont); ?>"
                                        <?php if (vehicaApp('settings_config')->isTextFont($vehicaFont)) : ?>
                                            selected="selected"
                                        <?php endif; ?>
                                    >
                                        <?php echo esc_html($vehicaFont); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <button data-hook="fonts" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="vehicle-listings-general">
                <?php esc_html_e('Listings General', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_FAVORITE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_FAVORITE); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (!vehicaApp('show_favorite'))  : ?>
                        checked
                    <?php endif; ?>
                >
                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_FAVORITE); ?>">
                    <?php esc_html_e('Disable "Favorite"', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE); ?>">
                        <?php esc_html_e('Compare Module', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE); ?>"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::COMPARE); ?>"
                    >
                        <option
                                value="1"
                            <?php if (vehicaApp('compare_mode') === 1) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('Search Page Only - User selectable ON/OFF', 'vehica-core'); ?>
                        </option>

                        <option
                                value="2"
                            <?php if (vehicaApp('compare_mode') === 2) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('Always ON', 'vehica-core'); ?>
                        </option>

                        <option
                                value="0"
                            <?php if (empty(vehicaApp('compare_mode'))) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('Disabled', 'vehica-core'); ?>
                        </option>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CAR_BREADCRUMBS); ?>">
                        <?php esc_html_e('Breadcrumbs', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CAR_BREADCRUMBS); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CAR_BREADCRUMBS); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('taxonomies_list') as $vehicaTaxonomyId => $vehicaTaxonomyName) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaTaxonomyId); ?>"
                                <?php if (vehicaApp('settings_config')->isCarBreadcrumb($vehicaTaxonomyId)) : ?>
                                    selected="selected"
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaTaxonomyName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::AUTO_CAR_TITLE); ?>">
                        <?php esc_html_e('Auto-Generate Listing Title', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::AUTO_CAR_TITLE); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::AUTO_CAR_TITLE); ?>"
                            multiple="multiple"
                            placeholder="<?php esc_html_e('Do not auto generate - user enter title manually', 'vehica-core'); ?>"
                    >
                        <?php foreach (vehicaApp('settings_config')->getAutoCarTitleFields() as $simpleTextField) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $simpleTextField */
                            ?>
                            <option
                                    value="<?php echo esc_attr($simpleTextField->getId()); ?>"
                                    selected
                            >
                                <?php echo esc_html($simpleTextField->getName()); ?>
                            </option>
                        <?php endforeach; ?>

                        <?php foreach (vehicaApp('simple_text_car_fields') as $simpleTextField) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $simpleTextField */
                            if (in_array($simpleTextField->getId(), vehicaApp('settings_config')->getAutoCarTitleFieldIds(), true)) {
                                continue;
                            }
                            ?>
                            <option value="<?php echo esc_attr($simpleTextField->getId()); ?>">
                                <?php echo esc_html($simpleTextField->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_CUSTOM_TEMPLATES); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_CUSTOM_TEMPLATES); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->customTemplatesEnabled()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_CUSTOM_TEMPLATES); ?>">
                    <?php esc_html_e('Allow to assign different templates to specific types of listings', 'vehica-core'); ?>
                    <a href="https://support.vehica.com/support/solutions/articles/101000376548"
                       target="_blank"
                       class="vehica-doc-link-solo">
                        <?php esc_html_e('- click here to read more', 'vehica-core'); ?>
                    </a>
                </label>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LISTINGS_TABLE_TAXONOMIES); ?>">
                        <?php esc_html_e('Admin Table Filters', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LISTINGS_TABLE_TAXONOMIES); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LISTINGS_TABLE_TAXONOMIES); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('taxonomies_list') as $vehicaTaxonomyId => $vehicaTaxonomyName) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaTaxonomyId); ?>"
                                <?php if (in_array($vehicaTaxonomyId, vehicaApp('settings_config')->getListingsTableTaxonomyIds(), true)) : ?>
                                    selected="selected"
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaTaxonomyName); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div>
                <button data-hook="fonts" class="vehica-button vehica-hook">
                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                </button>
            </div>

        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="vehicle-listing-card">
                <?php esc_html_e('Listing Card', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">

            <!--            <div class="vehica-field">-->
            <!--                <div class="vehica-field__col-1">-->
            <!--                    <label for="-->
            <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL_TYPE); ?><!--">-->
            <!--                        <i class="material-icons">photo_size_select_large</i>-->
            <!--                        --><?php //esc_html_e('Label Type', 'vehica-core'); ?>
            <!--                    </label>-->
            <!--                </div>-->
            <!---->
            <!--                <div class="vehica-field__col-2">-->
            <!--                    <select-->
            <!--                            id="-->
            <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL_TYPE); ?><!--"-->
            <!--                            class="vehica-selectize"-->
            <!--                            name="-->
            <?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL_TYPE); ?><!--"-->
            <!--                    >-->
            <!--                        <option-->
            <!--                                value="single"-->
            <!--                            --><?php //if (vehicaApp('settings_config')->getCardLabelType() === 'single') : ?>
            <!--                                selected-->
            <!--                            --><?php //endif; ?>
            <!--                        >--><?php //esc_html_e('Single', 'vehica-core'); ?><!--</option>-->
            <!--                        <option-->
            <!--                                value="multiple"-->
            <!--                            --><?php //if (vehicaApp('settings_config')->getCardLabelType() === 'multiple') : ?>
            <!--                                selected-->
            <!--                            --><?php //endif; ?>
            <!--                        >--><?php //esc_html_e('Multiple', 'vehica-core'); ?><!--</option>-->
            <!--                    </select>-->
            <!--                </div>-->
            <!--            </div>-->


            <h3><?php esc_html_e('Standard Card', 'vehica-core'); ?></h3>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_FEATURES); ?>">
                        <?php esc_html_e('Fields', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_FEATURES); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_FEATURES); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('settings_config')->getCardFeatures() as $vehicaSimpleTextAttribute) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                            ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>" selected>
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>

                        <?php foreach (vehicaApp('simple_text_car_fields') as $vehicaSimpleTextAttribute) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                            if (vehicaApp('settings_config')->isCardFeature($vehicaSimpleTextAttribute->getId())) :
                                continue;
                            endif;
                            ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>">
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_MULTILINE_FEATURES); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_MULTILINE_FEATURES); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->isCardMultilineFeaturesEnabled()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_MULTILINE_FEATURES); ?>">
                    <?php esc_html_e('Multiline features', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_IMAGE_SIZE); ?>">
                        <?php esc_html_e('Image Size', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_IMAGE_SIZE); ?>"
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_IMAGE_SIZE); ?>"
                    >
                        <?php foreach (vehicaApp('image_sizes') as $vehicaImageSizeKey => $vehicaImageSize) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaImageSizeKey); ?>"
                                <?php if ($vehicaImageSizeKey === vehicaApp('card_image_size')) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaImageSizeKey); ?>
                                (<?php echo esc_html($vehicaImageSize['width']) ?>
                                x <?php echo esc_html($vehicaImageSize['height']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <h3><?php esc_html_e('Row Card', 'vehica-core'); ?></h3>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_IMAGE_SIZE); ?>">
                        <?php esc_html_e('Image Size', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_IMAGE_SIZE); ?>"
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_IMAGE_SIZE); ?>"
                    >
                        <?php foreach (vehicaApp('image_sizes') as $vehicaImageSizeKey => $vehicaImageSize) : ?>
                            <option
                                    value="<?php echo esc_attr($vehicaImageSizeKey); ?>"
                                <?php if ($vehicaImageSizeKey === vehicaApp('row_image_size')) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaImageSizeKey); ?>
                                (<?php echo esc_html($vehicaImageSize['width']) ?>
                                x <?php echo esc_html($vehicaImageSize['height']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_SECONDARY_FEATURES); ?>">
                        <?php esc_html_e('Fields Upper', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_SECONDARY_FEATURES); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_SECONDARY_FEATURES); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('settings_config')->getRowSecondaryFeatures() as $vehicaSimpleTextAttribute) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                            ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>" selected>
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>

                        <?php foreach (vehicaApp('simple_text_car_fields') as $vehicaSimpleTextAttribute) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                            if (vehicaApp('settings_config')->isRowSecondaryFeature($vehicaSimpleTextAttribute->getId())) :
                                continue;
                            endif;
                            ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>">
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_PRIMARY_FEATURES); ?>">
                        <?php esc_html_e('Fields Bottom', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_PRIMARY_FEATURES); ?>[]"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_PRIMARY_FEATURES); ?>"
                            multiple="multiple"
                    >
                        <?php foreach (vehicaApp('settings_config')->getRowPrimaryFeatures() as $vehicaSimpleTextAttribute) : ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>" selected>
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>

                        <?php foreach (vehicaApp('simple_text_car_fields') as $vehicaSimpleTextAttribute) :
                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                            if (vehicaApp('settings_config')->isRowPrimaryFeature($vehicaSimpleTextAttribute->getId())) :
                                continue;
                            endif;
                            ?>
                            <option value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>">
                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_LOCATION); ?>">
                        <?php esc_html_e('Location Field', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_LOCATION); ?>"
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_LOCATION); ?>"
                    >
                        <option
                                value="0"
                            <?php if (empty(vehicaApp('row_location_type')))  : ?>
                                selected
                            <?php endif; ?>
                        ><?php esc_html_e('Hide', 'vehica-core'); ?></option>

                        <option
                                value="user_location"
                            <?php if (vehicaApp('row_location_type') === 'user_location') : ?>
                                selected
                            <?php endif; ?>
                        ><?php esc_html_e('User Location', 'vehica-core'); ?></option>

                        <?php foreach (vehicaApp('location_fields') as $vehicaLocationField) :
                            /* @var \Vehica\Model\Post\Field\LocationField $vehicaLocationField */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaLocationField->getKey()); ?>"
                                <?php if (vehicaApp('row_location_type') === $vehicaLocationField->getKey()) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaLocationField->getName()); ?>
                                (<?php esc_html_e('Custom Field', 'vehica-core') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_HIDE_CALCULATE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_HIDE_CALCULATE); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('row_hide_calculate'))  : ?>
                        checked
                    <?php endif; ?>
                >
                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ROW_HIDE_CALCULATE); ?>">
                    <?php esc_html_e('Hide calculate financing link', 'vehica-core'); ?>
                </label>
            </div>

            <h3><?php esc_html_e('All Cards', 'vehica-core'); ?></h3>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL); ?>">
                        <i class="material-icons">photo_size_select_large</i>
                        <?php esc_html_e('Featured Label (max 1 visible)', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL); ?>"
                            class="vehica-selectize"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_LABEL); ?>[]"
                            multiple
                    >
                        <option
                                value="featured"
                            <?php if (vehicaApp('settings_config')->isCardLabelElement('featured')) : ?>
                                selected
                            <?php endif; ?>
                        >
                            <?php esc_html_e('Featured', 'vehica-core') ?>
                        </option>

                        <?php foreach (vehicaApp('taxonomies') as $vehicaTaxonomy) :
                            /* @var \Vehica\Model\Post\Field\Taxonomy\Taxonomy $vehicaTaxonomy */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaTaxonomy->getKey()); ?>"
                                <?php if (vehicaApp('settings_config')->isCardLabelElement($vehicaTaxonomy->getKey())) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaTaxonomy->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SHOW_CONTACT_FOR_PRICE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SHOW_CONTACT_FOR_PRICE); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->showContactForPrice()) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::SHOW_CONTACT_FOR_PRICE); ?>">
                    <?php esc_html_e('Display "Contact for a price" if price is not set', 'vehica-core'); ?>
                </label>
            </div>

            <?php if (vehicaApp('price_fields')->count() > 1) : ?>
                <div class="vehica-field">
                    <div class="vehica-field__col-1">
                        <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_PRICE_FIELD); ?>">
                            <span class="material-icons">attach_money</span>
                            <?php esc_html_e('Price Field', 'vehica-core'); ?>
                        </label>
                    </div>

                    <div class="vehica-field__col-2">
                        <select
                                id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_PRICE_FIELD); ?>"
                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_PRICE_FIELD); ?>[]"
                                class="vehica-selectize"
                                placeholder="<?php esc_attr_e('Not set', 'vehica-core'); ?>"
                                multiple
                        >
                            <?php foreach (vehicaApp('price_fields') as $vehicaPriceField) :
                                /* @var \Vehica\Model\Post\Field\Price\PriceField $vehicaPriceField */
                                ?>
                                <option
                                        value="<?php echo esc_attr($vehicaPriceField->getId()); ?>"
                                    <?php if (in_array($vehicaPriceField->getId(), vehicaApp('settings_config')->getCardPriceFieldIds(), true)) : ?>
                                        selected
                                    <?php endif; ?>
                                >
                                    <?php echo esc_html($vehicaPriceField->getName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="margin-bottom:10px">
                        <?php esc_html_e('Listing Cards can display only 1 price field. If you select more than 1 price field here, the 2nd price field will be displayed only if the 1st price field is empty.', 'vehica-core'); ?>
                    </div>
                </div>
            <?php else :
                $vehicaPriceField = vehicaApp('price_fields')->first();
                if ($vehicaPriceField instanceof \Vehica\Model\Post\Field\Price\PriceField) :
                    ?>
                    <input
                            type="hidden"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_PRICE_FIELD); ?>"
                            value="<?php echo esc_attr($vehicaPriceField->getId()); ?>"
                    >
                <?php endif; ?>
            <?php endif; ?>

            <?php if (vehicaApp('gallery_fields')->count() > 1) : ?>
                <div class="vehica-field">
                    <div class="vehica-field__col-1">
                        <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_GALLERY_FIELD); ?>">
                            <?php esc_html_e('Gallery Field', 'vehica-core'); ?>
                        </label>
                    </div>

                    <div class="vehica-field__col-2">
                        <select
                                class="vehica-selectize"
                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_GALLERY_FIELD); ?>"
                                id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_GALLERY_FIELD); ?>"
                        >
                            <option
                                    value="0"
                                <?php if (!vehicaApp('settings_config')->getCardGalleryField()) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php esc_html_e('Not set', 'vehica-core'); ?>
                            </option>

                            <?php foreach (vehicaApp('gallery_fields') as $vehicaGalleryField) :
                                /* @var \Vehica\Model\Post\Field\GalleryField $vehicaGalleryField */
                                ?>
                                <option
                                        value="<?php echo esc_attr($vehicaGalleryField->getId()); ?>"
                                    <?php if (vehicaApp('settings_config')->getCardGalleryFieldId() === $vehicaGalleryField->getId()) : ?>
                                        selected
                                    <?php endif; ?>
                                >
                                    <?php echo esc_html($vehicaGalleryField->getName()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php else :
                $galleryField = vehicaApp('gallery_fields')->first();
                if ($galleryField instanceof \Vehica\Model\Post\Field\GalleryField) :
                    ?>
                    <input
                            type="hidden"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_GALLERY_FIELD); ?>"
                            value="<?php echo esc_attr($galleryField->getId()); ?>"
                    >
                <?php endif; ?>
            <?php endif; ?>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_HIDE_PHOTO_COUNT); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_HIDE_PHOTO_COUNT); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (!vehicaApp('show_photo_count'))  : ?>
                        checked
                    <?php endif; ?>
                >
                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::CARD_HIDE_PHOTO_COUNT); ?>">
                    <?php esc_html_e('Hide Photo Count', 'vehica-core'); ?>
                </label>
            </div>

            <button data-hook="vehicle-card-settings" class="vehica-button vehica-hook mt-3">
                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="social-media">
                <?php esc_html_e('Social media', 'vehica-core'); ?>
            </h2>

            <div>
                <?php esc_html_e('Add links to your social media profiles. Remove "#" sign to hide icons on your website. Facebook API Key is required to share images correctly.',
                    'vehica-core'); ?>
            </div>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_API); ?>">
                        <i class="fab fa-facebook-f"></i>
                        <?php esc_html_e('Facebook API', 'vehica-core'); ?>
                        <a class="vehica-doc-link-solo"
                           href="https://support.vehica.com/support/solutions/articles/101000377053">
                            <?php esc_html_e('- click here to read more', 'vehica-core'); ?>
                        </a>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_API); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_API); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookApi()); ?>"
                            type="text"
                            placeholder=""
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_PROFILE); ?>">
                        <i class="fab fa-facebook-f"></i>
                        <?php esc_html_e('Facebook profile', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://www.facebook.com/envato/"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TWITTER_PROFILE); ?>">
                        <i class="fab fa-twitter"></i>
                        <?php esc_html_e('Twitter profile', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TWITTER_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TWITTER_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getTwitterProfile()); ?>"
                            type="text"
                            placeholder=""
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::INSTAGRAM_PROFILE); ?>">
                        <i class="fab fa-instagram"></i>
                        <?php esc_html_e('Instagram profile', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::INSTAGRAM_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::INSTAGRAM_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getInstagramProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://www.instagram.com/envato/"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LINKEDIN_PROFILE); ?>">
                        <i class="fab fa-linkedin"></i>
                        <?php esc_html_e('LinkedIn profile', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LINKEDIN_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::LINKEDIN_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getLinkedinProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://www.linkedin.com/company/envato"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::YOUTUBE_PROFILE); ?>">
                        <i class="fab fa-youtube"></i>
                        <?php esc_html_e('Youtube profile', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::YOUTUBE_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::YOUTUBE_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getYoutubeProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://www.youtube.com/envato"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TIKTOK_PROFILE); ?>">
                        <i class="fab fa-tiktok"></i>

                        <?php esc_html_e('TikTok', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TIKTOK_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TIKTOK_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getTikTokProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://www.tiktok.com/@envato"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TELEGRAM_PROFILE); ?>">
                        <i class="fab fa-telegram"></i>

                        <?php esc_html_e('Telegram', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TELEGRAM_PROFILE); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::TELEGRAM_PROFILE); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getTelegramProfile()); ?>"
                            type="text"
                            placeholder="e.g. https://t.me/envato"
                    >
                </div>
            </div>

            <button data-hook="social-media" class="vehica-button vehica-hook mt-3">
                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</div>

<div class="vehica-section vehica-section--full">
    <h2 id="currencies">
        <?php esc_html_e('Currencies', 'vehica-core'); ?>
    </h2>

    <vehica-currencies
            :initial-default-currency-id="<?php echo esc_attr(vehicaApp('currency_default_id')); ?>"
            :initial-currencies="<?php echo htmlspecialchars(json_encode(vehicaApp('currencies'))); ?>"
            error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
            in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
            success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
            creating-currency-text="<?php esc_attr_e('Creating currency', 'vehica-core'); ?>"
            currency-created-text="<?php esc_attr_e('Currency created successfully', 'vehica-core'); ?>"
            create-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_create')); ?>"
            delete-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_delete')); ?>"
            set-default-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_set_default')); ?>"
            are-you-sure-title-text="<?php esc_attr_e('Are you sure?', 'vehica-core'); ?>"
            are-you-sure-msg-text="<?php esc_attr_e('You won\'t be able to revert this!', 'vehica-core'); ?>"
            confirm-text="<?php esc_attr_e('Yes', 'vehica-core'); ?>"
            cancel-text="<?php esc_attr_e('Cancel', 'vehica-core'); ?>"
            deleting-currency-text="<?php esc_attr_e('Deleting currency', 'vehica-core'); ?>"
            currency-deleted-text="<?php esc_attr_e('Currency deleted successfully', 'vehica-core'); ?>"
    >
        <div slot-scope="currenciesProps">
            <div class="vehica-table">
                <div class="vehica-table__head">
                    <div
                            v-if="currenciesProps.currencies.length > 1"
                            class="vehica-table__cell vehica-table__cell--state"
                    >
                        <?php esc_html_e('Actions', 'vehica-core'); ?>
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--currency-name">
                        <?php esc_html_e('Name', 'vehica-core'); ?>
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--sign">
                        <?php esc_html_e('Sign', 'vehica-core'); ?>
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--position">
                        <?php esc_html_e('Sign Position', 'vehica-core'); ?>
                    </div>

                    <div class="vehica-table__cell vehica-table__cell--format">
                        <?php esc_html_e('Format', 'vehica-core'); ?>
                    </div>

                    <div
                            v-if="currenciesProps.currencies.length > 1"
                            class="vehica-table__cell vehica-table__cell--currency-actions"
                    >
                        <?php esc_html_e('Remove', 'vehica-core'); ?>
                    </div>
                </div>

                <vehica-row v-for="currency in currenciesProps.currencies" :row-id="currency.id" :key="currency.id">
                    <div
                            slot-scope="rowProps"
                            class="vehica-table__row"
                            :class="{'vehica-table-row--active': rowProps.isEdited}"
                    >
                        <div
                                v-if="currenciesProps.currencies.length > 1"
                                class="vehica-table__cell vehica-table__cell--state"
                        >
                            <div
                                    v-if="currenciesProps.defaultCurrency !== currency.id"
                                    class="vehica-table__not-active"
                                    @click.prevent="currenciesProps.setDefault(currency)"
                            >
                                <i class="material-icons vehica-checked">
                                    radio_button_checked
                                </i>

                                <i class="material-icons vehica-unchecked">
                                    radio_button_unchecked
                                </i>

                                <span>
                                    <?php esc_html_e('Default', 'vehica-core'); ?>
                                </span>
                            </div>

                            <div
                                    class="vehica-table__active"
                                    v-if="currenciesProps.defaultCurrency === currency.id"
                            >
                                <i class="material-icons vehica-checked">
                                    radio_button_checked
                                </i>

                                <span><?php esc_html_e('Default', 'vehica-core'); ?></span>
                            </div>
                        </div>

                        <div class="vehica-table__cell vehica-table__cell--currency-name">
                            <vehica-set-currency-name
                                    :currency="currency"
                                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_set_name')); ?>"
                                    success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                                    in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                    error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                            >
                                <div slot-scope="setCurrencyNameProps">
                                    <template v-if="!setCurrencyNameProps.showEditField">
                                        {{ currency.name }}
                                        <i
                                                @click.prevent="setCurrencyNameProps.onShowEditField"
                                                class="material-icons vehica-action vehica-action--left"
                                        >edit</i>
                                    </template>

                                    <template v-if="setCurrencyNameProps.showEditField">
                                        <input
                                                @input="setCurrencyNameProps.setName($event.target.value)"
                                                :value="setCurrencyNameProps.name"
                                                type="text"
                                        >

                                        <button
                                                @click.prevent="setCurrencyNameProps.onSave"
                                                class="vehica-flat-button vehica-flat-button--cyan"
                                        >
                                            <?php esc_html_e('Save', 'vehica-core'); ?>
                                        </button>

                                        <button
                                                @click.prevent="setCurrencyNameProps.onCancel"
                                                class="vehica-flat-button vehica-flat-button--transparent"
                                        >
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </template>
                                </div>
                            </vehica-set-currency-name>
                        </div>

                        <div class="vehica-table__cell vehica-table__cell--sign">
                            <vehica-set-currency-sign
                                    :currency="currency"
                                    request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_set_sign')); ?>"
                                    success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                                    in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                    error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                            >
                                <div slot-scope="setCurrencySignProps">
                                    <template v-if="!setCurrencySignProps.showEditField">
                                        {{ currency.sign }}
                                        <i class="material-icons vehica-action vehica-action--left"
                                           @click.prevent="setCurrencySignProps.onShowEditField"
                                        >edit</i>
                                    </template>

                                    <template v-if="setCurrencySignProps.showEditField">
                                        <input
                                                @input="setCurrencySignProps.setSign($event.target.value)"
                                                :value="setCurrencySignProps.sign"
                                                type="text"
                                        >

                                        <button class="vehica-flat-button vehica-flat-button--cyan"
                                                @click.prevent="setCurrencySignProps.onSave">
                                            <?php esc_html_e('Save', 'vehica-core'); ?>
                                        </button>

                                        <button class="vehica-flat-button vehica-flat-button--transparent"
                                                @click.prevent="setCurrencySignProps.onCancel">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </template>
                                </div>
                            </vehica-set-currency-sign>
                        </div>
                        <vehica-set-currency-sign-position
                                :currency="currency"
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_set_sign_position')); ?>"
                                success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                                in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                        >
                            <div
                                    slot-scope="setCurrencySignPositionProps"
                                    class="vehica-table__cell vehica-table__cell--position"
                            >
                                <template v-if="!setCurrencySignPositionProps.showEditField">
                                    <span v-if="setCurrencySignPositionProps.signPosition === 'before'">
                                        <?php esc_html_e('Before the number', 'vehica-core'); ?>
                                    </span>

                                    <span v-if="setCurrencySignPositionProps.signPosition === 'after'">
                                        <?php esc_html_e('After the number', 'vehica-core'); ?>
                                    </span>

                                    <i
                                            class="material-icons vehica-action vehica-action--left"
                                            @click.prevent="setCurrencySignPositionProps.onShowEditField"
                                    >
                                        edit
                                    </i>
                                </template>

                                <template v-if="setCurrencySignPositionProps.showEditField">
                                    <select
                                            :value="setCurrencySignPositionProps.signPosition"
                                            @change="setCurrencySignPositionProps.setSignPosition($event.target.value)"
                                    >
                                        <option value="<?php echo esc_attr(\Vehica\Field\Fields\Price\Currency::SIGN_POSITION_BEFORE); ?>">
                                            <?php esc_html_e('Before the number', 'vehica-core'); ?>
                                        </option>

                                        <option value="<?php echo esc_attr(\Vehica\Field\Fields\Price\Currency::SIGN_POSITION_AFTER); ?>">
                                            <?php esc_html_e('After the number', 'vehica-core'); ?>
                                        </option>
                                    </select>

                                    <button class="vehica-flat-button vehica-flat-button--cyan"
                                            @click.prevent="setCurrencySignPositionProps.onSave"
                                    >
                                        <?php esc_html_e('Save', 'vehica-core'); ?>
                                    </button>

                                    <button class="vehica-flat-button vehica-flat-button--transparent"
                                            @click.prevent="setCurrencySignPositionProps.onCancel"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </template>
                            </div>
                        </vehica-set-currency-sign-position>

                        <vehica-set-currency-format
                                :currency="currency"
                                request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_currency_set_format')); ?>"
                                success-text="<?php esc_attr_e('Changes saved successfully', 'vehica-core'); ?>"
                                in-progress-text="<?php esc_attr_e('Saving changes', 'vehica-core'); ?>"
                                error-text="<?php esc_attr_e('Something went wrong', 'vehica-core'); ?>"
                                :formats="<?php echo htmlspecialchars(json_encode(\Vehica\Field\Fields\Price\Currency::getFormats())); ?>"
                        >
                            <div slot-scope="currencyFormatProps"
                                 class="vehica-table__cell vehica-table__cell--format">
                                <template v-if="!currencyFormatProps.showEditField">
                                    <span v-html="currencyFormatProps.displayFormat"></span>

                                    <i
                                            class="material-icons vehica-action vehica-action--left"
                                            @click.prevent="currencyFormatProps.onShowEditField"
                                    >
                                        edit
                                    </i>
                                </template>

                                <template v-if="currencyFormatProps.showEditField">
                                    <select
                                            :value="currencyFormatProps.format"
                                            @change="currencyFormatProps.setFormat($event.target.value)"
                                    >
                                        <?php foreach (
                                            \Vehica\Field\Fields\Price\Currency::getFormats() as $vehicaFormatVal =>
                                            $vehicaFormat
                                        ) : ?>
                                            <option value="<?php echo esc_attr($vehicaFormatVal); ?>">
                                                <?php echo esc_html($vehicaFormat); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button
                                            class="vehica-flat-button vehica-flat-button--cyan"
                                            @click.prevent="currencyFormatProps.onSave"
                                    >
                                        <?php esc_html_e('Save', 'vehica-core'); ?>
                                    </button>

                                    <button
                                            class="vehica-flat-button vehica-flat-button--transparent"
                                            @click.prevent="currencyFormatProps.onCancel"
                                    >
                                        <i class="fas fa-times"></i>
                                    </button>
                                </template>
                            </div>
                        </vehica-set-currency-format>
                        <div
                                v-if="currenciesProps.currencies.length > 1"
                                class="vehica-table__cell vehica-table__cell--currency-actions"
                        >
                            <i @click.prevent="currenciesProps.delete(currency)"
                               class="fas fa-trash vehica-action"></i>
                        </div>
                    </div>
                </vehica-row>
            </div>

            <div>
                <a
                        class="vehica-doc-link vehica-doc-link--full-width"
                        target="_blank"
                        href="https://support.vehica.com/support/solutions/articles/101000377000">
                    <i class="fas fa-info-circle"></i>
                    <span><?php esc_html_e('Need more than 1 currency? Read more', 'vehica-core'); ?></span>
                </a>
            </div>

            <button class="vehica-button vehica-button--add-new mt-5"
                    @click.prevent="currenciesProps.create">
                <i class="fas fa-plus-circle"></i>
                <?php esc_html_e('Add new currency', 'vehica-core'); ?>
            </button>
        </div>
    </vehica-currencies>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="number-format">
                <?php esc_html_e('Number format', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">
            <div class="vehica-field">
                <div>
                    <?php esc_html_e('Decimal separator', 'vehica-core'); ?>
                </div>

                <div>
                    <input
                            type="text"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DECIMAL_SEPARATOR); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DECIMAL_SEPARATOR); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getDecimalSeparator()); ?>"
                    >
                </div>
            </div>

            <div class="vehica-field">
                <div>
                    <?php esc_html_e('Thousands separator', 'vehica-core'); ?>
                </div>

                <div>
                    <input
                            type="text"
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::THOUSANDS_SEPARATOR); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::THOUSANDS_SEPARATOR); ?>"
                            value="<?php echo esc_attr(vehicaApp('settings_config')->getThousandsSeparator()); ?>"
                    >
                </div>
            </div>

            <button data-hook="number-format" class="vehica-button vehica-hook mt-3">
                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</div>

<div class="vehica-section">
    <div class="vehica-section__left">
        <div class="vehica-section__left__inner">
            <h2 id="other">
                <?php esc_html_e('Other', 'vehica-core'); ?>
            </h2>
        </div>
    </div>

    <div class="vehica-section__right">
        <div class="vehica-section__right__inner">

            <div class="vehica-field">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PRETTY_URLS); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PRETTY_URLS); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('pretty_urls_enabled')) : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_PRETTY_URLS); ?>">
                    <?php esc_html_e('Pretty search result links - based on breadcrumbs e.g. Make > Model - /bmw/x7/. If you turn on this option slugs for ', 'vehica-core'); ?>
                    <strong><?php esc_html_e('single listing page and inventory page must be different', 'vehica-core'); ?></strong>
                    <?php esc_html_e('(Vehica Panel > Translate & Rename > Slugs).', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field">
                <div class="vehica-field__col-1">
                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EXCLUDE_FROM_SEARCH); ?>">
                        <?php esc_html_e('Exclude From Search Results', 'vehica-core'); ?>
                    </label>
                </div>

                <div class="vehica-field__col-2">
                    <select
                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EXCLUDE_FROM_SEARCH); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::EXCLUDE_FROM_SEARCH); ?>[]"
                            class="vehica-selectize"
                            multiple
                    >
                        <?php foreach (\Vehica\Model\Post\Field\Taxonomy\Taxonomy::getAllTermList() as $vehicaTerm) :
                            /* @var \Vehica\Model\Term\Term $vehicaTerm */
                            ?>
                            <option
                                    value="<?php echo esc_attr($vehicaTerm->getId()); ?>"
                                <?php if (in_array($vehicaTerm->getId(), vehicaApp('settings_config')->getExcludeFromSearch(), true)) : ?>
                                    selected
                                <?php endif; ?>
                            >
                                <?php echo esc_html($vehicaTerm->getName()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="margin-bottom:20px">
                    <?php esc_html_e('You can use it e.g. to not display "Sold" listings in the inventory', 'vehica-core'); ?>
                </div>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DELETE_IMAGES_WITH_CAR); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DELETE_IMAGES_WITH_CAR); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->deleteImagesWithCar())  : ?>
                        checked
                    <?php endif; ?>
                >

                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DELETE_IMAGES_WITH_CAR); ?>">
                    <?php esc_html_e('Remove listing images files when listing is deleted', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_IMPORTER); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_IMPORTER); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('hide_importer'))  : ?>
                        checked
                    <?php endif; ?>
                >
                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_IMPORTER); ?>">
                    <?php esc_html_e('Disable "Demo Importer"', 'vehica-core'); ?>
                </label>
            </div>

            <div class="vehica-field vehica-field--checkbox">
                <input
                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_HELP); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_HELP); ?>"
                        type="checkbox"
                        value="1"
                    <?php if (vehicaApp('settings_config')->hideHelp())  : ?>
                        checked
                    <?php endif; ?>
                >
                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::HIDE_HELP); ?>">
                    <?php esc_html_e('Disable "Search Help"', 'vehica-core'); ?>
                </label>
            </div>

            <button data-hook="other" class="vehica-button vehica-hook mt-4">
                <?php esc_html_e('Save all changes', 'vehica-core'); ?>
            </button>
        </div>
    </div>
</div>
