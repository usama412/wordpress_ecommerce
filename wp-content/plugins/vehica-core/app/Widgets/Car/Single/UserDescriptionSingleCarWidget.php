<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class UserDescriptionSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserDescriptionSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_description_single_car_widget';
    const TEMPLATE = 'car/single/user_description';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Description', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'user_description',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'type' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-user-description'
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-user-description' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->addTextAlignControl(
            'user_description',
            '.vehica-user-description'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}