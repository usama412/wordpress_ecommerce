<?php
if (class_exists(\Vehica\Core\App::class)) :
    do_action('vehica/layouts/carArchive/prepareCss');
endif;

get_header();

if (class_exists(Vehica\Core\App::class) && vehicaApp('car_archive_template')) :
    do_action('vehica/layouts/archive/car');
else:
    get_template_part('templates/blog/index');
endif;

get_footer();