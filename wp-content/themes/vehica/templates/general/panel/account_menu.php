<?php
/* @var \Vehica\Widgets\General\PanelGeneralWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<div class="vehica-panel-list__top">
    <div class="vehica-panel-list__statuses-wrapper">
        <div class="vehica-panel-list__statuses">
            <a
                <?php if ($vehicaCurrentWidget->isAccountPage()) : ?>
                    class="vehica-panel-list__status vehica-panel-list__status--active"
                <?php else : ?>
                    class="vehica-panel-list__status"
                <?php endif; ?>
                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountPageUrl()); ?>"
            >
                <?php echo esc_html(vehicaApp('account_details_string')); ?>
            </a>

            <?php if (vehicaApp('current_user')->isRegularRegisterSource()): ?>
                <a
                    <?php if ($vehicaCurrentWidget->isAccountChangePasswordPage()) : ?>
                        class="vehica-panel-list__status vehica-panel-list__status--active"
                    <?php else : ?>
                        class="vehica-panel-list__status"
                    <?php endif; ?>
                        href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountChangePasswordPageUrl()); ?>"
                >
                    <?php echo esc_html(vehicaApp('change_password_string')); ?>
                </a>
            <?php endif; ?>

            <a
                <?php if ($vehicaCurrentWidget->isAccountSocialPage()) : ?>
                    class="vehica-panel-list__status vehica-panel-list__status--active"
                <?php else : ?>
                    class="vehica-panel-list__status"
                <?php endif; ?>
                    href="<?php echo esc_url(\Vehica\Widgets\General\PanelGeneralWidget::getAccountSocialPageUrl()); ?>"
            >
                <?php echo esc_html(vehicaApp('social_media_string')); ?>
            </a>
        </div>
    </div>
</div>