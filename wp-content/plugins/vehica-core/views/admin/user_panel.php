<div class="vehica-panel vehica-app vehica-app--styles">
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
                            <?php esc_html_e('User Panel', 'vehica-core'); ?>
                        </div>

                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_save_user_panel')); ?>"
                                method="post"
                        >
                            <div v-scroll-spy="{time: 1000}">
                                <div class="vehica-section">
                                    <div class="vehica-section__left">
                                        <div class="vehica-section__left__inner">
                                            <h2 id="user-panel">
                                                <?php esc_html_e('Basic', 'vehica-core'); ?>
                                            </h2>
                                        </div>
                                    </div>

                                    <div class="vehica-section__right">
                                        <div class="vehica-section__right__inner">
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
                                                    <?php esc_html_e(' Registration - enable user registration', 'vehica-core'); ?>
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
                                                    <span><?php esc_html_e('Read more', 'vehica-core'); ?></span>
                                                </a>
                                            </div>

<!--                                            <div class="vehica-field">-->
<!--                                                <div class="vehica-field__col-1">-->
<!--                                                    <label for="--><?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--">-->
<!--                                                        --><?php //esc_html_e('Listing Owner Contact Type', 'vehica-core'); ?>
<!--                                                    </label>-->
<!--                                                </div>-->
<!---->
<!--                                                <div class="vehica-field__col-2">-->
<!--                                                    <select-->
<!--                                                            class="vehica-selectize"-->
<!--                                                            name="--><?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--"-->
<!--                                                            id="--><?php //echo esc_attr(\Vehica\Model\Post\Config\Setting::CONTACT_OWNER_TYPE); ?><!--"-->
<!--                                                    >-->
<!--                                                        <option-->
<!--                                                                value="messages"-->
<!--                                                            --><?php //if (vehicaApp('settings_config')->getContactOwnerType() === 'messages') : ?>
<!--                                                                selected-->
<!--                                                            --><?php //endif; ?>
<!--                                                        >-->
<!--                                                            --><?php //esc_html_e('Private Message (make sure you activated it above)', 'vehica-core'); ?>
<!--                                                        </option>-->
<!---->
<!--                                                        --><?php //foreach (vehicaApp('contact_forms_list') as $vehicaKey => $vehicaLabel) : ?>
<!--                                                            <option-->
<!--                                                                    value="--><?php //echo esc_attr($vehicaKey); ?><!--"-->
<!--                                                                --><?php //if (vehicaApp('settings_config')->getContactOwnerType() === $vehicaKey) : ?>
<!--                                                                    selected-->
<!--                                                                --><?php //endif; ?>
<!--                                                            >-->
<!--                                                                --><?php //echo esc_html($vehicaLabel); ?>
<!--                                                            </option>-->
<!--                                                        --><?php //endforeach; ?>
<!--                                                    </select>-->
<!--                                                </div>-->
<!--                                            </div>-->

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
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::APPROVE_LISTING_AFTER_EDIT); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::APPROVE_LISTING_AFTER_EDIT); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->listingAfterEditMustBeApproved()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::APPROVE_LISTING_AFTER_EDIT); ?>">
                                                    <?php esc_html_e('The approved listing must be re-approved when the user edits it', 'vehica-core'); ?>
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

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PHONE_NUMBER); ?>">
                                                        <?php esc_html_e('Phone Number', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PHONE_NUMBER); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_PHONE_NUMBER); ?>"
                                                            class="vehica-selectize"
                                                    >
                                                        <option
                                                                value="optional_show"
                                                            <?php if (vehicaApp('settings_config')->isPanelPhoneNumberSetting('optional_show')) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Optional + show on the register form', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="optional_hide"
                                                            <?php if (vehicaApp('settings_config')->isPanelPhoneNumberSetting('optional_hide')) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Optional + hide on the register form', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="required"
                                                            <?php if (vehicaApp('settings_config')->isPanelPhoneNumberSetting('required')) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Required', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="disable"
                                                            <?php if (vehicaApp('settings_config')->isPanelPhoneNumberSetting('disable')) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Disable', 'vehica-core'); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_ALLOW_HIDE_PHONE); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_ALLOW_HIDE_PHONE); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isPanelHidePhoneAllowed()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_ALLOW_HIDE_PHONE); ?>">
                                                    <?php esc_html_e('Allow users to hide phone number', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_WHATS_APP); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_WHATS_APP); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isWhatsAppEnabled()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_WHATS_APP); ?>">
                                                    <?php esc_html_e('"Chat via WhatsApp" module', 'vehica-core'); ?>
                                                </label>
                                                <a
                                                        class="vehica-doc-link vehica-doc-link--full-width"
                                                        target="_blank"
                                                        style="margin: 5px 0 0 10px;"
                                                        href="https://support.vehica.com/support/solutions/articles/101000376997">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span><?php esc_html_e('Read more', 'vehica-core'); ?></span>
                                                </a>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ADDRESS_TYPE); ?>">
                                                        <?php esc_html_e('User Location Type', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ADDRESS_TYPE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ADDRESS_TYPE); ?>"
                                                            class="vehica-selectize"
                                                    >
                                                        <option
                                                                value="text_input"
                                                            <?php if (vehicaApp('settings_config')->isUserAddressTextInput()) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Text Input', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="map"
                                                            <?php if (vehicaApp('settings_config')->isUserAddressMap()) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Google Maps', 'vehica-core'); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ROLE_MODE); ?>">
                                                        <?php esc_html_e('Registration - User Roles', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <?php
                                                    $vehicaUserRoleMode = vehicaApp('settings_config')->getUserRoleMode();

                                                    ?>
                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ROLE_MODE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::USER_ROLE_MODE); ?>"
                                                            class="vehica-selectize"
                                                    >
                                                        <option
                                                                value="enabled"
                                                            <?php if ($vehicaUserRoleMode === 'enabled') : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('2 User Roles (Private and Business)', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="hidden_private"
                                                            <?php if ($vehicaUserRoleMode === 'hidden_private') : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('1 user role only - Private Seller (no profile page)', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="hidden_business"
                                                            <?php if ($vehicaUserRoleMode === 'hidden_business') : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('1 User Role only - Business Seller (with profile pages)', 'vehica-core'); ?>
                                                        </option>
                                                    </select>
                                                    <div style="margin-bottom:10px; font-weight:700;">
                                                        <?php esc_html_e('You can rename "Private Seller" and "Business Seller" to any other name in the:', 'vehica-core'); ?>
                                                        <a href="<?php echo esc_url(admin_url('admin.php?page=vehica_panel_rename_and_translate')); ?>"
                                                           class="vehica-doc-link-solo">
                                                            <span><?php esc_html_e('Translate & Rename Module', 'vehica-core'); ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_CARD_FEATURES); ?>">
                                                        <?php esc_html_e('Panel Card - Fields', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_CARD_FEATURES); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::PANEL_CARD_FEATURES); ?>[]"
                                                            class="vehica-selectize"
                                                            multiple
                                                    >
                                                        <?php foreach (vehicaApp('simple_text_car_fields') as $vehicaSimpleTextAttribute) :
                                                            /* @var \Vehica\Attribute\SimpleTextAttribute $vehicaSimpleTextAttribute */
                                                            ?>
                                                            <option
                                                                    value="<?php echo esc_attr($vehicaSimpleTextAttribute->getId()); ?>"
                                                                <?php if (vehicaApp('settings_config')->isPanelCardFeature($vehicaSimpleTextAttribute->getId()))  : ?>
                                                                    selected
                                                                <?php endif; ?>
                                                            >
                                                                <?php echo esc_html($vehicaSimpleTextAttribute->getName()); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_NUMBER); ?>">
                                                        <?php esc_html_e('Maximum number of images to upload', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_NUMBER); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_NUMBER); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getMaxImageNumber()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_FILE_SIZE); ?>">
                                                        <?php esc_html_e('Maximum upload image size (MB)', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_FILE_SIZE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_IMAGE_FILE_SIZE); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getMaxImageFileSize()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_NUMBER); ?>">
                                                        <?php esc_html_e('Maximum number of attachments to upload', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_NUMBER); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_NUMBER); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getMaxImageNumber()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_FILE_SIZE); ?>">
                                                        <?php esc_html_e('Maximum upload attachment size (MB)', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_FILE_SIZE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::MAX_ATTACHMENT_FILE_SIZE); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getMaxImageFileSize()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REDIRECT_AFTER_LISTING_CREATED); ?>">
                                                        <?php esc_html_e('Redirect After Listing Created', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <?php
                                                    $vehicaRedirectAfterListingCreatedPageId = vehicaApp('settings_config')->getRedirectAfterListingCreatedPageId();
                                                    ?>

                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REDIRECT_AFTER_LISTING_CREATED); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REDIRECT_AFTER_LISTING_CREATED); ?>"
                                                            class="vehica-selectize"
                                                    >
                                                        <option
                                                                value="0"
                                                            <?php if (empty($vehicaRedirectAfterListingCreatedPageId)) : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Default', 'vehica-core'); ?>
                                                        </option>

                                                        <?php foreach (vehicaApp('pages') as $vehicaPage) :
                                                            /* @var \Vehica\Model\Post\Page $vehicaPage */
                                                            ?>
                                                            <option
                                                                    value="<?php echo esc_attr($vehicaPage->getId()); ?>"
                                                                <?php if ($vehicaRedirectAfterListingCreatedPageId === $vehicaPage->getId()) : ?>
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
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DESCRIPTION_TYPE); ?>">
                                                        <?php esc_html_e('Description (Textarea) - Style Bar', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <select
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DESCRIPTION_TYPE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::DESCRIPTION_TYPE); ?>"
                                                            class="vehica-selectize"
                                                    >
                                                        <option
                                                                value="rich"
                                                            <?php if (vehicaApp('settings_config')->getDescriptionType() === 'rich') : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Enable', 'vehica-core'); ?>
                                                        </option>

                                                        <option
                                                                value="regular"
                                                            <?php if (vehicaApp('settings_config')->getDescriptionType() === 'regular') : ?>
                                                                selected
                                                            <?php endif; ?>
                                                        >
                                                            <?php esc_html_e('Disable', 'vehica-core'); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REQUIRED_DESCRIPTION); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REQUIRED_DESCRIPTION); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isDescriptionRequired()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::REQUIRED_DESCRIPTION); ?>">
                                                    <?php esc_html_e('Description required', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::POLICY_LABEL); ?>">
                                                        <?php esc_html_e('Privacy Policy Text', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                     <textarea
                                                             id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::POLICY_LABEL); ?>"
                                                             name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::POLICY_LABEL); ?>"
                                                             cols="3"
                                                             rows="3"
                                                     ><?php echo wp_kses_post(vehicaApp('policy_label')); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GALLERY_FIELD_TIP); ?>">
                                                        <?php esc_html_e('Gallery Field - Notice', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                     <textarea
                                                             id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GALLERY_FIELD_TIP); ?>"
                                                             name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GALLERY_FIELD_TIP); ?>"
                                                             cols="3"
                                                             rows="3"
                                                     ><?php echo wp_kses_post(vehicaApp('settings_config')->getGalleryFieldTip()); ?></textarea>
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ATTACHMENTS_FIELD_TIP); ?>">
                                                        <?php esc_html_e('Attachment Field - Notice', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                     <textarea
                                                             id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ATTACHMENTS_FIELD_TIP); ?>"
                                                             name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ATTACHMENTS_FIELD_TIP); ?>"
                                                             cols="3"
                                                             rows="3"
                                                     ><?php echo wp_kses_post(vehicaApp('settings_config')->getAttachmentsFieldTip()); ?></textarea>
                                                </div>
                                            </div>

                                            <div>
                                                <button data-hook="public-information"
                                                        class="vehica-button vehica-hook">
                                                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-section">
                                    <div class="vehica-section__left">
                                        <div class="vehica-section__left__inner">
                                            <h2>
                                                <?php esc_html_e('Google reCAPTCHA v3 ', 'vehica-core'); ?>
                                                <br>
                                                <?php esc_html_e('(Invisible reCAPTCHA)', 'vehica-core'); ?>
                                            </h2>
                                            <div>
                                                <a
                                                        class="vehica-doc-link vehica-doc-link--full-width"
                                                        target="_blank"
                                                        style="margin: 5px 0 20px 0;"
                                                        href="https://www.google.com/recaptcha/admin/create">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span><?php esc_html_e('Create new reCAPTCHA v3 here', 'vehica-core'); ?></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vehica-section__right">
                                        <div class="vehica-section__right__inner">
                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_RECAPTCHA); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_RECAPTCHA); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isRecaptchaEnabled()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_RECAPTCHA); ?>">
                                                    <?php esc_html_e('Enable reCAPTCHA', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SITE); ?>">
                                                        <?php esc_html_e('Site Key', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SITE); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SITE); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getRecaptchaSite()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SECRET); ?>">
                                                        <?php esc_html_e('Secret Key', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SECRET); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::RECAPTCHA_SECRET); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getRecaptchaSecret()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <button data-hook="public-information"
                                                        class="vehica-button vehica-hook">
                                                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-section">
                                    <div class="vehica-section__left">
                                        <div class="vehica-section__left__inner">
                                            <h2>
                                                <?php esc_html_e('Facebook', 'vehica-core'); ?>
                                            </h2>
                                            <div>
                                                <a target="_blank"
                                                   href="https://support.vehica.com/support/solutions/articles/101000377052"
                                                   class="vehica-doc-link vehica-doc-link--full-width"
                                                   style="margin: 5px 0px 20px;"><i class="fas fa-info-circle"></i>
                                                    <span><?php esc_html_e('How to configure Facebook Login? ', 'vehica-core'); ?></span></a>
                                                <br>
                                                <strong><u><?php esc_html_e('OAuth Redirect URI ', 'vehica-core'); ?></u></strong><br>
                                                <span style="font-size:10px;"><?php echo esc_url(site_url() . '/social-auth/facebook/'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vehica-section__right">
                                        <div class="vehica-section__right__inner">

                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FACEBOOK_AUTH); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FACEBOOK_AUTH); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isFacebookAuthEnabled()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_FACEBOOK_AUTH); ?>">
                                                    <?php esc_html_e('Enable Facebook Auth', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_ID); ?>">
                                                        <?php esc_html_e('App ID', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_ID); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_ID); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookAppId()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_SECRET); ?>">
                                                        <?php esc_html_e('App Secret', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_SECRET); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::FACEBOOK_APP_SECRET); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getFacebookAppSecret()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <button data-hook="public-information"
                                                        class="vehica-button vehica-hook">
                                                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="vehica-section">
                                    <div class="vehica-section__left">
                                        <div class="vehica-section__left__inner">
                                            <h2>
                                                <?php esc_html_e('Google Sign-In', 'vehica-core'); ?>
                                            </h2>
                                            <div>
                                                <a target="_blank"
                                                   href="https://support.vehica.com/support/solutions/articles/101000377051"
                                                   class="vehica-doc-link vehica-doc-link--full-width"
                                                   style="margin: 5px 0px 20px;"><i class="fas fa-info-circle"></i>
                                                    <span><?php esc_html_e('How to configure Google Login?', 'vehica-core'); ?></span></a>
                                                <br>
                                                <strong><u><?php esc_html_e('OAuth Redirect URI ', 'vehica-core'); ?></u></strong><br>
                                                <span style="font-size:10px;"><?php echo esc_url(site_url() . '/social-auth/google/'); ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="vehica-section__right">
                                        <div class="vehica-section__right__inner">


                                            <div class="vehica-field">
                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_GOOGLE_AUTH); ?>"
                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_GOOGLE_AUTH); ?>"
                                                        type="checkbox"
                                                        value="1"
                                                    <?php if (vehicaApp('settings_config')->isGoogleAuthEnabled()) : ?>
                                                        checked
                                                    <?php endif; ?>
                                                >

                                                <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::ENABLE_GOOGLE_AUTH); ?>">
                                                    <?php esc_html_e('Enable Google Auth', 'vehica-core'); ?>
                                                </label>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_ID); ?>">
                                                        <?php esc_html_e('Client ID', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_ID); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_ID); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getGoogleAuthClientId()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div class="vehica-field">
                                                <div class="vehica-field__col-1">
                                                    <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_SECRET); ?>">
                                                        <?php esc_html_e('Client Secret', 'vehica-core'); ?>
                                                    </label>
                                                </div>

                                                <div class="vehica-field__col-2">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_SECRET); ?>"
                                                            name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_AUTH_CLIENT_SECRET); ?>"
                                                            type="text"
                                                            value="<?php echo esc_attr(vehicaApp('settings_config')->getGoogleAuthClientSecret()); ?>"
                                                    >
                                                </div>
                                            </div>

                                            <div>
                                                <button data-hook="public-information"
                                                        class="vehica-button vehica-hook">
                                                    <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </vehica-hide-when-loaded>
</div>