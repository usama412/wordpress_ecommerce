<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\ServiceProvider;
use Vehica\Field\FieldType;
use Vehica\Model\Post\Field\AttachmentsField;
use Vehica\Model\Post\Field\DateTimeField;
use Vehica\Model\Post\Field\EmbedField;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\HeadingField;
use Vehica\Model\Post\Field\LocationField;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\RewritableField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Field\TextField;
use Vehica\Model\Post\Field\TrueFalseField;
use WP_Query;

/**
 * Class FieldServiceProvider
 *
 * @package Vehica\Providers
 */
class FieldServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('field_types', static function () {
            $path = vehicaApp('config_path') . 'fields.php';
            $fieldTypes = file_exists($path) ? require $path : [];

            return Collection::make($fieldTypes)->map(static function ($data, $class) {
                return new FieldType($class, $data['key'], $data['name']);
            });
        });

        $this->app->bind('fields', static function () {
            $query = new WP_Query([
                'posts_per_page' => -1,
                'post_status' => PostStatus::PUBLISH,
                'post_type' => Field::POST_TYPE,
                'order' => 'ASC',
                'orderby' => 'name'
            ]);

            return Collection::make($query->posts)->map(static function ($post) {
                return Field::get($post);
            });
        });

        $this->app->bind('attachments_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof AttachmentsField;
            });
        });

        $this->app->bind('price_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof PriceField;
            });
        });

        $this->app->bind('date_time_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof DateTimeField;
            });
        });

        $this->app->bind('location_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof LocationField;
            });
        });

        $this->app->bind('location_fields_list', static function () {
            $fields = [];

            foreach (vehicaApp('location_fields') as $locationField) {
                /* @var LocationField $locationField */
                $fields[$locationField->getKey()] = $locationField->getName();
            }

            return $fields;
        });

        $this->app->bind('price_field_key_list', static function () {
            $list = [];

            foreach (vehicaApp('price_fields') as $field) {
                /* @var PriceField $field */
                $list[$field->getKey()] = $field->getName();
            }

            return $list;
        });

        $this->app->bind('car_fields', static function () {
            if (!vehicaApp('car_config')) {
                return Collection::make();
            }

            return vehicaApp('car_config')->getAllFields();
        });

        $this->app->bind('simple_text_car_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($carField) {
                return $carField instanceof SimpleTextAttribute;
            });
        });

        $this->app->bind('simple_text_car_fields_list', static function () {
            $list = [];

            vehicaApp('simple_text_car_fields')->each(static function ($field) use (&$list) {
                /* @var SimpleTextAttribute $field */
                $list[$field->getId()] = $field->getName();
            });

            return $list;
        });

        $this->app->bind('usable_car_fields', static function () {
            return vehicaApp('car_fields');
        });


        $this->app->bind('rewritable_fields', static function () {
            return vehicaApp('fields')->filter(static function ($field) {
                return $field instanceof RewritableField;
            });
        });

        $this->app->bind('gallery_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof GalleryField;
            });
        });

        $this->app->bind('gallery_field_key_list', static function () {
            $list = [];

            foreach (vehicaApp('gallery_fields') as $galleryField) {
                /* @var GalleryField $galleryField */
                $list[$galleryField->getKey()] = $galleryField->getName();
            }

            return $list;
        });

        $this->app->bind('embed_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof EmbedField;
            });
        });

        $this->app->bind('embed_field_key_list', static function () {
            $list = [];

            foreach (vehicaApp('embed_fields') as $embedField) {
                /* @var EmbedField $embedField */
                $list[$embedField->getKey()] = $embedField->getName();
            }

            return $list;
        });

        $this->app->bind('taxonomies', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof Taxonomy;
            });
        });

        $this->app->bind('child_taxonomies', static function () {
            return vehicaApp('taxonomies')->filter(static function ($taxonomy) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->hasParentTaxonomy();
            });
        });

        $this->app->bind('taxonomies_keys', static function () {
            return vehicaApp('taxonomies')->map(static function ($taxonomy) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->getKey();
            })->all();
        });

        $this->app->bind('taxonomies_list', static function () {
            return vehicaApp('taxonomies')->toList();
        });

        $this->app->bind('taxonomy_ids', static function () {
            return vehicaApp('taxonomies')
                ->map(static function ($taxonomy) {
                    /* @var Taxonomy $taxonomy */
                    return $taxonomy->getId();
                })
                ->values()
                ->all();
        });

        $this->app->bind('taxonomies_key_list', static function () {
            $list = [];

            foreach (vehicaApp('taxonomies') as $taxonomy) {
                /* @var Taxonomy $taxonomy */
                $list[$taxonomy->getKey()] = $taxonomy->getName();
            }

            return $list;
        });

        $this->app->bind('text_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof TextField;
            });
        });

        $this->app->bind('number_fields', static function () {
            return vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof NumberField;
            });
        });
    }

    /**
     * @param string $key
     *
     * @return Taxonomy|false
     */
    public static function getTaxonomy($key)
    {
        $taxonomyKey = str_replace('taxonomy_', '', $key);
        $taxonomyId = (int)$taxonomyKey;

        return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyId, $taxonomyKey) {
            /* @var Taxonomy $taxonomy */
            return $taxonomy->getId() === $taxonomyId || $taxonomy->getKey() === $taxonomyKey;
        });
    }

}