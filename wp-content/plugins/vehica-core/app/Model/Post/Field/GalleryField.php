<?php

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Field\GalleryAttribute;
use Vehica\Core\Model\Field\FieldsUser;

/**
 * Class GalleryField
 * @package Vehica\CustomField\Fields
 */
class GalleryField extends Field
{
    const KEY = 'gallery';

    /**
     * @param FieldsUser $fieldsUser
     * @return GalleryAttribute
     */
    public function getAttribute(FieldsUser $fieldsUser)
    {
        return new GalleryAttribute($this, $fieldsUser);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param string $value
     */
    public function save(FieldsUser $fieldsUser, $value)
    {
        if (isset($_POST['vehica_source']) && $_POST['vehica_source'] === 'backend' && empty($_POST[$this->getKey() . '_loaded'])) {
            return;
        }

        $imageIds = explode(',', $value);
        global $wpdb;
        $table = $wpdb->prefix . 'posts';

        if (!empty($imageIds) && is_array($imageIds)) {
            foreach ($imageIds as $imageId) {
                $wpdb->update($table,
                    [
                        'post_parent' => $fieldsUser->getId(),
                    ],
                    [
                        'ID' => $imageId,
                    ]
                );
            }
        }

        $fieldsUser->setMeta($this->getKey(), $value);
    }

    /**
     * @param FieldsUser $fieldsUser
     * @return array
     */
    public function getValue(FieldsUser $fieldsUser)
    {
        if (!$this->isVisible()) {
            return [];
        }

        $images = $fieldsUser->getMeta($this->getKey());
        if (empty($images)) {
            return [];
        }

        $images = explode(',', $images);
        if (empty($images) || !is_array($images)) {
            return [];
        }

        $images = Collection::make($images)->map(static function ($imageId) {
            return (int)$imageId;
        })->filter(static function ($imageId) {
            return !empty($imageId);
        });

        if (is_admin() && current_user_can('manage_options')) {
            $images = $images->filter(static function ($imageId) {
                return wp_get_attachment_image_url($imageId) !== false;
            });
        }

        return $images->all();
    }

}