<?php

if (class_exists(\Vehica\Core\App::class) && vehicaApp('blog_page')) {
    wp_redirect(vehicaApp('blog_page')->getUrl() . '?s=' . urlencode(get_query_var('s')));
    exit;
}

get_template_part('archive');