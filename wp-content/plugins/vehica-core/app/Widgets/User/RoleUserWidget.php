<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;

/**
 * Class RoleUserWidget
 * @package Vehica\Widgets\User
 */
class RoleUserWidget extends UserWidget
{
    const NAME = 'vehica_role_user_widget';
    const TEMPLATE = 'user/role';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Position', 'vehica-core');
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
            '.vehica-user-role'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }
}