<?php

if (class_exists(\Vehica\Core\App::class) && vehicaApp('404_page')) :
    do_action('vehica/layouts/404/prepareCss');
endif;

get_header();

if (class_exists(\Vehica\Core\App::class) && vehicaApp('404_page')) :
    do_action('vehica/layouts/404');
else :
    get_template_part('templates/blog/404');
endif;

get_footer();