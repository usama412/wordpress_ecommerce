<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;

/**
 * Class CopyrightsGeneralWidget
 * @package Vehica\Widgets\General
 */
class CopyrightsGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_copyrights_general_widget';
    const TEMPLATE = 'general/copyrights';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Global Copyrights Text', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'type' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTextAlignControl(
            'vehica_copyrights',
            '.vehica-copyrights'
        );

        $this->addTextColorControl(
            'vehica_text_color',
            '.vehica-copyrights'
        );

        $this->end_controls_section();
    }

}