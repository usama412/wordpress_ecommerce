<?php

namespace Vehica\Core\Model\Traits;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Model\Post\Template\Layout;
use Vehica\Model\Post\Template\Template;

/**
 * Trait Templatable
 * @package Vehica\Core\Model\Traits
 */
trait Templatable
{
    use Metadatable, Keyable;

    /**
     * @param string $templateTypeKey
     * @param int $templateId
     * @return bool
     */
    public function isTemplateSelected($templateTypeKey, $templateId)
    {
        $templateId = (int)$templateId;
        $currentTemplateId = $this->getTemplateId($templateTypeKey);
        if (empty($currentTemplateId)) {
            return false;
        }

        return $currentTemplateId === $templateId;
    }

    /**
     * @param string $templateTypeKey
     * @return int
     */
    public function getTemplateId($templateTypeKey)
    {
        $templateId = (int)$this->getMeta($templateTypeKey);

        if (empty($templateId)) {
            return 0;
        }

        return $templateId;
    }

    /**
     * @param string $templateTypeKey
     * @return Template|false
     */
    public function getTemplateByTypeKey($templateTypeKey)
    {
        $templateId = $this->getTemplateId($templateTypeKey);

        if (!$templateId) {
            return false;
        }

        return $this->getTemplate($templateId);
    }

    /**
     * @param int $templateId
     * @return Template|false
     */
    public function getTemplate($templateId)
    {
        return Template::getById($templateId);
    }

    /**
     * @param string $templateTypeKey
     * @param int $templateId
     */
    public function setTemplate($templateTypeKey, $templateId)
    {
        $templateId = (int)$templateId;
        $this->setMeta($templateTypeKey, $templateId);
    }

    /**
     * @param $templateTypeKey
     * @return bool
     */
    public function hasTemplate($templateTypeKey)
    {
        $hasTemplate = $this->getMeta($templateTypeKey);
        return !empty($hasTemplate);
    }

    /**
     * @param string $templateTypeKey
     * @param int $layoutId
     */
    public function setLayout($templateTypeKey, $layoutId)
    {
        $layoutId = (int)$layoutId;
        $this->setMeta($templateTypeKey . 'layout', $layoutId);
    }

    /**
     * @param int $templateTypeKey
     * @return int
     */
    public function getLayoutId($templateTypeKey)
    {
        return (int)$this->getMeta($templateTypeKey . 'layout');
    }

    /**
     * @param string $templateTypeKey
     * @return Layout|false
     */
    public function getLayout($templateTypeKey)
    {
        $layoutId = $this->getLayoutId($templateTypeKey);
        $layout = Layout::getById($layoutId);

        if (!$layout instanceof Layout || !$layout->isLayout()) {
            return false;
        }

        return $layout;
    }

    /**
     * @param string $templateTypeKey
     * @return bool
     */
    public function hasLayout($templateTypeKey)
    {
        return $this->getLayout($templateTypeKey) !== false;
    }

    /**
     * @return Collection
     */
    abstract public function getTemplateTypes();

}