<div class="vehica-panel vehica-app--styles vehica-app">
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
                            <?php esc_html_e('Google Maps', 'vehica-core'); ?>
                        </div>

                        <form
                                action="<?php echo esc_url(admin_url('admin-post.php?action=vehica_panel_save_maps')); ?>"
                                method="post"
                        >
                            <vehica-map-settings
                                    :initial-zoom="<?php echo esc_attr(vehicaApp('map_zoom')); ?>"
                                    initial-map-type="<?php echo esc_attr(vehicaApp('map_type')); ?>"
                                    :initial-position="<?php echo htmlspecialchars(json_encode(vehicaApp('map_initial_position'))); ?>"
                            >
                                <div slot-scope="mapSettings">
                                    <div class="vehica-section">
                                        <div class="vehica-section__left">
                                            <div class="vehica-section__left__inner">
                                                <h2 id="maps">
                                                    <?php esc_html_e('Google Maps API Key', 'vehica-core'); ?>
                                                </h2>

                                                <div>
                                                    <a
                                                            class="vehica-doc-link vehica-doc-link--full-width"
                                                            target="_blank"
                                                            href="https://support.vehica.com/support/solutions/articles/101000377056"
                                                    >
                                                        <i class="fas fa-info-circle"></i>
                                                        <span><?php esc_html_e('Click here to read how to configure Google Maps API Key', 'vehica-core'); ?></span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="vehica-section__right">
                                            <div class="vehica-section__right__inner">
                                                <div class="vehica-field">
                                                    <div class="vehica-field__col-1">
                                                        <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_API_KEY); ?>">
                                                            <?php esc_html_e('Google Maps API Key', 'vehica-core'); ?>
                                                        </label>
                                                    </div>

                                                    <div class="vehica-field__col-2">
                                                        <input
                                                                id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_API_KEY); ?>"
                                                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_API_KEY); ?>"
                                                                value="<?php echo esc_attr(vehicaApp('settings_config')->getGoogleMapsApiKey()); ?>"
                                                                type="text"
                                                                placeholder="e.g. AIXxXXXxXxx9xxXx1Xx76x-xXXxXXXXx7xXXxx"
                                                        >
                                                    </div>
                                                </div>

                                                <?php if (empty(vehicaApp('google_maps_api_key'))) : ?>
                                                    <button data-hook="maps" class="vehica-button vehica-hook mt-5">
                                                        <?php esc_html_e('Save', 'vehica-core'); ?>
                                                    </button>
                                                <?php else: ?>

                                                    <button data-hook="maps" class="vehica-button vehica-hook mt-5">
                                                        <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                    </button>

                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        <?php if (empty(vehicaApp('settings_config')->getGoogleMapsApiKey())) : ?>
                                            v-show="false"
                                        <?php endif; ?>
                                    >
                                        <div class="vehica-section">
                                            <div class="vehica-section__left">
                                                <div class="vehica-section__left__inner">
                                                    <h2 id="maps">
                                                        <?php esc_html_e('Setting', 'vehica-core'); ?>
                                                    </h2>
                                                </div>
                                            </div>

                                            <div class="vehica-section__right">
                                                <div class="vehica-section__right__inner">

                                                    <div class="vehica-field">
                                                        <div class="vehica-field__col-1">
                                                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_LANGUAGE); ?>">
                                                                <i class="material-icons">translate</i> <?php esc_html_e('Language', 'vehica-core'); ?>
                                                            </label>
                                                        </div>

                                                        <div class="vehica-field__col-2">
                                                            <select
                                                                    class="vehica-selectize"
                                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_LANGUAGE); ?>"
                                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_LANGUAGE); ?>"
                                                                    placeholder="<?php esc_html_e('Change map language', 'vehica-core'); ?>"
                                                            >
                                                                <option
                                                                        value=""
                                                                    <?php if (empty(vehicaApp('settings_config')->getGoogleMapsLanguage())) : ?>
                                                                        selected
                                                                    <?php endif; ?>
                                                                >
                                                                    <?php esc_html_e('Change map language', 'vehica-core'); ?>
                                                                </option>

                                                                <?php foreach (\Vehica\Model\Post\Field\LocationField::getLanguages() as $vehicaLanguageCode => $vehicaLanguage) : ?>
                                                                    <option
                                                                            value="<?php echo esc_html($vehicaLanguageCode); ?>"
                                                                        <?php if (vehicaApp('settings_config')->getGoogleMapsLanguage() === $vehicaLanguageCode) : ?>
                                                                            selected
                                                                        <?php endif; ?>
                                                                    >
                                                                        <?php echo esc_html($vehicaLanguage); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="vehica-field">
                                                        <div class="vehica-field__col-2">
                                                            <div v-if="!mapSettings.isGoogleLoaded">
                                                                <?php esc_html_e('Google Maps API is not loaded correctly', 'vehica-core'); ?>
                                                            </div>

                                                            <div v-if="mapSettings.isGoogleLoaded">
                                                                <input
                                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_INITIAL_POSITION_LAT); ?>"
                                                                        type="hidden"
                                                                        :value="mapSettings.position.lat"
                                                                >
                                                                <input
                                                                        name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_INITIAL_POSITION_LNG); ?>"
                                                                        type="hidden"
                                                                        :value="mapSettings.position.lng"
                                                                >

                                                                <label>
                                                                    <i class="material-icons">pin_drop</i> <?php esc_html_e('Google Map Address', 'vehica-core'); ?>
                                                                </label>

                                                                <input
                                                                        id="vehica-map-address"
                                                                        type="text"
                                                                        placeholder="<?php esc_attr_e('Search address', 'vehica-core'); ?>"
                                                                >

                                                                <div class="mb-2">
                                                                    <?php esc_html_e("If your address doesn't have a street number, or you're sure that you've entered the address correctly but the system still can't find it, you can pin your location directly on the map below.",
                                                                        'vehica-core'); ?>
                                                                </div>

                                                                <div id="vehica-map-position"
                                                                     class="vehica-map-initial"></div>

                                                                <div style="margin-top:15px;">
                                                                    <label>
                                                                        <i class="material-icons">zoom_in</i> <?php esc_html_e('Zoom Level', 'vehica-core'); ?>
                                                                        <input
                                                                                name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_INITIAL_ZOOM); ?>"
                                                                                type="text"
                                                                                :value="mapSettings.zoom"
                                                                                @input="mapSettings.setZoom($event.target.value)"
                                                                        >
                                                                    </label>

                                                                    <div>
                                                                        <?php esc_html_e('Default Level: 8. Higher number = bigger zoom.', 'vehica-core'); ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button data-hook="maps" class="vehica-button vehica-hook mt-5">
                                                        <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="vehica-section">
                                            <div class="vehica-section__left">
                                                <div class="vehica-section__left__inner">
                                                    <h2 id="maps">
                                                        <?php esc_html_e('Style Google Maps (snazzy maps)', 'vehica-core'); ?>
                                                    </h2>
                                                    <div>
                                                        <a
                                                                target="_blank"
                                                                href="https://support.vehica.com/support/solutions/articles/101000377057"
                                                                class="vehica-doc-link vehica-doc-link--full-width"
                                                        >
                                                            <i class="fas fa-info-circle"></i>
                                                            <span><?php esc_html_e('Click to read how to style Google Maps', 'vehica-core'); ?></span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="vehica-section__right">
                                                <div class="vehica-section__right__inner">

                                                    <div class="vehica-field">
                                                        <div class="vehica-field__col-1">
                                                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_CODE); ?>">
                                                                <strong><?php esc_html_e('Style Code', 'vehica-core'); ?></strong>
                                                            </label>
                                                        </div>


                                                        <div class="vehica-field__col-2">
                                                            <textarea
                                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_CODE); ?>"
                                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_CODE); ?>"
                                                                    cols="3"
                                                                    rows="6"
                                                            >
                                                                <?php echo wp_kses_post(vehicaApp('settings_config')->getGoogleMapsSnazzyCode()); ?>
                                                            </textarea>
                                                        </div>
                                                    </div>

                                                    <div class="vehica-field">
                                                        <div class="vehica-field__col-1">
                                                            <label for="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_LOCATION); ?>">
                                                                <span class="material-icons">layers</span> <?php esc_html_e('Where to use styled map?', 'vehica-core'); ?>
                                                            </label>
                                                        </div>

                                                        <div class="vehica-field__col-2">
                                                            <?php
                                                            $vehicaSnazzyLocations = vehicaApp('settings_config')->getGoogleMapsSnazzyLocation();
                                                            ?>

                                                            <select
                                                                    name="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_LOCATION); ?>[]"
                                                                    id="<?php echo esc_attr(\Vehica\Model\Post\Config\Setting::GOOGLE_MAPS_SNAZZY_LOCATION); ?>"
                                                                    class="vehica-selectize"
                                                                    placeholder="<?php esc_attr_e('Everywhere', 'vehica-core'); ?>"
                                                                    multiple
                                                            >

                                                                <option
                                                                        value="map_widget"
                                                                    <?php if (!empty($vehicaSnazzyLocations) && vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('map_widget')) : ?>
                                                                        selected
                                                                    <?php endif; ?>
                                                                >
                                                                    <?php esc_html_e('Map Widget', 'vehica-core'); ?>
                                                                </option>

                                                                <?php foreach (vehicaApp('location_fields') as $vehicaLocationField) : ?>
                                                                    <option
                                                                            value="<?php echo esc_attr($vehicaLocationField->getKey() . '_add'); ?>"
                                                                        <?php if (!empty($vehicaSnazzyLocations) && vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected($vehicaLocationField->getKey() . '_add')) : ?>
                                                                            selected
                                                                        <?php endif; ?>
                                                                    >
                                                                        <?php echo esc_html($vehicaLocationField->getName()) . ' ' . esc_html__(' - Submit Form', 'vehica-core'); ?>
                                                                    </option>

                                                                    <option
                                                                            value="<?php echo esc_attr($vehicaLocationField->getKey() . '_view'); ?>"
                                                                        <?php if (!empty($vehicaSnazzyLocations) && vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected($vehicaLocationField->getKey() . '_view')) : ?>
                                                                            selected
                                                                        <?php endif; ?>
                                                                    >
                                                                        <?php echo esc_html($vehicaLocationField->getName()) . ' ' . esc_html__(' - Vehicle Page', 'vehica-core'); ?>
                                                                    </option>
                                                                <?php endforeach; ?>

                                                                <option
                                                                        value="user_map"
                                                                    <?php if (!empty($vehicaSnazzyLocations) && vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('user_map')) : ?>
                                                                        selected
                                                                    <?php endif; ?>
                                                                >
                                                                    <?php esc_html_e('User Map', 'vehica-core'); ?>
                                                                </option>

                                                                <option
                                                                        value="user_map_add"
                                                                    <?php if (!empty($vehicaSnazzyLocations) && vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('user_map_add')) : ?>
                                                                        selected
                                                                    <?php endif; ?>
                                                                >
                                                                    <?php esc_html_e('User Set Location', 'vehica-core'); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <button data-hook="maps" class="vehica-button vehica-hook mt-5">
                                                        <?php esc_html_e('Save all changes', 'vehica-core'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </vehica-map-settings>
                        </form>
                    </template>
                </div>
            </div>
        </div>
    </vehica-hide-when-loaded>
</div>