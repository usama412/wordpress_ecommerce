<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Model\Post\Image;
use Vehica\Widgets\Partials\GallerySingleCarPartialWidget;

/**
 * Class GalleryV2SingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class GalleryV2SingleCarWidget extends SingleCarWidget
{
    use GallerySingleCarPartialWidget;

    const NAME = 'vehica_gallery_v2_single_car_widget';
    const TEMPLATE = 'car/single/gallery_v2';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Gallery V2', 'vehica-core');
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

        $this->addGalleryFieldControl();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

    /**
     * @return string
     */
    public function getMainImageUrl()
    {
        if ($this->getImages()->isEmpty()) {
            return '';
        }

        /** @noinspection NullPointerExceptionInspection */
        return $this->getImages()->first()->getUrl('full');
    }

}