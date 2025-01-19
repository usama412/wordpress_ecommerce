<?php
/* @var \Vehica\Widgets\Post\Archive\NamePostArchiveWidget $vehicaCurrentWidget */
global $vehicaCurrentWidget;
?>
<h1 class="vehica-blog-title">
    <?php if (is_tag()) : ?>
        <?php echo esc_html(vehicaApp('tagged_in_string')); ?> <?php single_tag_title(); ?>
    <?php elseif (is_category()) : ?>
        <?php single_cat_title(); ?>
    <?php elseif ($vehicaCurrentWidget->isSearch()) : ?>
        <?php echo esc_html(vehicaApp('search_results_for_string')) . ' ' . esc_html($vehicaCurrentWidget->getSearchQuery()); ?>
    <?php else : ?>
        <?php echo esc_html(vehicaApp('our_latest_news_string')); ?>
    <?php endif; ?>
</h1>