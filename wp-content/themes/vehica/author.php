<?php

if (class_exists(\Vehica\Core\App::class)) :
    do_action('vehica/layouts/user/prepareCss');
endif;

get_header();

if (class_exists(\Vehica\Core\App::class) && vehicaApp('user_template')) :
    do_action('vehica/layouts/single/user');
else :
    get_template_part('archive');
endif;

get_footer();