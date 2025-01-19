<?php


namespace Vehica\Widgets\User;


/**
 * Class DescriptionUserWidget
 * @package Vehica\Widgets\User
 */
class DescriptionUserWidget extends UserWidget
{
    const NAME = 'vehica_description_user_widget';
    const TEMPLATE = 'user/description';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Description', 'vehica-core');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}