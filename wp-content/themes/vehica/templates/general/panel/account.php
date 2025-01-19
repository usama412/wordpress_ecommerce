<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */

global $vehicaCurrentWidget;
$vehicaUser = vehicaApp('current_user');
if (!$vehicaUser instanceof \Vehica\Model\User\User) {
    return;
}
?>
<div class="vehica-container">
    <?php get_template_part('templates/general/panel/account_menu'); ?>

    <vehica-panel-change-details
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_save_account_details')); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_save_account')); ?>"
            in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
            success-string="<?php echo esc_attr(vehicaApp('success_string')); ?>"
            error-string="<?php echo esc_attr(vehicaApp('error_string')); ?>"
            initial-display-name="<?php echo esc_attr($vehicaUser->getName()); ?>"
            initial-display-address="<?php echo esc_attr($vehicaUser->getDisplayAddress()); ?>"
            initial-phone="<?php echo esc_attr($vehicaUser->getPhone()); ?>"
            initial-role="<?php echo esc_attr($vehicaUser->getFrontendUserRole()); ?>"
            initial-description="<?php echo esc_attr($vehicaUser->getDescription()); ?>"
            :initial-hide-phone="<?php echo esc_attr($vehicaUser->hidePhone() ? 'true' : 'false'); ?>"
            initial-address="<?php echo esc_attr($vehicaUser->getAddress()); ?>"
            :initial-whats-app="<?php echo esc_attr($vehicaUser->isWhatsAppActive() ? 'true' : 'false'); ?>"
        <?php if ($vehicaUser->getLocation()) : ?>
            :initial-location="<?php echo htmlspecialchars(json_encode($vehicaUser->getLocation())); ?>"
        <?php endif; ?>
    >
        <div slot-scope="props">
            <div class="vehica-panel-account">
                <div class="vehica-panel-account__details-wrapper">
                    <div class="vehica-panel-account__details">
                        <div class="vehica-panel-account__inner">
                            <h3 class="vehica-panel-account__title">
                                <?php echo esc_html(vehicaApp('your_contact_details_string')); ?>
                            </h3>

                            <form @submit.prevent="props.onChange">
                                <div class="vehica-grid">
                                    <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of2">
                                        <div class="vehica-panel-account-field">
                                            <label
                                                    class="vehica-panel-account-field__label"
                                                    for="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_NAME); ?>"
                                            >
                                                <?php echo esc_html(vehicaApp('name_string')); ?><span
                                                        class="vehica-text-primary">*</span>
                                            </label>

                                            <input
                                                    id="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_NAME); ?>"
                                                    class="vehica-panel-account-field__text-control"
                                                    type="text"
                                                    @input="props.setDisplayName($event.target.value)"
                                                    :value="props.displayName"
                                                    required
                                            >
                                        </div>
                                    </div>

                                    <?php if (vehicaApp('settings_config')->isUserAddressTextInput()) : ?>
                                        <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of2">
                                            <div class="vehica-panel-account-field">
                                                <label
                                                        for="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_ADDRESS); ?>"
                                                        class="vehica-panel-account-field__label"
                                                >
                                                    <?php echo esc_html(vehicaApp('location_string')); ?>
                                                </label>

                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_ADDRESS); ?>"
                                                        class="vehica-panel-account-field__text-control"
                                                        type="text"
                                                        @input="props.setDisplayAddress($event.target.value)"
                                                        :value="props.displayAddress"
                                                >
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($vehicaCurrentWidget->showPhoneNumberField()) : ?>
                                        <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of2">
                                            <div class="vehica-panel-account-field">
                                                <label
                                                        for="<?php echo esc_attr(\Vehica\Model\User\User::PHONE); ?>"
                                                        class="vehica-panel-account-field__label"
                                                >
                                                    <?php if ($vehicaCurrentWidget->isPhoneNumberFieldRequired()) : ?>
                                                        <?php echo esc_html(vehicaApp('phone_string')); ?><span
                                                                class="vehica-text-primary">*</span>
                                                    <?php else : ?>
                                                        <?php echo esc_html(vehicaApp('phone_string')); ?>
                                                    <?php endif; ?>
                                                </label>

                                                <input
                                                        id="<?php echo esc_attr(\Vehica\Model\User\User::PHONE); ?>"
                                                        class="vehica-panel-account-field__text-control"
                                                        type="text"
                                                        @input="props.setPhone($event.target.value)"
                                                        :value="props.phone"
                                                    <?php if ($vehicaCurrentWidget->isPhoneNumberFieldRequired()) : ?>
                                                        required
                                                    <?php endif; ?>
                                                >
                                            </div>

                                            <?php if (vehicaApp('settings_config')->isPanelHidePhoneAllowed()) : ?>
                                                <div class="vehica-checkbox vehica-checkbox--hide-phone">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\User\User::HIDE_PHONE); ?>"
                                                            type="checkbox"
                                                            :checked="props.hidePhone"
                                                            @change="props.setHidePhone"
                                                    >
                                                    <label
                                                            for="<?php echo esc_attr(\Vehica\Model\User\User::HIDE_PHONE); ?>"
                                                            class="vehica-panel-account-field__label"
                                                    >
                                                        <?php echo esc_html(vehicaApp('hide_phone_string')); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>

                                            <?php if ($vehicaCurrentWidget->showWhatsAppCheckbox()) : ?>
                                                <div class="vehica-checkbox">
                                                    <input
                                                            id="<?php echo esc_attr(\Vehica\Model\User\User::WHATS_APP); ?>"
                                                            type="checkbox"
                                                            :checked="props.whatsApp"
                                                            @change="props.setWhatsApp"
                                                    >
                                                    <label
                                                            for="<?php echo esc_attr(\Vehica\Model\User\User::WHATS_APP); ?>"
                                                            class="vehica-panel-account-field__label"
                                                    >
                                                        <?php echo esc_html(vehicaApp('whats_app_string')); ?>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of2">
                                        <div class="vehica-panel-account-field vehica-panel-account-field--email">
                                            <label class="vehica-panel-account-field__label" for="vehica-email">
                                                <?php echo esc_html(vehicaApp('email_string')); ?>
                                            </label>

                                            <input
                                                    id="vehica-email"
                                                    class="vehica-panel-account-field__text-control"
                                                    type="text"
                                                    value="<?php echo esc_attr($vehicaUser->getMail()); ?>"
                                                    disabled
                                            >
                                        </div>
                                    </div>

                                    <?php if (vehicaApp('show_user_roles')) : ?>
                                        <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of2">
                                            <div class="vehica-panel-account-field">
                                                <label
                                                        for="vehica-type"
                                                        class="vehica-panel-account-field__label"
                                                >
                                                    <?php echo esc_html(vehicaApp('type_string')); ?>
                                                </label>

                                                <div>
                                                    <input
                                                            name="<?php echo esc_attr(\Vehica\Model\User\User::FRONTEND_USER_ROLE); ?>"
                                                            type="hidden"
                                                            :value="props.role"
                                                    >

                                                    <v-select
                                                            label="name"
                                                            :reduce="option => option.key"
                                                            @input="props.setRole"
                                                            :value="props.role"
                                                            :options="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getUserRoles())); ?>"
                                                            :searchable="false"
                                                    ></v-select>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of1">
                                        <div class="vehica-panel-account-field">
                                            <label class="vehica-panel-account-field__label" for="vehica-description">
                                                <?php echo esc_html(vehicaApp('description_string')); ?>
                                            </label>

                                            <textarea
                                                    id="vehica-description"
                                                    class="vehica-panel-account-field__text-control"
                                                    type="text"
                                                    value="<?php echo esc_attr($vehicaUser->getDescription()); ?>"
                                                    @input="props.setDescription($event.target.value)"
                                                    :value="props.description"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <?php if (!empty(vehicaApp('google_maps_api_key')) && vehicaApp('settings_config')->isUserAddressMap()) : ?>
                                        <div class="vehica-grid__element--mobile-1of1 vehica-grid__element--tablet-1of1 vehica-grid__element--1of1">
                                            <vehica-set-user-location
                                                    :zoom="<?php echo esc_attr(vehicaApp('map_zoom')); ?>"
                                                    :initial-position="<?php echo htmlspecialchars(json_encode(vehicaApp('map_initial_position'))); ?>"
                                                    map-type="<?php echo esc_attr(vehicaApp('map_type')); ?>"
                                                    :location="props.location"
                                                    :address="props.address"
                                                    :snazzy="<?php echo esc_attr(vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('user_map_add') ? 'true' : 'false'); ?>"
                                            >
                                                <div slot-scope="userLocation"
                                                     class="vehica-panel-account-field vehica-panel-account-field--location-google">
                                                    <label class="vehica-panel-account-field__label"
                                                           for="vehica-address">
                                                        <?php echo esc_html(vehicaApp('location_string')); ?>
                                                    </label>

                                                    <input
                                                            id="vehica-user-address"
                                                            type="text"
                                                            placeholder="<?php echo esc_attr(vehicaApp('enter_location_string')); ?>"
                                                            :value="userLocation.address"
                                                    >

                                                    <div class="vehica-checkbox">
                                                        <input
                                                                id="vehica-checkbox-map-autocomplete"
                                                                type="checkbox"
                                                                @change="userLocation.setMarkerChangeAddress"
                                                                :checked="userLocation.markerChangeAddress"
                                                        >

                                                        <label for="vehica-checkbox-map-autocomplete">
                                                            <?php echo esc_html(vehicaApp('autocomplete_map_string')); ?>
                                                        </label>
                                                    </div>

                                                    <div id="vehica-user-map"></div>
                                                </div>
                                            </vehica-set-user-location>
                                        </div>
                                    <?php endif; ?>

                                </div>

                                <div class="vehica-panel-account__button-save">
                                    <button
                                            class="vehica-button vehica-button--with-progress-animation"
                                            :class="{'vehica-button--with-progress-animation--active': props.inProgress}"
                                            :disabled="props.inProgress"
                                    >
                                        <span><?php echo esc_html(vehicaApp('save_string')); ?></span>

                                        <template>
                                            <svg
                                                    v-if="props.inProgress"
                                                    width="120"
                                                    height="30"
                                                    wviewBox="0 0 120 30"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="#fff"
                                            >
                                                <circle cx="15" cy="15" r="15">
                                                    <animate attributeName="r" from="15" to="15"
                                                             begin="0s" dur="0.8s"
                                                             values="15;9;15" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                    <animate attributeName="fill-opacity" from="1" to="1"
                                                             begin="0s" dur="0.8s"
                                                             values="1;.5;1" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                </circle>
                                                <circle cx="60" cy="15" r="9" fill-opacity="0.3">
                                                    <animate attributeName="r" from="9" to="9"
                                                             begin="0s" dur="0.8s"
                                                             values="9;15;9" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                    <animate attributeName="fill-opacity" from="0.5" to="0.5"
                                                             begin="0s" dur="0.8s"
                                                             values=".5;1;.5" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                </circle>
                                                <circle cx="105" cy="15" r="15">
                                                    <animate attributeName="r" from="15" to="15"
                                                             begin="0s" dur="0.8s"
                                                             values="15;9;15" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                    <animate attributeName="fill-opacity" from="1" to="1"
                                                             begin="0s" dur="0.8s"
                                                             values="1;.5;1" calcMode="linear"
                                                             repeatCount="indefinite"/>
                                                </circle>
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="vehica-panel-account__image-wrapper">
                        <h3 class="vehica-panel-account__title">
                            <?php echo esc_html(vehicaApp('your_photo_string')); ?>
                        </h3>

                        <vehica-panel-user-image
                            <?php if ($vehicaUser->hasImage()) : ?>
                                :initial-image="<?php echo esc_attr(json_encode($vehicaCurrentWidget->getUserImageData($vehicaUser))); ?>"
                            <?php endif; ?>
                                delete-request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_delete_account_image')); ?>"
                                delete-nonce="<?php echo esc_attr(wp_create_nonce('vehica_delete_account_image')); ?>"
                                upload-nonce="<?php echo esc_attr(wp_create_nonce('vehica_save_account_image')); ?>"
                                delete-message-string="<?php echo esc_attr(vehicaApp('are_you_sure_string')); ?>"
                                delete-success-string="<?php echo esc_attr(vehicaApp('deleted_string')); ?>"
                                success-string="<?php echo esc_attr(vehicaApp('success_string')); ?>"
                                confirm-string="<?php echo esc_attr(vehicaApp('confirm_string')); ?>"
                                cancel-string="<?php echo esc_attr(vehicaApp('cancel_string')); ?>"
                                in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
                        >
                            <div slot-scope="props">
                                <?php if ($vehicaUser->hasSocialImage()) : ?>
                                    <div>
                                        <div class="vehica-panel-account__image vehica-panel-account__image--selected">
                                            <div class="vehica-panel-account__selected-image">
                                                <img src="<?php echo esc_url($vehicaUser->getSocialImage()); ?>">
                                            </div>
                                        </div>

                                        <div
                                                @click.prevent="props.onDelete"
                                                class="vehica-panel-account__image-label"
                                        >
                                            <?php echo esc_html(vehicaApp('remove_profile_photo_string')); ?>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div
                                            class="vehica-panel-account__image"
                                            :class="{'vehica-panel-account__image--selected': props.image}"
                                            @click.prevent="props.onOpen"
                                    >
                                        <template>
                                            <div class="vehica-panel-account__selected-image" v-if="props.image">
                                                <img :src="props.image.url">
                                            </div>

                                            <div v-if="!props.image">
                                                <vehica-dropzone
                                                        v-show="false"
                                                        id="vehica-panel-user-image"
                                                        :options="<?php echo htmlspecialchars(json_encode($vehicaCurrentWidget->getUserImageDropZoneConfig())); ?>"
                                                        @vdropzone-sending="props.onSending"
                                                        @vdropzone-success="props.onSuccess"
                                                        @vdropzone-error="props.onError"
                                                        @vdropzone-canceled="props.onError"
                                                >
                                                </vehica-dropzone>
                                            </div>
                                        </template>
                                    </div>

                                    <template>
                                        <div
                                                v-if="!props.image"
                                                @click.prevent="props.onOpen"
                                                class="vehica-panel-account__image-label"
                                        >
                                            <?php echo esc_html(vehicaApp('upload_profile_photo_string')); ?>
                                        </div>

                                        <div
                                                v-if="props.image"
                                                @click.prevent="props.onDelete"
                                                class="vehica-panel-account__image-label"
                                        >
                                            <?php echo esc_html(vehicaApp('remove_profile_photo_string')); ?>
                                        </div>
                                    </template>
                                <?php endif; ?>
                            </div>
                        </vehica-panel-user-image>
                    </div>
                </div>
            </div>
        </div>
    </vehica-panel-change-details>

    <vehica-delete-user
            request-url="<?php echo esc_url(admin_url('admin-post.php?action=vehica_delete_account')); ?>"
            redirect-url="<?php echo esc_url(vehicaApp('settings_config')->getLoginPageUrl()); ?>"
            vehica-nonce="<?php echo esc_attr(wp_create_nonce('vehica_delete_account')); ?>"
            message-string="<?php echo esc_attr(vehicaApp('are_you_sure_string')); ?>"
            confirm-string="<?php echo esc_attr(vehicaApp('confirm_string')); ?>"
            cancel-string="<?php echo esc_attr(vehicaApp('cancel_string')); ?>"
            success-string="<?php echo esc_attr(vehicaApp('deleted_string')); ?>"
            in-progress-string="<?php echo esc_attr(vehicaApp('in_progress_string')); ?>"
    >
        <div class="vehica-panel-account__delete" slot-scope="props" @click.prevent="props.onDelete">
            <?php echo esc_html(vehicaApp('delete_account_string')); ?>
        </div>
    </vehica-delete-user>
</div>