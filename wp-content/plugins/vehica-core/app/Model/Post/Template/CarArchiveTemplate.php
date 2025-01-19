<?php

namespace Vehica\Model\Post\Template;


use Vehica\Widgets\WidgetCategory;

/**
 * Class CarArchiveTemplate
 * @package Vehica\Model\Post\Template
 */
class CarArchiveTemplate extends Template
{
    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return WidgetCategory::CAR_ARCHIVE;
    }

    public function prepare()
    {
        $this->setMeta('_elementor_edit_mode', 'builder');
        $this->setMeta('_elementor_data', json_decode('[{"id":"ac7e3e9","elType":"section","settings":[],"elements":[{"id":"30406e6","elType":"column","settings":{"_column_size":100},"elements":[{"id":"c7acecb","elType":"widget","settings":{"vehica_heading_text":"Car Archive"},"elements":[],"widgetType":"vehica_heading_general_widget"}],"isInner":false}],"isInner":false}]', true));
    }

}