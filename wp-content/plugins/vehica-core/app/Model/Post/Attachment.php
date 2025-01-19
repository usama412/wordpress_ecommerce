<?php


namespace Vehica\Model\Post;


use Vehica\Core\Collection;
use WP_Error;
use WP_Query;

/**
 * Class Attachment
 * @package Vehica\Model\Post
 */
class Attachment extends BasePost
{
    const KEY = 'vehica_attachments';
    const POST_TYPE = 'post';

    /**
     * @return string
     */
    public function getPostTypeKey()
    {
        return self::POST_TYPE;
    }

    /**
     * @return string
     */
    public function getIconUrl()
    {
        $type = $this->getType();

        if (!$type) {
            return get_template_directory_uri() . '/assets/img/other_file_type.svg';
        }

        if ($type === 'pdf') {
            return get_template_directory_uri() . '/assets/img/pdf.svg';
        }

        if ($type === 'png') {
            return get_template_directory_uri() . '/assets/img/png.svg';
        }

        if ($type === 'jpg' || $type === 'jpeg') {
            return get_template_directory_uri() . '/assets/img/jpg.svg';
        }

        if ($type === 'doc' || $type === 'docx') {
            return get_template_directory_uri() . '/assets/img/doc.svg';
        }

        if ($type === 'zip') {
            return get_template_directory_uri() . '/assets/img/zip.svg';
        }

        if ($type === 'xls') {
            return get_template_directory_uri() . '/assets/img/xls.svg';
        }

        return get_template_directory_uri() . '/assets/img/other_file_type.svg';
    }

    /**
     * @return false|string
     */
    public function getType()
    {
        $url = $this->getUrl();
        if (!$url) {
            return false;
        }

        $type = wp_check_filetype($this->getUrl());
        if (!isset($type['ext'])) {
            return false;
        }

        return $type['ext'];
    }

    /**
     * @return false|string
     */
    public function getUrl()
    {
        $url = wp_get_attachment_url($this->getId());

        if ($url instanceof WP_Error) {
            return false;
        }

        return $url;
    }

    /**
     * @param array $ids
     * @return Collection
     */
    public static function getAttachments($ids)
    {
        $query = new WP_Query([
            'post__in' => $ids,
            'post_type' => 'attachment',
            'posts_per_page' => '-1',
            'post_status' => 'any',
            'orderby' => 'post__in'
        ]);

        return Collection::make($query->posts)->map(static function ($post) {
            return new Attachment($post);
        });
    }

}