<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Image;
use Vehica\Widgets\Partials\GallerySingleCarPartialWidget;

/**
 * Class GalleryV4SingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class GalleryV4SingleCarWidget extends SingleCarWidget
{
    use GallerySingleCarPartialWidget;

    const NAME = 'vehica_gallery_v4_single_car_widget';
    const TEMPLATE = 'car/single/gallery_v4';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Gallery V4', 'vehica-core');
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

        $this->addGalleryFieldControl();

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
    public function getThumbnails()
    {
        return $this->getImages()->slice(1, 4);
    }

    /**
     * @return Image|false
     */
    public function getMainImage()
    {
        return $this->getImages()->slice(0, 1)->first();
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $count = $this->getImages()->count();

        if ($count > 5) {
            return 5;
        }

        return $count;
    }

}