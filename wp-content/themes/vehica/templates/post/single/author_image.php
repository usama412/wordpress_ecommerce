<?php
/* @var \Vehica\Widgets\Post\Single\AuthorImageSinglePostWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
$vehicaUser = $vehicaCurrentWidget->getUser();

if (!$vehicaUser) {
    return;
}

if ($vehicaUser->hasImageUrl($vehicaCurrentWidget->getImageSize())): ?>
    <div <?php $vehicaCurrentWidget->print_render_attribute_string('image'); ?>>
        <img
                src="<?php echo esc_url($vehicaUser->getImageUrl($vehicaCurrentWidget->getImageSize())); ?>"
                alt="<?php echo esc_attr($vehicaUser->getName()); ?>"
        >
    </div>
<?php
endif;