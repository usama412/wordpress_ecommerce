<?php
/* @var \Vehica\Widgets\General\MapGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if (empty(vehicaApp('google_maps_api_key')) && is_page(1525)) {
    return;
}

if (empty(vehicaApp('google_maps_api_key'))) { ?>
    <div class="vehica-map__no-api-key">
        <div class="vehica-map__no-api-key__inner">
            <div class="vehica-map__no-api-key__icon">
                <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3><?php esc_html_e('You must add Google Maps API Key to display map', 'vehica'); ?></h3>
            <p><?php esc_html_e('You can do it in the /wp-admin/ > Vehica Panel > Google Maps', 'vehica'); ?></p>
        </div>
    </div>
    <?php
    return;
}
?>

<div class="vehica-app">
    <vehica-advanced-map
            :zoom="<?php echo esc_attr($vehicaCurrentWidget->getZoom()); ?>"
            address="<?php echo esc_attr($vehicaCurrentWidget->getAddress()); ?>"
            :lat="<?php echo esc_attr($vehicaCurrentWidget->getLat()); ?>"
            :lng="<?php echo esc_attr($vehicaCurrentWidget->getLng()); ?>"
            icon="<?php echo esc_url($vehicaCurrentWidget->getIcon()); ?>"
            :snazzy="<?php echo esc_attr(vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('map_widget') ? 'true' : 'false'); ?>"
            :show-info-window="<?php echo esc_attr($vehicaCurrentWidget->showInfoWindow() ? 'true' : 'false'); ?>"
            marker-type="<?php echo esc_attr($vehicaCurrentWidget->getMarkerType()); ?>"
        <?php if (\Elementor\Plugin::instance()->editor->is_edit_mode()) : ?>
            widget-id="<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
            :edit-mode="true"
        <?php endif; ?>
    >
        <div slot-scope="advancedMap">
            <div class="vehica-map vehica-map--marker-<?php echo esc_attr($vehicaCurrentWidget->getMarkerType()); ?>"></div>
            <?php if ($vehicaCurrentWidget->showInfoWindow()) : ?>
                <div class="vehica-info-window-wrapper">
                    <div class="vehica-info-window">
                        <?php if ($vehicaCurrentWidget->hasInfoWindowText()) : ?>
                            <div class="vehica-info-window__text">
                                <?php echo wp_kses_post($vehicaCurrentWidget->getInfoWindowText()); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </vehica-advanced-map>
</div>