<?php

namespace Vehica\Model\Post\Config;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\Model\Traits\Fieldable;
use Vehica\Core\Model\Traits\Templatable;
use Vehica\Core\Rewrite\Rewritable;
use Vehica\Core\Rewrite\Rewrite;
use Vehica\Core\TemplateType\TemplateType;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Template\Template;
use Vehica\Widgets\WidgetCategory;

/**
 * Class SellerConfig
 * @package Vehica\Model\Post\Config
 */
class UserConfig extends Config implements \Vehica\Core\Model\Interfaces\Templatable
{
    use Templatable;
    use Rewritable;
    use Fieldable;

    const KEY = 'vehica_user_config';

    /**
     * @var array
     */
    protected $settings = [
        Rewrite::REWRITE,
        Field::OPTION
    ];

    /**
     * @return Collection
     */
    public function getTemplateTypes()
    {
        return Collection::make([
            TemplateType::make(
                WidgetCategory::USER,
                esc_html__('User', 'vehica-core')
            )
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return esc_html__('User', 'vehica-core');
    }

    /**
     * @return bool
     */
    public function hasArchive()
    {
        return false;
    }

    /**
     * @return Template|false
     */
    public function getSingleTemplate()
    {
        $template = $this->getTemplateByTypeKey(WidgetCategory::USER);

        if (!$template) {
            return vehicaApp('user_templates')->count() > 0 ? vehicaApp('user_templates')->first() : false;
        }

        return $template;
    }

    /**
     * @param int $templateId
     */
    public function setSingleTemplate($templateId)
    {
        $this->setTemplate(WidgetCategory::USER, $templateId);
    }

    /**
     * @return int
     */
    public function getSingleTemplateId()
    {
        if (!vehicaApp('user_template')) {
            return 0;
        }

        return vehicaApp('user_template')->getId();
    }

}