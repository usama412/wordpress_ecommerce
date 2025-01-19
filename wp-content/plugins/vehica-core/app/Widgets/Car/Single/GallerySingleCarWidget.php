<?php

namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Vehica\Core\Collection;
use Vehica\Model\Post\Image;
use Vehica\Widgets\Partials\GallerySingleCarPartialWidget;

/**
 * Class GallerySingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class GallerySingleCarWidget extends SingleCarWidget
{
    use GallerySingleCarPartialWidget;

    const NAME = 'vehica_gallery_single_car_widget';
    const TEMPLATE = 'car/single/gallery';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Listing Gallery', 'vehica-core');
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

        $this->addAutoPlayControls();

        $this->addThumbnailControls();

        $this->end_controls_section();
    }

    private function addThumbnailControls()
    {
        $this->add_control(
            'vehica_gallery_show_thumbnails',
            [
                'label'        => esc_html__('Display Thumbnails', 'vehica-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default'      => '1',
            ]
        );
    }

    private function addAutoPlayControls()
    {
        $this->add_control(
            'vehica_gallery_autoplay',
            [
                'label'        => esc_html__('Autoplay', 'vehica-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default'      => '0',
            ]
        );

        $this->add_control(
            'vehica_gallery_delay',
            [
                'label'     => esc_html__('Delay', 'vehica-core'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'condition' => [
                    'vehica_gallery_autoplay' => '1'
                ]
            ]
        );
    }

    /**
     * @return int
     */
    public function getThumbsPerView()
    {
        return 5;
    }

    /**
     * @return int
     */
    public function getMobileThumbsPerView()
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getTabletThumbsPerView()
    {
        return 3;
    }

    /**
     * @return array
     */
    public function getSwiperConfigWithoutThumbnails()
    {
        $config = [
            'main' => [
                'loop' => true
            ],
        ];

        if ($this->isAutoplayEnabled()) {
            $config['main']['autoplay'] = [
                'delay' => $this->getDelay()
            ];
        }

        return $config;
    }

    /**
     * @return array
     */
    public function getSwiperConfig()
    {
        if ( ! $this->showThumbnails()) {
            return $this->getSwiperConfigWithoutThumbnails();
        }

        $config = [
            'main'     => [
                'loop' => true,
            ],
            'thumbs'   => [
                'loop' => false,
            ],
            'settings' => [
                'spaceBetween' => 17,
                'desktop'      => [
                    'spaceBetween'  => 17,
                    'slidesPerView' => $this->getThumbsPerView(),
                ],
                'tablet'       => [
                    'spaceBetween'  => 17,
                    'slidesPerView' => $this->getTabletThumbsPerView(),
                ],
                'mobile'       => [
                    'spaceBetween'  => 17,
                    'slidesPerView' => $this->getMobileThumbsPerView(),
                ]
            ]
        ];

        if ($this->isAutoplayEnabled()) {
            $config['main']['autoplay'] = [
                'delay' => $this->getDelay()
            ];
        }

        return $config;
    }

    private function addThumbColumnRenderAttribute()
    {
        $classes = [
            'vehica-swiper-slide',
            'vehica-grid__element',
            'vehica-grid__element--1of' . $this->getThumbsPerView(),
            'vehica-grid__element--tablet-1of' . $this->getTabletThumbsPerView(),
            'vehica-grid__element--mobile-1of' . $this->getMobileThumbsPerView(),
        ];

        $this->add_render_attribute('thumb_column', 'class', implode(' ', $classes));
    }

    public function getMainImages()
    {
        return $this->getImages();
    }

    /**
     * @return Collection
     */
    public function getThumbnails()
    {
        return $this->getImages();
    }

    /**
     * @return bool
     */
    private function isAutoplayEnabled()
    {
        $autoplay = $this->get_settings_for_display('vehica_gallery_autoplay');

        return ! empty($autoplay);
    }

    /**
     * @return int
     */
    private function getDelay()
    {
        return (int)$this->get_settings_for_display('vehica_gallery_delay');
    }

    /**
     * @return bool
     */
    public function showThumbnails()
    {
        $showThumbnails = $this->get_settings_for_display('vehica_gallery_show_thumbnails');

        return ! empty($showThumbnails);
    }

    /**
     * @return string
     */
    private function getMainImageSize()
    {
        $size = (string)$this->get_settings_for_display('vehica_main_image_size_size');

        if (empty($size)) {
            return 'large';
        }

        return $size;
    }

    /**
     * @return string
     */
    private function getThumbImageSize()
    {
        $size = (string)$this->get_settings_for_display('vehica_thumb_image_size_size');

        if (empty($size)) {
            return 'thumbnail';
        }

        return $size;
    }

    protected function render()
    {
        parent::render();

        $this->addThumbColumnRenderAttribute();

        $this->loadTemplate();
    }

    /**
     * @return string
     */
    public function getMainImagePadding()
    {
        $sizes     = vehicaApp('image_sizes');
        $imageSize = $this->getMainImageSize();

        if ( ! isset($sizes[$imageSize])) {
            return '75%';
        }

        return (($sizes[$imageSize]['height'] / $sizes[$imageSize]['width']) * 100) . '%';
    }

    /**
     * @return string
     */
    public function getThumbnailImagePadding()
    {
        $sizes     = vehicaApp('image_sizes');
        $imageSize = $this->getThumbImageSize();

        if ( ! isset($sizes[$imageSize])) {
            return '75%';
        }

        return (($sizes[$imageSize]['height'] / $sizes[$imageSize]['width']) * 100) . '%';
    }

    /**
     * @return string
     */
    public function getThumbnailImageWidth()
    {
        return 'calc(100% / ' . $this->getThumbsPerView() . ' - 17px)';
    }

}