<?php


namespace Vehica\Widgets\Car\Single;


/**
 * Class WhatsAppButtonSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class WhatsAppButtonSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_whats_app_button_single_car_widget';
    const TEMPLATE = 'car/single/whats_app_button';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('WhatsApp Button', 'vehica-core');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}