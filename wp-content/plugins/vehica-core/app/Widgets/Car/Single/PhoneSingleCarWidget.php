<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Car;

/**
 * Class PhoneSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class PhoneSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_phone_single_car_widget';
    const TEMPLATE = 'car/single/phone';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Owner Phone (Reveal)', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTextColorControl(
            'vehica_phone',
            '.vehica-phone-show-number button'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }


    /**
     * @param Car $car
     *
     * @return string
     */
    public function getNumberPlaceholder(Car $car)
    {
        $user = $car->getUser();
        if ( ! $user) {
            return '*** *** ***';
        }

        $phone = $user->getPhone();
        if (empty($phone)) {
            return '*** *** ***';
        }

        $phone = str_replace(['(', ')', ' ', '+'], '', $phone);

        return substr($phone, 0, 3) . ' *** ***';
    }

}