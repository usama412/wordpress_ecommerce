<form role="search" method="get" class="vehica-search-form" action="/">
    <label>
        <?php if (class_exists(\Vehica\Core\App::class)) : ?>
            <span class="screen-reader-text"><?php echo esc_html(vehicaApp('search_for_string')); ?></span>
        <?php else : ?>
            <span class="screen-reader-text"><?php esc_html_e('Search for:', 'vehica'); ?></span>
        <?php endif; ?>

        <input
                class="vehica-search-field"
                type="search"
            <?php if (class_exists(\Vehica\Core\App::class)) : ?>
                placeholder="<?php echo esc_attr(vehicaApp('search_dots_string')); ?>"
            <?php else : ?>
                placeholder="<?php esc_attr_e('Search …', 'vehica'); ?>"
            <?php endif; ?>
                value="<?php the_search_query(); ?>"
                name="s"
        >
        <button class="vehica-search-form__button-search"><i class="fas fa-search"></i></button>
    </label>
</form>