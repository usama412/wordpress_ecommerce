<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class IdSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class IdSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_id_single_car_widget';
    const TEMPLATE = 'car/single/id';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing ID', 'vehica-core');
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

        $this->add_control(
            'vehica_text',
            [
                'label' => esc_html__('Text before ID', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return bool
     */
    public function hasText()
    {
        return $this->getText() !== '';
    }

    /**
     * @return string
     */
    public function getText()
    {
        return (string)$this->get_settings_for_display('vehica_text');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}