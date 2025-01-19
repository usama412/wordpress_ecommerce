<?php


namespace Vehica\Widgets\User;


/**
 * Class WhatsAppButtonUserWidget
 * @package Vehica\Widgets\User
 */
class WhatsAppButtonUserWidget extends UserWidget
{
    const NAME = 'vehica_whats_app_user_widget';
    const TEMPLATE = 'user/whats_app_button';

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