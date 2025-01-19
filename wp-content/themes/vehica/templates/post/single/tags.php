<?php
/* @var \Vehica\Widgets\Post\Single\TagsSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}

$vehicaPostTags = $vehicaPost->getTags();
if ($vehicaPostTags->isNotEmpty()) :?>
    <div class="vehica-post-field__tags">
        <span class="vehica-post-field__tags__icon">
            <i class="fas fa-tags"></i>
        </span>
        <?php foreach ($vehicaPostTags as $vehicaTag) :/* @var \Vehica\Model\Term\Term $vehicaTag */ ?>
            <a
                    href="<?php echo esc_url($vehicaTag->getTermUrl()); ?>"
                    title="<?php echo esc_attr($vehicaTag->getName()); ?>"
                    class="vehica-post-field__tags__single"
            ><?php echo esc_html($vehicaTag->getName()); ?></a>
        <?php endforeach; ?>
    </div>
<?php endif;