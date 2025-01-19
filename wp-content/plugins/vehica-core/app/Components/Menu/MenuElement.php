<?php

namespace Vehica\Components\Menu;


use Vehica\Model\Post\Post;
use WP_Post;

/**
 * Class MenuElement
 *
 * @package Vehica\Components\Menu
 */
class MenuElement extends Post
{
    /**
     * @var int
     */
    protected $depth;

    /**
     * @var int
     */
    protected $counter;

    /**
     * MenuElement constructor.
     * @param WP_Post $post
     * @param int $depth
     * @param int $counter
     */
    public function __construct(WP_Post $post, $depth = 0, $counter = 1)
    {
        parent::__construct($post);

        $this->depth = $depth;

        $this->counter = $counter;
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
     * @noinspection PhpUndefinedFieldInspection
     */
    public function getLink()
    {
        return $this->model->url;
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
        return 'menu-item-' . $this->counter . '-' . $this->model->ID;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        $classes = !empty($this->model->classes) ? $this->model->classes : [];
        $classes[] = 'menu-item-' . $this->getId();
        $classes[] = 'vehica-menu-item-depth-' . $this->getDepth();

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
        return $this->getMeta('_menu_item_target') === '_blank';
    }

}