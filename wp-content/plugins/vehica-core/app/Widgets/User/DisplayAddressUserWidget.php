<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class DisplayAddressUserWidget
 * @package Vehica\Widgets\User
 */
class DisplayAddressUserWidget extends UserWidget
{
    const NAME = 'vehica_display_address_user_widget';
    const TEMPLATE = 'user/display_address';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Address', 'vehica-core');
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
            'vehica_align',
            '.vehica-user-address'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}