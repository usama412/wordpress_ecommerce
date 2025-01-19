<?php
/* @var \Vehica\Widgets\General\SimpleMenuGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div <?php $vehicaCurrentWidget->print_render_attribute_string('menu'); ?>>
    <?php $vehicaCurrentWidget->displayMenu(); ?>
</div>
