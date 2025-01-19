<?php

namespace Vehica\Core\Rewrite;

if (!defined('ABSPATH')) {
    exit;
}

use Cocur\Slugify\Slugify;
use Vehica\Core\Model\Traits\Metadatable;

/**
 * Trait Rewritable
 * @package Vehica\Core\Rewrite
 */
trait Rewritable
{
    use Metadatable;

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    public function getSingularName()
    {
        $singularName = $this->getMeta(Rewrite::SINGULAR_NAME);
        if (!$singularName) {
            return $this->getName();
        }
        return $singularName;
    }

    /**
     * @param string $singularName
     */
    public function setSingularName($singularName)
    {
        $singularName = sanitize_text_field($singularName);
        $this->setMeta(Rewrite::SINGULAR_NAME, $singularName);
    }

    /**
     * @return string
     */
    public function getRewrite()
    {
        $rewrite = $this->getMeta(Rewrite::REWRITE);

        if (empty($rewrite)) {
            return Slugify::create()->slugify($this->getSingularName());
        }

        return $rewrite;
    }

    /**
     * @param string $rewrite
     */
    public function setRewrite($rewrite)
    {
        $rewrite = Slugify::create()->slugify($rewrite);

        if (empty($rewrite)) {
            $rewrite = $this->getRewrite();
        }

        $this->setMeta(Rewrite::REWRITE, $rewrite);
    }

    /**
     * @return string
     */
    public function getRewriteFrom()
    {
        return $this->getRewrite() . vehicaApp('from_rewrite');
    }

    /**
     * @return string
     */
    public function getRewriteTo()
    {
        return $this->getRewrite() . vehicaApp('to_rewrite');
    }

}