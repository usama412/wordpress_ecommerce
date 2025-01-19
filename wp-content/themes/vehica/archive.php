<?php

if (class_exists(\Vehica\Core\App::class)) :
    do_action('vehica/layouts/postArchive/prepareCss');
endif;

get_header();

if (class_exists(\Vehica\Core\App::class) && vehicaApp('post_archive_template')) :
    do_action('vehica/layouts/archive/post');
else :
    get_template_part('templates/blog/index');
endif;

get_footer();