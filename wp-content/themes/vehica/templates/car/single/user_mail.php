<?php
/* @var \Vehica\Widgets\Car\Single\TermsSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCar;
if (!$vehicaCar) {
    return;
}

$vehicaUser = $vehicaCar->getUser();
if (!$vehicaUser) {
    return;
}
?>
<div class="vehica-user-email-v2">
    <a
            href="mailto:<?php echo esc_attr($vehicaUser->getMail()); ?>"
            title="<?php echo esc_attr($vehicaUser->getMail()); ?>"
    >
        <i class="fa fa-envelope"></i>
        <span>
            <?php echo esc_html($vehicaUser->getMail()); ?>
        </span>
    </a>
</div>
