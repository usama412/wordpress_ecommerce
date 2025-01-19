<?php
/* @var \Vehica\Widgets\General\EmailGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

if (!empty(vehicaApp('email'))) : ?>
    <div class="vehica-email">
        <a href="mailto:<?php echo esc_attr(vehicaApp('email')); ?>">
            <?php if ($vehicaCurrentWidget->showIcon()) : ?>
                <i class="fa fa-envelope"></i>
            <?php endif; ?>

            <?php echo esc_html(vehicaApp('email')); ?>
        </a>
    </div>
<?php
endif;