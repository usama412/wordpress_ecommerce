<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class AddToCompareSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class AddToCompareSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_add_to_compare_single_car_widget';
    const TEMPLATE = 'car/single/add_to_compare';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Add To Compare', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            static::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'add_to_compare_info',
            [
                'raw' => '<strong>' . __('Please note!', 'elementor') . '</strong> ' . __('To display "Compare" Checkbox on the Single Listing Page option you must select option: Vehica Panel > Compare Mode > Always ON.', 'elementor'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}