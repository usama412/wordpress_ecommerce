<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Field\EmbedField;

/**
 * Class EmbedPreviewManager
 * @package Vehica\Managers
 */
class EmbedPreviewManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_nopriv_vehica_embed_preview', [$this, 'preview']);
        add_action('admin_post_vehica_embed_preview', [$this, 'preview']);

        add_filter('oembed_result', static function ($data, $url) {
            if (strpos($url, 'facebook') !== false && strpos($url, 'videos') !== false) {
                return '<iframe src="https://www.facebook.com/plugins/video.php?href=' . urlencode($url) . '" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true"></iframe>';
            }

            return $data;
        }, 10, 2);
    }

    public function preview()
    {
        if (empty($_POST['url']) || empty($_POST['fieldId'])) {
            return;
        }

        $embed = wp_oembed_get($_POST['url']);
        $fieldId = (int)$_POST['fieldId'];
        $field = vehicaApp('embed_fields')->find(static function ($embedField) use ($fieldId) {
            /* @var EmbedField $embedField */
            return $embedField->getId() === $fieldId;
        });

        if (!$field instanceof EmbedField) {
            return;
        }

        if (empty($embed) && $field->allowRawHtml()) {
            echo stripslashes_deep($_POST['url']);
            return;
        }

        echo vehica_filter($embed);
    }

}