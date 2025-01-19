<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;

/**
 * Class UploadAttachmentManager
 * @package Vehica\Managers
 */
class UploadAttachmentManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica/attachments/upload', [$this, 'upload']);

        add_action('admin_post_nopriv_vehica/attachments/upload', [$this, 'upload']);
    }

    public function upload()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_upload_attachment')) {
            return;
        }

        echo esc_html(self::uploadAttachment());
    }

    /**
     * @return int
     * @noinspection DuplicatedCode
     */
    public static function uploadAttachment()
    {
        $file = wp_handle_upload($_FILES['file'], ['test_form' => false]);

        $attachment = [
            'guid' => $file['url'],
            'post_mime_type' => $file['type'],
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($file['url'])),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attachmentId = wp_insert_attachment($attachment, $file['file']);

        if (is_wp_error($attachmentId)) {
            return 0;
        }

        update_post_meta($attachmentId, 'vehica_source', 'panel');

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attachmentData = wp_generate_attachment_metadata($attachmentId, $file['file']);
        wp_update_attachment_metadata($attachmentId, $attachmentData);

        return $attachmentId;
    }

}