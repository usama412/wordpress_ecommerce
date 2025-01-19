<?php

do_action('vehica/layouts/postSingle/prepareCss');

get_header();

if (class_exists(Vehica\Core\App::class) && vehicaApp('post_single_template')) :
    do_action('vehica/layouts/single/post');
else :
    get_template_part('templates/blog/single');
endif;

get_footer();