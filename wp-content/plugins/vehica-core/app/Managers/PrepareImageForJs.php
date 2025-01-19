<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use WP_Post;

/**
 * Class PrepareImageForJs
 * @package Vehica\Managers
 */
class PrepareImageForJs extends Manager
{

    public function boot()
    {
        add_filter('wp_prepare_attachment_for_js', [$this, 'response'], 10, 3);
    }

    /**
     * @param array $response
     * @param WP_Post $attachment
     * @param array $meta
     * @return array
     */
    public function response($response, $attachment, $meta)
    {
        if (!$this->sizeExits($meta)) {
            return $response;
        }

        $response['sizes']['large'] = [
            'width' => $this->getWidth($meta),
            'height' => $this->getHeight($meta),
            'url' => $this->getUrl($attachment, $meta),
            'orientation' => $this->getOrientation($meta),
        ];

        return $response;
    }

    /**
     * @param array $meta
     * @return string
     */
    private function getOrientation($meta)
    {
        return $meta['sizes']['large']['height'] > $meta['sizes']['large']['width'] ? 'portrait' : 'landscape';
    }

    /**
     * @param WP_Post $attachment
     * @param array $meta
     * @return string
     */
    private function getUrl($attachment, $meta)
    {
        $attachmentUrl = wp_get_attachment_url($attachment->ID);
        $baseUrl = str_replace(wp_basename($attachmentUrl), '', $attachmentUrl);
        return $baseUrl . $meta['sizes']['large']['file'];
    }

    /**
     * @param array $meta
     * @return float
     */
    private function getWidth($meta)
    {
        return $meta['sizes']['large']['width'];
    }

    /**
     * @param array $meta
     * @return float
     */
    private function getHeight($meta)
    {
        return $meta['sizes']['large']['height'];
    }

    /**
     * @param array $meta
     * @return bool
     */
    private function sizeExits($meta)
    {
        return isset($meta['sizes']['large']);
    }

}