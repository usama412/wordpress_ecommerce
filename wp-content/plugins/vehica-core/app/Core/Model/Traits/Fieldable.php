<?php

namespace Vehica\Core\Model\Traits;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\Attribute;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Field;

/**
 * Trait Fieldable
 * @package Vehica\Core\Model\Traits
 */
trait Fieldable
{
    use Metadatable;

    /**
     * @param array $fields
     * @return mixed
     */
    public function setFields($fields)
    {
        return $this->setMeta(Field::OPTION, $fields);
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        return $this->getAttributeIds()->map(static function ($attributeId) {
            return self::getAttribute($attributeId);
        })->filter(static function ($attribute) {
            return $attribute;
        });
    }

    /**
     * @param $id
     * @return Attribute|false
     */
    public static function getAttribute($id)
    {
        $id = (int)$id;
        $field = vehicaApp('fields')->find(static function ($field) use ($id) {
            /* @var Field $field */
            return $field->getId() === $id;
        });

        if ($field) {
            return Field::getById($id);
        }

        $taxonomy = vehicaApp('taxonomy_' . $id);
        if (!$taxonomy) {
            return false;
        }

        return $taxonomy;
    }

    /**
     * @return Collection
     */
    public function getAttributeIds()
    {
        $ids = Collection::make();
        $sections = $this->getMeta(Field::OPTION);
        if (empty($sections) || !is_array($sections)) {
            return $ids;
        }

        foreach ($sections as $section) {
            if (!isset($section['fields']) || !is_array($section['fields'])) {
                continue;
            }

            foreach ($section['fields'] as $fieldId) {
                if (!empty($fieldId)) {
                    $ids[] = (int)$fieldId;
                }
            }
        }
        return $ids;
    }

    /**
     * @return bool
     */
    public function hasFields()
    {
        $fields = $this->getMeta(Field::OPTION);
        return !empty($fields);
    }

    /**
     * @return bool
     */
    public function supportTaxonomies()
    {
        return false;
    }

}