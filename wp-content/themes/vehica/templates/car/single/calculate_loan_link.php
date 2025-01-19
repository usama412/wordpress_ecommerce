<?php
/* @var \Vehica\Widgets\Car\Single\CalculateLoanLinkSingleCarWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Car $vehicaCar */
global $vehicaCurrentWidget, $vehicaCar;
?>
<div class="vehica-calculate-loan-link-wrapper">
    <a
            class="vehica-calculate-loan-link"
            href="<?php echo esc_url($vehicaCar->getCalculateFinancingUrl()); ?>"
            title="<?php echo esc_attr(vehicaApp('calculate_financing_string')); ?>"
    >
        <?php echo esc_html(vehicaApp('calculate_financing_string')); ?>
    </a>
</div>
