<?php

define('VEHICA_VERSION', '1.0.90');

add_action('after_setup_theme', static function () {
    add_theme_support('post-thumbnails');
    add_theme_support('nav-menus');
    add_theme_support('title-tag');
    add_theme_support('woocommerce');
    add_theme_support('custom-logo', [
        'width' => 160,
        'height' => 36,
    ]);
    add_theme_support(
        'html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ]
    );

    load_theme_textdomain('vehica', get_template_directory().'/languages');

    register_nav_menus(['vehica-primary' => esc_html__('Vehica Theme Default Menu', 'vehica')]);
});

add_action('wp_enqueue_scripts', static function () {
    $deps = [];

    if (!class_exists(\Vehica\Core\App::class)) {
        foreach (['Muli'] as $font) {
            $fontName = strtolower($font);
            $font = str_replace(' ', '+', $font)
                .':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
            $fontUrl = 'https://fonts.googleapis.com/css?family='.$font.'&display=swap';

            $subsets = [
                'ru_RU' => 'cyrillic',
                'bg_BG' => 'cyrillic',
                'he_IL' => 'hebrew',
                'el' => 'greek',
                'vi' => 'vietnamese',
                'uk' => 'cyrillic',
                'cs_CZ' => 'latin-ext',
                'ro_RO' => 'latin-ext',
                'pl_PL' => 'latin-ext',
            ];
            $locale = get_locale();

            if (isset($subsets[$locale])) {
                $fontUrl .= '&subset='.$subsets[$locale];
            }

            wp_enqueue_style('google-font-'.$fontName, $fontUrl, [], false);
        }

        wp_enqueue_style('vehica-blog', get_template_directory_uri().'/assets/css/style-static.css', ['vehica']);
        wp_enqueue_style('font-awesome', get_template_directory_uri().'/assets/css/fontawesome.min.css');
        wp_enqueue_script('vehica-blog', get_template_directory_uri().'/assets/js/main.min.js', ['jquery'], false,
            true);

        if (is_singular(['page', 'post']) && comments_open()) {
            wp_enqueue_script('comment-reply');
        }
    }

    if (class_exists(\Elementor\Plugin::class)) {
        $deps[] = 'elementor-frontend';
    }

    wp_enqueue_style('vehica', get_stylesheet_uri(), $deps, VEHICA_VERSION);
});

require get_template_directory().'/tgm/class-tgm-plugin-activation.php';
require 'includes/extras.php';

add_action('tgmpa_register', static function () {
    tgmpa(
        [
            [
                'name' => esc_html__('Contact Form 7', 'vehica'),
                'slug' => 'contact-form-7',
                'required' => true,
            ],
            [
                'name' => esc_html__('Elementor', 'vehica'),
                'slug' => 'elementor',
                'required' => true,
                'version' => '3.0.0',
                'force_activation' => false,
                'force_deactivation' => false,
            ],
            [
                'name' => esc_html__('Vehica Core', 'vehica'),
                'slug' => 'vehica-core',
                'source' => get_template_directory().'/tgm/plugins/vehica-core.zip',
                'required' => true,
                'version' => '1.0.90',
                'force_activation' => false,
                'force_deactivation' => false,
            ],
            [
                'name' => esc_html__('Vehica Updater', 'vehica'),
                'slug' => 'vehica-updater',
                'source' => get_template_directory().'/tgm/plugins/vehica-updater.zip',
                'required' => false,
                'version' => '1.0.6',
                'force_activation' => false,
                'force_deactivation' => false,
            ],
            [
                'name' => esc_html__('MC4WP', 'vehica'),
                'slug' => 'mailchimp-for-wp',
                'required' => false,
                'version' => '4.8.1',
                'force_activation' => false,
                'force_deactivation' => false,
            ],
        ]
    );
});

add_action('widgets_init', static function () {
    register_sidebar(
        [
            'name' => esc_html__('Vehica Sidebar', 'vehica'),
            'id' => 'vehica-sidebar',
            'description' => esc_html__('Add widgets here.', 'vehica'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget' => '</section>',
            'before_title' => '<div class="vehica-widget-title"><h3 class="vehica-widget-title__text">',
            'after_title' => '</h3></div>',
        ]
    );
});

if (!class_exists(Vehica\Core\App::class)) {
    add_theme_support('automatic-feed-links');
}

require get_template_directory().'/includes/basic.php';

if (!isset($content_width)) {
    $content_width = 600;
}