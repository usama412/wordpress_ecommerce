<?php

do_action('vehica/layouts/postArchive/prepareCss');

get_header();

if (is_home() && class_exists(Vehica\Core\App::class) && vehicaApp('post_archive_template')) :
    do_action('vehica/layouts/archive/post');
else:
    get_template_part('templates/blog/index');
endif;

get_footer();