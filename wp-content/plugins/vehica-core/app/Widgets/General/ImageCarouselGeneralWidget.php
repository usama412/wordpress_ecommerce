<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Core\Collection;
use Vehica\Widgets\Partials\SwiperPartialWidget;

/**
 * Class ImageCarouselGeneralWidget
 *
 * @package Vehica\Widgets\General
 */
class ImageCarouselGeneralWidget extends GeneralWidget
{
    use SwiperPartialWidget;

    const NAME     = 'vehica_image_carousel_general_widget';
    const TEMPLATE = 'general/image_carousel';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Image Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('Term Carousel', 'vehica-core'),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addImageSizeControl('large');

        $this->addItemsControl();

        $this->end_controls_section();
    }

    private function addItemsControl()
    {
        $repeater = new Repeater();
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'vehica-core'),
                'type'  => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'vehica-core'),
                'type'  => Controls_Manager::URL,
            ]
        );

        $this->add_control(
            'vehica_items',
            [
                'label'         => esc_html__('Items', 'vehica-core'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => $repeater->get_controls(),
                'prevent_empty' => false,
            ]
        );
    }

    /**
     * @return array
     */
    public function getBreakpoints()
    {
        return [
            [
                'width'  => 1299,
                'number' => 4
            ],
            [
                'width'  => 1023,
                'number' => 3
            ],
            [
                'width'  => 699,
                'number' => 2
            ],
            [
                'width'  => 1,
                'number' => 1
            ],
        ];
    }

    /**
     * @return Collection
     */
    public function getItems()
    {
        $items = $this->get_settings_for_display('vehica_items');
        if (empty($items) || ! is_array($items)) {
            return Collection::make();
        }

        $imageSize = $this->getImageSize();

        return Collection::make($items)->map(static function ($item) use ($imageSize) {
            if (empty($item['image']['id'])) {
                $image = $item['image']['url'];
            } else {
                $image = wp_get_attachment_image_url($item['image']['id'], $imageSize);
            }

            return [
                'image' => $image,
                'link'  => $item['link'],
            ];
        });
    }

}