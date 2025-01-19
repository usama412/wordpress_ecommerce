<?php

namespace Vehica\Model\Post\Config;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Traits\Templatable;
use Vehica\Core\TemplateType\TemplateType;
use Vehica\Model\Post\Post;
use Vehica\Model\Post\Template\Template;
use Vehica\Widgets\WidgetCategory;

/**
 * Class PostConfig
 * @package Vehica\Model\Post\Config
 */
class PostConfig extends Config implements \Vehica\Core\Model\Interfaces\Templatable
{
    use Templatable;

    const KEY = 'vehica_post_config';

    /**
     * @return bool
     */
    public function hasArchive()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return esc_html__('Post Grid', 'vehica-core');
    }

    /**
     * @return Collection
     */
    public function getTemplateTypes()
    {
        return Collection::make([
            TemplateType::make(
                WidgetCategory::POST_SINGLE,
                esc_html__('Post (single)', 'vehica-core')
            ),
            TemplateType::make(
                WidgetCategory::POST_ARCHIVE,
                esc_html__('Post (archive)', 'vehica-core')
            )
        ]);
    }

    /**
     * @return Template|false
     */
    public function getSingleTemplate()
    {
        $template = $this->getTemplateByTypeKey(WidgetCategory::POST_SINGLE);

        if (!$template) {
            return vehicaApp('post_single_templates')->count() > 0 ? vehicaApp('post_single_templates')->first() : false;
        }

        return $template;
    }

    /**
     * @return int
     */
    public function getSingleTemplateId()
    {
        if (!vehicaApp('post_single_template')) {
            return 0;
        }

        return vehicaApp('post_single_template')->getId();
    }

    /**
     * @param int $templateId
     */
    public function setSingleTemplate($templateId)
    {
        $this->setTemplate(WidgetCategory::POST_SINGLE, $templateId);
    }

    /**
     * @return Template|false
     */
    public function getArchiveTemplate()
    {
        $template = $this->getTemplateByTypeKey(WidgetCategory::POST_ARCHIVE);

        if (!$template) {
            return vehicaApp('post_archive_templates')->count() > 0 ? vehicaApp('post_archive_templates')->first() : false;
        }

        return $template;
    }

    /**
     * @return int
     */
    public function getArchiveTemplateId()
    {
        if (!vehicaApp('post_archive_template')) {
            return 0;
        }

        return vehicaApp('post_archive_template')->getId();
    }

    /**
     * @param int $templateId
     */
    public function setArchiveTemplate($templateId)
    {
        $this->setTemplate(WidgetCategory::POST_ARCHIVE, $templateId);
    }

}