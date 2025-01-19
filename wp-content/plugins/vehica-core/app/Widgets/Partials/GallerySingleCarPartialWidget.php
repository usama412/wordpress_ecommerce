<?php


namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Image;
use WP_Query;

/**
 * Trait GallerySingleCarPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait GallerySingleCarPartialWidget
{
    use WidgetPartial;

    /**
     * @var Collection
     */
    protected $images;

    /**
     * @return Car|false
     */
    abstract public function getCar();

    protected function prepareImages()
    {
        $car = $this->getCar();
        if ( ! $car) {
            $this->images = Collection::make();

            return;
        }

        $galleryField = $this->getGalleryField();
        if ( ! $galleryField instanceof GalleryField) {
            $this->images = Collection::make();

            return;
        }

        $imageIds = $galleryField->getValue($car);
        if (empty($imageIds)) {
            $this->images = Collection::make();

            return;
        }

        $query = new WP_Query([
            'post__in'       => $imageIds,
            'post_type'      => 'attachment',
            'posts_per_page' => '-1',
            'post_status'    => 'inherit',
            'orderby'        => 'post__in'
        ]);

        $this->images = Collection::make($query->posts)->map(static function ($image) {
            return new Image($image);
        });
    }

    /**
     * @return GalleryField|false
     */
    private function getGalleryField()
    {
        $galleryFieldKey = $this->get_settings_for_display('vehica_gallery_field');
        if (empty($galleryFieldKey)) {
            return false;
        }

        $galleryField = vehicaApp('car_fields')->find(static function ($field) use ($galleryFieldKey) {
            /* @var Field $field */
            return $field->getKey() === $galleryFieldKey;
        });

        if ($galleryField instanceof GalleryField) {
            return $galleryField;
        }

        return vehicaApp('car_fields')->find(static function ($field) {
            return $field instanceof GalleryField;
        });
    }

    private function addGalleryFieldControl()
    {
        $list = vehicaApp('gallery_field_key_list');

        if (count($list) === 1) {
            $this->add_control(
                'vehica_gallery_field',
                [
                    'label'   => esc_html__('Gallery Field', 'vehica-core'),
                    'type'    => Controls_Manager::HIDDEN,
                    'default' => key($list)
                ]
            );

            return;
        }

        $this->add_control(
            'vehica_gallery_field',
            [
                'label'   => esc_html__('Gallery Field', 'vehica-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => $list,
                'default' => ! empty($list) ? key($list) : null,
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getImages()
    {
        if ($this->images === null) {
            $this->prepareImages();
        }

        return $this->images;
    }

    /**
     * @return bool
     */
    public function hasImages()
    {
        return $this->getImages()->isNotEmpty();
    }

    /**
     * @return array
     */
    public function getImageData()
    {
        return $this->getImages()->map(static function ($image) {
            /* @var Image $image */
            return [
                'id'     => $image->getId(),
                'url'    => $image->getUrl('full'),
                'width'  => $image->getWidth(),
                'height' => $image->getHeight(),
            ];
        })->all();
    }

}