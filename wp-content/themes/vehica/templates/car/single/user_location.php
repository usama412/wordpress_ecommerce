<?php
/* @var \Vehica\Widgets\Car\Single\LocationSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;

if (!$vehicaCar || !$vehicaCurrentWidget) {
    return;
}

$vehicaUser = $vehicaCar->getUser();
$vehicaUserLocation = $vehicaUser->getLocation();
if (empty($vehicaUserLocation)) {
    return;
}
?>
<div class="vehica-app">
    <div class="vehica-car-location">
        <?php if ($vehicaCurrentWidget->showLabel()) : ?>
            <h3 class="vehica-section-label"><?php echo esc_html($vehicaCurrentWidget->getLabel()); ?></h3>
        <?php endif; ?>

        <vehica-location
                map-id="vehica-car__location--<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
                :values="<?php echo htmlspecialchars(json_encode([['position' => $vehicaUserLocation]])); ?>"
                :zoom="<?php echo esc_attr($vehicaCurrentWidget->getZoom()); ?>"
                icon="<?php echo esc_url($vehicaCurrentWidget->getIcon()); ?>"
                map-type="<?php echo esc_attr(vehicaApp('map_type')); ?>"
                :snazzy="<?php echo esc_attr(vehicaApp('settings_config')->isGoogleMapsSnazzyLocationSelected('user_map') ? 'true' : 'false'); ?>"
        >
            <div
                    slot-scope="location"
                    id="vehica-car__location--<?php echo esc_attr($vehicaCurrentWidget->get_id()); ?>"
                    class="vehica-car__location"
            ></div>
        </vehica-location>
    </div>
</div>