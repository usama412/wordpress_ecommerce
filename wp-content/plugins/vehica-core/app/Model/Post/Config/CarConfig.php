<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Config;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Traits\Fieldable;
use Vehica\Core\Model\Traits\Templatable;
use Vehica\Core\PostType\PostTypable;
use Vehica\Core\PostType\PostTypeData;
use Vehica\Core\TemplateType\TemplateType;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Template\Template;
use Vehica\Widgets\WidgetCategory;

/**
 * Class CarConfig
 * @package Vehica\Model\Post\Config
 */
class CarConfig extends Config implements PostTypable, \Vehica\Core\Model\Interfaces\Templatable
{
    use Templatable;
    use Fieldable;

    const HAS_COMMENTS = 'vehica_has_comments';
    const KEY = 'vehica_car_config';
    const TAXONOMIES = 'vehica_taxonomies';

    /**
     * @var array
     */
    protected $settings = [
        CarConfig::HAS_COMMENTS,
        Field::OPTION,
    ];

    /**
     * @return PostTypeData
     */
    public function getPostTypeData()
    {
        return new PostTypeData(
            Car::POST_TYPE,
            Car::class,
            $this->getName(),
            $this->getSingularName(),
            true,
            $this->getOptions()
        );
    }

    /**
     * @return array
     */
    private function getOptions()
    {
        $supports = ['title', 'author', 'thumbnail', 'editor'];

        if ($this->hasComments()) {
            $supports[] = 'comments';
        }

        return [
            'name' => $this->getName(),
            'singular_name' => $this->getSingularName(),
            'has_archive' => $this->getRewriteArchive(),
            'show_in_menu' => true,
            'rewrite' => [
                'slug' => $this->getRewrite(),
                'with_front' => false,
            ],
            'supports' => $supports,
            'query_var' => true,
            'public' => true,
            'publicly_queryable' => true,
            'show_in_rest' => true,
            'rest_base' => 'cars',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return vehicaApp('vehicles_string');
    }

    /**
     * @return string
     */
    public function getSingularName()
    {
        return vehicaApp('vehicle_string');
    }

    /**
     * @return bool
     */
    public function hasArchive()
    {
        return true;
    }

    /**
     * @param int $hasComments
     */
    public function setHasComments($hasComments)
    {
        $hasComments = (int)$hasComments;
        update_post_meta($this->getId(), self::HAS_COMMENTS, $hasComments);
    }

    /**
     * @return bool
     */
    public function hasComments()
    {
        $hasComments = get_post_meta($this->getId(), self::HAS_COMMENTS, true);
        return !empty($hasComments);
    }

    /**
     * @return string
     */
    public function getRewriteArchive()
    {
        return vehicaApp('vehicle_archive_rewrite');
    }

    /**
     * @return bool
     */
    public function supportTaxonomies()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function hasThumbnail()
    {
        return true;
    }

    /**
     * @return Collection
     */
    public function getTemplateTypes()
    {
        return Collection::make([
            TemplateType::make(
                WidgetCategory::CAR_SINGLE,
                esc_html__('Car (single)', 'vehica-core')
            ),
            TemplateType::make(
                WidgetCategory::CAR_ARCHIVE,
                esc_html__('Car (archive)', 'vehica-core')
            )
        ]);
    }

    /**
     * @return Template|false
     */
    public function getSingleTemplate()
    {
        $template = $this->getTemplateByTypeKey(WidgetCategory::CAR_SINGLE);

        if (!$template) {
            return vehicaApp('car_single_templates')->count() > 0 ? vehicaApp('car_single_templates')->first() : false;
        }

        return $template;
    }

    /**
     * @return int
     */
    public function getSingleTemplateId()
    {
        if (!vehicaApp('car_single_template') instanceof Template) {
            return 0;
        }

        return vehicaApp('car_single_template')->getId();
    }

    /**
     * @param int $templateId
     */
    public function setSingleTemplate($templateId)
    {
        $this->setTemplate(WidgetCategory::CAR_SINGLE, $templateId);
    }

    /**
     * @return Template|false
     */
    public function getArchiveTemplate()
    {
        $template = $this->getTemplateByTypeKey(WidgetCategory::CAR_ARCHIVE);

        if (!$template) {
            return vehicaApp('car_archive_templates')->count() > 0 ? vehicaApp('car_archive_templates')->first() : false;
        }

        return $template;
    }

    /**
     * @return int
     */
    public function getArchiveTemplateId()
    {
        if (!vehicaApp('car_archive_template') instanceof Template) {
            return 0;
        }

        return vehicaApp('car_archive_template')->getId();
    }

    /**
     * @param int $templateId
     */
    public function setArchiveTemplate($templateId)
    {
        $this->setTemplate(WidgetCategory::CAR_ARCHIVE, $templateId);
    }

    /**
     * @return string
     */
    public function getRewrite()
    {
        return vehicaApp('vehicle_single_rewrite');
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        return vehicaApp('usable_car_fields');
    }

    /**
     * @return Collection
     */
    public function getAllFields()
    {
        $fields = $this->getMeta(Field::OPTION);

        if (!is_array($fields) || empty($fields)) {
            return Collection::make();
        }

        return Collection::make($fields)->map(static function ($fieldId) {
            $fieldId = (int)$fieldId;
            return vehicaApp('fields')->find(static function ($field) use ($fieldId) {
                /* @var Field $field */
                return $field->getId() === $fieldId && $field->isCarField();
            });
        })->filter(static function ($field) {
            return $field !== false;
        });
    }

}