<?php
if (!defined('ABSPATH')) {
    exit;
}
/* @var \Vehica\Model\User\User $vehicaUser */
?>
<div class="vehica-app">

    <table class="form-table">
        <tr>
            <th><?php esc_attr_e('Image', 'vehica-core'); ?></th>
            <td>
                <vehica-user-image
                        :initial-image-id="<?php echo esc_attr($vehicaUser->getImageId()); ?>"
                        title-text="<?php esc_attr_e('Seller image', 'vehica-core'); ?>"
                        button-text="<?php esc_attr_e('Use this', 'vehica-core'); ?>"
                        class="vehica-user-field__image"
                >
                    <div slot-scope="userImage">
                        <div v-if="userImage.hasImage">
                            <img :src="userImage.url" alt="" class="mb-5">
                            <i @click.prevent="userImage.onRemoveImage" class="fas fa-trash"></i>
                        </div>
                        <input
                                name="<?php echo esc_attr(\Vehica\Model\User\User::IMAGE); ?>"
                                type="hidden"
                                :value="userImage.imageId"
                        >
                        <button class="button button-primary" @click.prevent="userImage.onSetImage">
                            <?php esc_html_e('Set image', 'vehica-core'); ?>
                        </button>
                    </div>
                </vehica-user-image>
            </td>
        </tr>

        <?php if (vehicaApp('settings_config')->isUserConfirmationEnabled()) : ?>
            <tr>
                <th><?php esc_html_e('Confirmed email', 'vehica-core'); ?></th>

                <td>
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\User\User::CONFIRMED); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\User\User::CONFIRMED); ?>"
                            type="checkbox"
                            value="1"
                        <?php if ($vehicaUser->isConfirmed()) : ?>
                            checked
                        <?php endif; ?>
                    >
                </td>
            </tr>
        <?php endif; ?>

        <?php if (!vehicaApp('settings_config')->isUserAddressMap()) : ?>
            <tr>
                <th><?php esc_html_e('Location', 'vehica-core'); ?></th>
                <td>
                    <input
                            id="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_ADDRESS); ?>"
                            name="<?php echo esc_attr(\Vehica\Model\User\User::DISPLAY_ADDRESS); ?>"
                            type="text"
                            value="<?php echo esc_attr($vehicaUser->getDisplayAddress()); ?>"
                    >
                </td>
            </tr>
        <?php endif; ?>

        <?php if (!empty(vehicaApp('google_maps_api_key')) && vehicaApp('settings_config')->isUserAddressMap()) : ?>
            <tr>
                <th><?php esc_html_e('Location', 'vehica-core'); ?></th>
                <td>
                    <vehica-user-location
                            map-type="<?php echo esc_attr(vehicaApp('map_type')); ?>"
                            :initial-zoom="<?php echo esc_attr(vehicaApp('map_zoom')); ?>"
                            :initial-position="<?php echo htmlspecialchars(json_encode(vehicaApp('map_initial_position'))); ?>"
                            initial-address="<?php echo esc_attr($vehicaUser->getAddress()); ?>"
                            :snazzy="<?php echo esc_attr(vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('user_map_add') ? 'true' : 'false'); ?>"
                        <?php if ($vehicaUser->hasLocation()) : ?>
                            :initial-location="<?php echo htmlspecialchars(json_encode($vehicaUser->getLocation())); ?>"
                        <?php endif; ?>
                    >
                        <div slot-scope="userLocation">
                            <input
                                    id="vehica-user-address"
                                    type="text"
                                    @input="userLocation.setAddress($event.target.value)"
                                    :value="userLocation.address"
                                    name="<?php echo esc_attr(\Vehica\Model\User\User::ADDRESS); ?>"
                            >
                            <button class="vehica-user__map-reset-button" v-if="userLocation.showResetButton"
                                    @click.prevent="userLocation.onReset">
                                <span class="dashicons dashicons-no"></span>
                            </button>
                            <?php if (!empty(vehicaApp('google_maps_api_key'))) : ?>
                                <div class="mt-1 mb-5">
                                    <input
                                            @change="userLocation.onSyncAddress"
                                            :checked="userLocation.syncAddress"
                                            type="checkbox"
                                            class="vehica-user__map-click-checkbox"
                                    >
                                    <?php esc_html_e('Auto change address on click', 'vehica-core'); ?>s
                                </div>
                                <div id="vehica-user-map" class="vehica-user__map-size"></div>

                                <template v-if="userLocation.location !== false">
                                    <input
                                            name="<?php echo esc_attr(\Vehica\Model\User\User::LOCATION_LAT); ?>"
                                            :value="userLocation.location.lat"
                                            type="hidden"
                                    >
                                    <input
                                            name="<?php echo esc_attr(\Vehica\Model\User\User::LOCATION_LNG); ?>"
                                            :value="userLocation.location.lng"
                                            type="hidden"
                                    >
                                </template>
                            <?php endif; ?>
                        </div>
                    </vehica-user-location>
                </td>
            </tr>
        <?php endif; ?>

        <tr>
            <th><?php esc_html_e('Phone', 'vehica-core'); ?></th>
            <td>
                <input
                        id="<?php echo esc_attr(\Vehica\Model\User\User::PHONE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\User\User::PHONE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getPhone()); ?>"
                >
            </td>
        </tr>
        <tr>
            <th><?php esc_html_e('Job Title', 'vehica-core') ?></th>
            <td>
                <input
                        id="<?php echo esc_attr(\Vehica\Model\User\User::JOB_TITLE); ?>"
                        name="<?php echo esc_attr(\Vehica\Model\User\User::JOB_TITLE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getJobTitle()); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('Facebook profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::FACEBOOK_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getFacebookProfile()); ?>"
                        placeholder="<?php esc_attr_e('Facebook profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('Linkedin profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::LINKEDIN_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getLinkedinProfile()); ?>"
                        placeholder="<?php esc_attr_e('Linkedin profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('Instagram profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::INSTAGRAM_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getInstagramProfile()); ?>"
                        placeholder="<?php esc_attr_e('Instagram profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('Twitter profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::TWITTER_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getTwitterProfile()); ?>"
                        placeholder="<?php esc_attr_e('Twitter profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('TikTok profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::TIKTOK_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getTiktokProfile()); ?>"
                        placeholder="<?php esc_attr_e('TikTok profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
        <tr>
            <th>
                <?php esc_html_e('Telegram profile', 'vehica-core'); ?>
            </th>
            <td>
                <input
                        name="<?php echo esc_attr(\Vehica\Model\User\User::TELEGRAM_PROFILE); ?>"
                        type="text"
                        value="<?php echo esc_attr($vehicaUser->getTiktokProfile()); ?>"
                        placeholder="<?php esc_attr_e('Telegram profile url', 'vehica-core'); ?>"
                >
            </td>
        </tr>
    </table>
</div>