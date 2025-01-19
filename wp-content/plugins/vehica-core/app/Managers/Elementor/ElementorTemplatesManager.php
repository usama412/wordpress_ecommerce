<?php

namespace Vehica\Managers\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Manager;

/**
 * Class ElementorTemplatesManager
 * @package Vehica\Managers\Elementor
 */
class ElementorTemplatesManager extends Manager
{

    public function boot()
    {
        add_action('elementor/editor/footer', [$this, 'addTemplatesManagerToEditor']);
        add_action('wp_ajax_vehica_get_template_data', [$this, 'getTemplateData']);
    }

    public function addTemplatesManagerToEditor()
    {
        require vehicaApp('views_path') . 'elementor/templates_manager.php';
    }

    public function getTemplateData()
    {
        if (empty($_POST['templateId'])) {
            wp_die();
        }

        $templateId = (int)$_POST['templateId'];
        $data = TemplateSource::make()->getTemplate($templateId);
        if (!$data) {
            wp_die();
        }

        echo json_encode($data);
        wp_die();
    }

}