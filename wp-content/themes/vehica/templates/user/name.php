<?php
/* @var \Vehica\Widgets\User\NameUserWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\User\User $vehicaUser */
global $vehicaCurrentWidget, $vehicaUser;
?>
<<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?> class="vehica-user-name-profile">
<?php echo esc_html($vehicaUser->getName()); ?>
</<?php echo esc_html($vehicaCurrentWidget->getHtmlTag()); ?>>
