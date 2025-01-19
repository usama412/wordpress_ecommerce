<?php

if (!is_home() && class_exists(Vehica\Core\App::class)) {
    do_action('vehica/layouts/preparePageCss');
}

get_header();

if (class_exists(Vehica\Core\App::class) && vehicaApp('page_layout')) :
    if (is_home()) :
        do_action('vehica/layouts/archive/post');
    else :
        do_action('vehica/layouts/page');
    endif;
else :
    get_template_part('templates/blog/page');
endif;

get_footer();