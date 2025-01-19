<?php

namespace Vehica\Core\Model\Interfaces;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Model\Post\Template\Template;

/**
 * Interface Templatable
 * @package Vehica\Core\Model\Interfaces
 */
interface Templatable
{
    /**
     * @return bool
     */
    public function hasArchive();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $templateTypeKey
     * @param int $templateId
     * @return bool
     */
    public function isTemplateSelected($templateTypeKey, $templateId);

    /**
     * @param string $templateTypeKey
     * @return bool|int
     */
    public function getTemplateId($templateTypeKey);

    /**
     * @param string $templateTypeKey
     * @return Template
     * @throws \Exception
     */
    public function getTemplateByTypeKey($templateTypeKey);

    /**
     * @param int $templateId
     * @return Template
     * @throws \Exception
     */
    public function getTemplate($templateId);

    /**
     * @param string $templateTypeKey
     * @param string $templateId
     */
    public function setTemplate($templateTypeKey, $templateId);

    /**
     * @param $templateTypeKey
     * @return bool
     */
    public function hasTemplate($templateTypeKey);

    /**
     * @return Collection
     */
    public function getTemplateTypes();

}