<?php
/* @var \Vehica\Widgets\Post\Single\CategoriesSinglePostWidget $vehicaCurrentWidget */
/* @var \Vehica\Model\Post\Post $vehicaPost */
global $vehicaCurrentWidget, $vehicaPost;

if (!$vehicaPost) {
    return;
}

$vehicaPostCategories = $vehicaPost->getCategories();
if ($vehicaPostCategories->isNotEmpty()) :?>
    <div class="vehica-post-field__category__list">
        <i class="far fa-folder-open"></i>
        <?php foreach ($vehicaPostCategories as $vehicaIndex => $vehicaCategory) :/* @var \Vehica\Model\Term\Term $vehicaCategory */
            if ($vehicaIndex) : ?>, <?php endif; ?><a
            href="<?php echo esc_url($vehicaCategory->getTermUrl()); ?>"
            title="<?php echo esc_attr($vehicaCategory->getName()); ?>"
            class="vehica-post-field__category__single"
            >
            <?php echo esc_html($vehicaCategory->getName()); ?></a><?php endforeach; ?>
    </div>
<?php
endif;