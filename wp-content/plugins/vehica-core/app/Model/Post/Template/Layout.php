<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Template;


use Elementor\Plugin;
use Vehica\Core\Collection;
use WP_Post;

/**
 * Class Layout
 * @package Vehica\Model\Post\Template
 */
class Layout extends Template
{
    const GLOBAL_LAYOUT = 'vehica_global_layout_id';

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'elementorEditUrl' => $this->document->get_edit_url(),
            'isValid' => $this->isValid(),
        ];
    }

    public function display()
    {
        global $post;
        if ($post instanceof WP_Post && is_singular(self::POST_TYPE) && $post->ID === $this->getId()) {
            echo apply_filters('the_content', $this->model->post_content);
        } else {
            setup_postdata($this->model);
            echo Plugin::instance()->frontend->get_builder_content_for_display($this->getId());
            wp_reset_postdata();
        }
    }

    /**
     * @param int $layoutId
     */
    public static function setGlobal($layoutId)
    {
        $layoutId = (int)$layoutId;
        update_option(self::GLOBAL_LAYOUT, $layoutId, true);
    }

    /**
     * @return int
     */
    public static function getGlobalId()
    {
        return (int)get_option(self::GLOBAL_LAYOUT);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $document = Plugin::instance()->documents->get($this->getId());
        if (!$document) {
            return false;
        }

        $elements = $document->get_elements_data();
        return Collection::make($elements)->find(function ($element) {
                return $this->findTemplateContentWidget($element);
            }) !== false;
    }

    /**
     * @param array $element
     * @return bool
     */
    private function findTemplateContentWidget($element)
    {
        if (isset($element['widgetType']) && $element['widgetType'] === 'vehica_template_content') {
            return true;
        }

        if (!empty($element['elements']) && is_array($element['elements'])) {
            foreach ($element['elements'] as $el) {
                $found = $this->findTemplateContentWidget($el);
                if ($found) {
                    return true;
                }
            }
        }

        return false;
    }

    public function prepare()
    {
        $this->setMeta('_elementor_edit_mode', 'builder');
        $this->setMeta('_elementor_data', json_decode('[{"id":"396a7e6","elType":"section","settings":[],"elements":[{"id":"efbcfb5","elType":"column","settings":{"_column_size":100},"elements":[{"id":"f00f2c4","elType":"widget","settings":[],"elements":[],"widgetType":"vehica_template_content"}],"isInner":false}],"isInner":false}]', true));
    }

    /**
     * @return Template|false
     */
    public function getLayout()
    {
        return false;
    }

}