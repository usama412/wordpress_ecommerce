<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class AddToFavoriteSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class AddToFavoriteSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_add_to_favorite_single_car_widget';
    const TEMPLATE = 'car/single/add_to_favorite';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Add To Favorites', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTextAlignControl(
            'vehica_add_to_favorite',
            '.vehica-car-add-to-favorite__wrapper'
        );

        $this->add_responsive_control(
            'vehica_add_to_favorite_icon',
            [
                'label' => esc_html__('Icon Size', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-car-add-to-favorite i' => 'font-size: {{SIZE}}{{UNIT}};'
                ],
                'frontend_available' => true,
            ]
        );

        $this->start_controls_tabs(
            'favorite_tabs'
        );

        $this->start_controls_tab(
            'favorite_tab',
            [
                'label' => esc_html__('Normal', 'vehica-core'),
            ]
        );

        $this->addTextColorControl('favorite', '.vehica-car-add-to-favorite');

        $this->addTextTypographyControl('favorite', '.vehica-car-add-to-favorite');

        $this->end_controls_tab();

        $this->start_controls_tab(
            'favorite_active_tab',
            [
                'label' => esc_html__('Active', 'vehica-core')
            ]
        );

        $this->addTextColorControl('favorite_active', '.vehica-car-add-to-favorite.vehica-car-add-to-favorite--is-favorite');

        $this->addTextTypographyControl('favorite_active', '.vehica-car-add-to-favorite.vehica-car-add-to-favorite--is-favorite');

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}