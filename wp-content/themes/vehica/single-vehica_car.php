<?php

do_action('vehica/layouts/carSingle/prepareCss');

get_header();

if (class_exists(Vehica\Core\App::class) && vehicaApp('car_single_template')) :
    do_action('vehica/layouts/single/car');
else :
    get_template_part('templates/blog/single');
endif;

get_footer();