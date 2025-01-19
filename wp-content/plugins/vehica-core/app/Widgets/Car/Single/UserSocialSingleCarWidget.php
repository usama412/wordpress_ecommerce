<?php


namespace Vehica\Widgets\Car\Single;


/**
 * Class UserSocialSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class UserSocialSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_user_social_single_car_widget';
    const TEMPLATE = 'car/single/user_social';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Socials', 'vehica-core');
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }
}