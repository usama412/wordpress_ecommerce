<?php


namespace Vehica\Widgets\User;


use Elementor\Controls_Manager;
use Vehica\Api\CarsApi;
use Vehica\Core\Collection;
use Vehica\Widgets\Partials\CarCardPartialWidget;

/**
 * Class CarsUserWidget
 * @package Vehica\Widgets\User
 */
class CarsUserWidget extends UserWidget
{
    use CarCardPartialWidget;

    const NAME = 'vehica_cars_user_widget';
    const TEMPLATE = 'user/cars';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('User Vehicles', 'vehica-core');
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

        $this->addShowCardLabelsControl();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        $args = [];
        $user = $this->getUser();

        if ($user) {
            $args['user_id'] = $user->getId();
        }

        $args[vehicaApp('sort_by_rewrite')] = vehicaApp('featured_rewrite');

        $api = new CarsApi($args);
        $api->disableTermsCount();

        return $api->getCars();
    }

}