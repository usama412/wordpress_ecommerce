<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

/**
 * Class CalculateLoanLinkSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class CalculateLoanLinkSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_calculate_loan_link_single_car_widget';
    const TEMPLATE = 'car/single/calculate_loan_link';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Calculate Loan Link', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'calculate_loan_link',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->addTextTypographyControl(
            'link',
            '.vehica-calculate-loan-link'
        );

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name' => 'link_typography_hover',
            'label' => esc_html__('Typography (Hover)', 'vehica-core'),
            'selector' => '{{WRAPPER}} .vehica-calculate-loan-link:hover',
        ]);

        $this->addTextColorControl(
            'link',
            '.vehica-calculate-loan-link',
            esc_html__('Color', 'vehica-core')
        );

        $this->addTextColorControl(
            'link_hover',
            '.vehica-calculate-loan-link:hover',
            esc_html__('Color (Hover)', 'vehica-core')
        );

        $this->addTextAlignControl(
            'link',
            '.vehica-calculate-loan-link-wrapper'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}