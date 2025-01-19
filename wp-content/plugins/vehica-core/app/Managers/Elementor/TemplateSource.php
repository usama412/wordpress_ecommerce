<?php

namespace Vehica\Managers\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\TemplateLibrary\Source_Remote;

/**
 * Class TemplateSource
 * @package Vehica\Core\Elementor
 */
class TemplateSource extends Source_Remote
{
    const SOURCE_URL = 'https://vehica.com';

    /**
     * @return TemplateSource
     */
    public static function make()
    {
        return new self;
    }

    /**
     * @param int $templateId
     * @return array|false
     */
    public function getTemplate($templateId)
    {
        $response = wp_remote_get(
            self::SOURCE_URL . '/wp-admin/admin-ajax.php?action=vehica_template&templateId=' . $templateId
        );

        if (!is_array($response)) {
            return false;
        }

        $content = json_decode($response['body'], true);
        if (empty($content)) {
            return false;
        }

        $content = $this->replace_elements_ids($content);
        $content = $this->process_export_import_content($content, 'on_import');

        return $content;
    }


}