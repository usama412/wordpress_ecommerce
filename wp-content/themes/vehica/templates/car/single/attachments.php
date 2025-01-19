<?php
/* @var \Vehica\Widgets\Car\Single\AttachmentsSingleCarWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;

$vehicaAttachments = $vehicaCurrentWidget->getAttachments();
if ($vehicaAttachments->isEmpty()) {
    return;
}

if ($vehicaCurrentWidget->showLabel()) :?>
    <h3 class="vehica-section-label vehica-section-label--attachments">
        <?php echo esc_html($vehicaCurrentWidget->getLabel()); ?>
    </h3>
<?php endif; ?>

<div class="vehica-attachments">
    <?php foreach ($vehicaCurrentWidget->getAttachments() as $vehicaAttachment) :
        /* @var \Vehica\Model\Post\Attachment $vehicaAttachment */
        ?>
        <div class="vehica-attachment-single-wrapper">
            <a
                    class="vehica-attachment"
                    href="<?php echo esc_url($vehicaAttachment->getUrl()); ?>"
                    target="_blank"
            >
                <div class="vehica-attachment__icon">
                    <img
                            src="<?php echo esc_url($vehicaAttachment->getIconUrl()); ?>"
                            alt="<?php echo esc_attr($vehicaAttachment->getName()); ?>"
                    >
                </div>

                <div class="vehica-attachment__name">
                    <?php echo esc_attr($vehicaAttachment->getName()); ?>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
