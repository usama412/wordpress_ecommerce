<?php

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class SimpleMenuGeneralWidget
 * @package Vehica\Widgets\General
 */
class SimpleMenuGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_simple_menu_general_widget';
    const TEMPLATE = 'general/simple_menu';
    const MENU = 'vehica_menu';
    const TYPE = 'vehica_type';
    const TYPE_VERTICAL = 'vertical';
    const TYPE_HORIZONTAL = 'horizontal';

    public static $counter = 1;

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Simple Menu', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            'vehica_simple_menu_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $menus = vehicaApp('menus')->toList();
        $defaultMenuId = vehicaApp('footer_menu_id');
        if (empty($defaultMenuId)) {
            $defaultMenuId = key($menus);
        }
        $this->add_control(
            self::MENU,
            [
                'label' => esc_html__('Menu', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $menus,
                'default' => $defaultMenuId
            ]
        );

        $this->add_control(
            self::TYPE,
            [
                'label' => esc_html__('Direction (Tablet / Desktop)', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    self::TYPE_HORIZONTAL => esc_html__('Horizontal', 'vehica-core'),
                    self::TYPE_VERTICAL => esc_html__('Vertical', 'vehica-core'),
                ],
                'default' => self::TYPE_HORIZONTAL
            ]
        );

        $this->addTextColorControl(
            'vehica_text_color',
            '.vehica-simple-menu a',
            esc_html__('Color', 'vehica-core')
        );

        $this->end_controls_section();
    }

    /**
     * @return string
     */
    public function getType()
    {
        return (string)$this->get_settings_for_display(self::TYPE);
    }

    /**
     * @return bool
     */
    public function isVertical()
    {
        return $this->getType() === self::TYPE_VERTICAL;
    }

    /**
     * @return bool
     */
    public function isHorizontal()
    {
        return $this->getType() === self::TYPE_HORIZONTAL;
    }

    protected function render()
    {
        $menuClass = [
            'vehica-simple_menu'
        ];

        if ($this->isHorizontal()) {
            $menuClass[] = 'vehica-simple-menu--horizontal';
        } else {
            $menuClass[] = 'vehica-simple-menu--vertical';
        }

        $this->add_render_attribute('menu', 'class', implode(' ', $menuClass));

        $this->loadTemplate();
    }

    public function displayMenu()
    {
        $menuId = (int)$this->get_settings_for_display(self::MENU);
        if (empty($menuId) || get_post($menuId)) {
            return;
        }

        wp_nav_menu([
            'menu' => $menuId,
            'container' => 'div',
            'container_id' => 'vehica-simple-container-' . self::$counter,
            'items_wrap' => '<ul class="vehica-simple-menu">%3$s</ul>',
            'depth' => 1,
        ]);

        ++self::$counter;
    }
}