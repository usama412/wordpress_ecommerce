<?php


namespace Vehica\Action;


/**
 * Class AddMediaFromUrlAction
 * @package Vehica\Action
 */
class AddMediaFromUrlAction
{
    /**
     * @param $url
     * @return int|false
     */
    public static function add($url)
    {
        $uploadDirectory = wp_upload_dir();
        $imageData = file_get_contents($url);
        $filename = basename($url);

        if (wp_mkdir_p($uploadDirectory['path'])) {
            $file = $uploadDirectory['path'] . '/' . $filename;
        } else {
            $file = $uploadDirectory['basedir'] . '/' . $filename;
        }

        file_put_contents($file, $imageData);

        $fileType = wp_check_filetype($filename);

        $attachment = [
            'post_mime_type' => $fileType['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attachmentId = wp_insert_attachment($attachment, $file);
        if (is_wp_error($attachmentId)) {
            return false;
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attachmentData = wp_generate_attachment_metadata($attachmentId, $file);
        wp_update_attachment_metadata($attachmentId, $attachmentData);

        return $attachmentId;
    }

}