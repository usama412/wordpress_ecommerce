<?php
/* @var \Vehica\Widgets\General\LogoGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaLogoUrl = $vehicaCurrentWidget->getLogoUrl();
if (!$vehicaLogoUrl) {
    return;
}
?>
<div class="vehica-logo-widget">
    <a
            href="<?php echo esc_url(get_home_url()); ?>"
            title="<?php echo esc_attr(get_bloginfo('name')); ?>"
    >
        <img
                src="<?php echo esc_url($vehicaLogoUrl); ?>"
                alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
        >
    </a>
</div>