<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;

/**
 * Class UserMailSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserMailSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_mail_single_car_widget';
    const TEMPLATE = 'car/single/user_mail';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Email', 'vehica-core');
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
            'vehica_no_options_info',
            [
                'label' => esc_html__('No Settings', 'vehica-core'),
                'type' => Controls_Manager::HEADING
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