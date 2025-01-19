<?php
/* @var \Vehica\Widgets\Post\Single\ImageSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}

if ($vehicaPost->hasImageUrl($vehicaCurrentWidget->getImageSize())) :?>
    <div <?php $vehicaCurrentWidget->print_render_attribute_string('image'); ?>>
        <img
                src="<?php echo esc_url($vehicaPost->getImageUrl($vehicaCurrentWidget->getImageSize())); ?>"
                alt="<?php echo esc_attr($vehicaPost->getName()); ?>"
        >
    </div>
<?php
endif;