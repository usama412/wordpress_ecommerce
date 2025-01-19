<?php
/* @var \Vehica\Widgets\General\ComingSoonGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-count-down">
    <div class="vehica-count-down__box">
        <div class="vehica-count-down__box__inner">
            <div class="vehica-count-down__number">
                <?php echo esc_html($vehicaCurrentWidget->getDays()); ?>
            </div>
            <div class="vehica-count-down__label">
                <?php echo esc_html(vehicaApp('days_string')) ?>
            </div>
        </div>
    </div>
    <div class="vehica-count-down__box">
        <div class="vehica-count-down__box__inner">
            <div class="vehica-count-down__number">
                <?php echo esc_html($vehicaCurrentWidget->getHours()); ?>
            </div>
            <div class="vehica-count-down__label">
                <?php echo esc_html(vehicaApp('hours_string')) ?>
            </div>
        </div>
    </div>
    <div class="vehica-count-down__box">
        <div class="vehica-count-down__box__inner">
            <div class="vehica-count-down__number">
                <?php echo esc_html($vehicaCurrentWidget->getMinutes()); ?>
            </div>
            <div class="vehica-count-down__label">
                <?php echo esc_html(vehicaApp('minutes_string')) ?>
            </div>
        </div>
    </div>
</div>