<?php

if (class_exists(Vehica\Core\App::class)) {
    do_action('vehica/layouts/prepareAttachmentCss');
}

get_header();

do_action('vehica/layouts/attachment');

if (class_exists(Vehica\Core\App::class)) :
    do_action('vehica/layouts/attachment');
else :
    get_template_part('templates/attachment');
endif;

get_footer();