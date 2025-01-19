<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr(get_bloginfo('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php if (function_exists('wp_body_open')): ?>
    <?php wp_body_open(); ?>
<?php endif; ?>
