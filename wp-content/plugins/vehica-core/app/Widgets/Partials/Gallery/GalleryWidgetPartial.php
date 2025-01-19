<?php

namespace Vehica\Widgets\Partials\Gallery;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\Field\GalleryField;
use Vehica\Widgets\Partials\WidgetPartial;
use Vehica\Field\FieldsUser;
use Vehica\Core\Collection;
use Elementor\Controls_Manager;

/**
 * Trait GalleryElementPartial
 * @package Vehica\Widgets\Partials\Gallery
 */
trait GalleryWidgetPartial
{
    use WidgetPartial;

    /**
     * @var Collection
     */
    private $galleryImages;

    /**
     * @return array
     */
    abstract function getGalleryFieldsList();

    /**
     * @return Collection
     */
    abstract function getGalleryFields();

    /**
     * @return void
     */
    protected function addSelectGalleryFieldControl()
    {
        $galleryFields = $this->getGalleryFieldsList();
        $this->add_control(
            BaseGalleryWidget::GALLERY_FIELD,
            [
                'label' => esc_html__('Gallery field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $galleryFields,
                'default' => !empty($galleryFields) ? key($galleryFields) : ''
            ]
        );
    }

    /**
     * @return GalleryField|false
     */
    protected function getGalleryField()
    {
        $fieldId = intval($this->get_settings_for_display(BaseGalleryWidget::GALLERY_FIELD));
        if (!$fieldId) {
            return false;
        }

        return $this->getGalleryFields()->find(function ($field) use ($fieldId) {
            /* @var GalleryField $field */
            return $field->getId() == $fieldId;
        });
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return void
     */
    private function prepareGalleryImages(FieldsUser $fieldsUser)
    {
        $field = $this->getGalleryField();
        if (!$field) {
            $this->galleryImages = Collection::make();
            return;
        }

        $value = $field->getValue($fieldsUser);
        $this->galleryImages = Collection::make($value)->filter(function ($image) {
            return isset($image['src']) && !empty($image['src']);
        })->map(function ($image) {
            return [
                'src' => $image['src'][0],
                'caption' => $image['caption']
            ];
        });
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return bool
     */
    public function hasImages(FieldsUser $fieldsUser)
    {
        if (is_null($this->galleryImages)) {
            $this->prepareGalleryImages($fieldsUser);
        }
        return $this->galleryImages->isNotEmpty();
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return Collection
     */
    public function getImages(FieldsUser $fieldsUser)
    {
        if (is_null($this->galleryImages)) {
            $this->prepareGalleryImages($fieldsUser);
        }
        return $this->galleryImages;
    }

}