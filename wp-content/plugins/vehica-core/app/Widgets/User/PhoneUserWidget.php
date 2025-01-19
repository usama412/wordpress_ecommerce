<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class PhoneUserWidget
 * @package Vehica\Widgets\User
 */
class PhoneUserWidget extends UserWidget
{
    const NAME = 'vehica_phone_user_widget';
    const TEMPLATE = 'user/phone';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Phone', 'vehica-core');
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
            '.vehica-user-phone'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}