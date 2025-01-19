<?php

function vehica_get_image_orientation()
{
    if (!has_post_thumbnail()) {
        return '';
    }

    $imageId = get_post_thumbnail_id();

    if (empty($imageId)) {
        return '';
    }

    $meta = wp_get_attachment_metadata($imageId);
    if ($meta['width'] > $meta['height']) {
        return 'vehica-image__horizontal';
    }

    if ($meta['height'] > $meta['width']) {
        return 'vehica-image__vertical';
    }

    return 'vehica-image__square';
}

class VehicaMenuWalker extends \Walker_Nav_Menu
{
    /**
     * @param string $output
     * @param WP_Post $item
     * @param int $depth
     * @param array $args
     * @param int $id
     */
    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
        global $vehicaMenuElement;
        $vehicaMenuElement = new VehicaMenuElement($item, $depth);
        ob_start();
        get_template_part('templates/general/menu/item', 'start');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param \WP_Post $item
     * @param int $depth
     * @param array $args
     */
    public function end_el(&$output, $item, $depth = 0, $args = [])
    {
        global $vehicaMenuElement;
        $vehicaMenuElement = new VehicaMenuElement($item, $depth);
        ob_start();
        get_template_part('templates/general/menu/item', 'end');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param int $depth
     * @param array $args
     */
    public function start_lvl(&$output, $depth = 0, $args = [])
    {
        global $vehicaMenuLevel;
        $vehicaMenuLevel = new VehicaMenuLevel($depth);
        ob_start();
        get_template_part('templates/general/menu/level', 'start');
        $output .= ob_get_clean();
    }

    /**
     * @param string $output
     * @param int $depth
     * @param array $args
     */
    public function end_lvl(&$output, $depth = 0, $args = [])
    {
        global $vehicaMenuLevel;
        $vehicaMenuLevel = new VehicaMenuLevel($depth);
        ob_start();
        get_template_part('templates/general/menu/level', 'end');
        $output .= ob_get_clean();
    }

}

class VehicaMenuElement
{
    /**
     * @var int
     */
    protected $depth;

    /**
     * @var WP_Post
     */
    protected $model;

    /**
     * MenuElement constructor.
     *
     * @param WP_Post $post
     * @param int $depth
     */
    public function __construct(WP_Post $post, $depth = 0)
    {
        $this->model = $post;
        $this->depth = $depth;
    }

    /**
     * @return string
     */
    public function getName()
    {
        if (property_exists($this->model, 'title')) {
            return $this->model->title;
        }

        return $this->model->post_title;
    }

    /**
     * @return false|mixed|string
     */
    public function getLink()
    {
        if (property_exists($this->model, 'url')) {
            return $this->model->url;
        }

        return get_the_permalink($this->model);
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return string
     */
    public function getElementId()
    {
        return 'menu-item-' . $this->model->ID;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        $classes = !empty($this->model->classes) ? $this->model->classes : [];
        $classes[] = 'menu-item-' . $this->model->ID;
        $classes = implode(
            ' ',
            apply_filters('nav_menu_css_class',
                array_filter($classes),
                $this->model,
                [],
                $this->depth
            )
        );

        return $classes;
    }

    /**
     * @return bool
     */
    public function isTargetBlank()
    {
        return get_post_meta($this->model->ID, '_menu_item_target', true) === '_blank';
    }
}

class VehicaMenuLevel
{
    /**
     * @var int
     */
    protected $depth;

    /**
     * MenuLevel constructor.
     *
     * @param int $depth
     */
    public function __construct($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        $classes = ['vehica-submenu vehica-submenu--level-' . $this->getDepth()];

        return implode(' ', $classes);
    }

}

function vehica_add_class_the_tags($html)
{
    return str_replace('<a', '<a class="vehica-post-field__tags__single"', $html);
}

add_filter('the_tags', 'vehica_add_class_the_tags');

add_action('wp_enqueue_scripts', static function () {
    if (is_singular('post') && comments_open()) {
        wp_enqueue_script('comment-reply', '/wp-includes/js/comment-reply.min.js', [], false, true);
    }
});