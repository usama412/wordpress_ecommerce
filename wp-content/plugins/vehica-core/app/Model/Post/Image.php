<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post;


use Vehica\Core\Collection;
use WP_Post;
use WP_Query;

/**
 * Class Image
 * @package Vehica\Model\Post
 */
class Image extends Post
{
    const ALT = '_wp_attachment_image_alt';

    /**
     * @var string
     */
    protected $size = 'vehica_default_1';

    /**
     * @var string
     */
    protected $currentSize = 'vehica_default_1';

    /**
     * Image constructor.
     *
     * @param WP_Post $model
     * @param string $size
     */
    public function __construct($model, $size = 'vehica_default_1')
    {
        parent::__construct($model);

        $this->size = $size;
    }

    /**
     * @param string $size
     *
     * @return string|false
     */
    public function getUrl($size = '')
    {
        return $this->getSrc($size);
    }

    /**
     * @param string $size
     *
     * @return string|false
     */
    public function getSrc($size = '')
    {
        if (empty($size)) {
            $size = $this->size;
        }

        $this->currentSize = $size;

        $image = wp_get_attachment_image_src($this->getId(), $size);

        if (!$image || !isset($image[0])) {
            return $size === 'full' ? false : $this->getSrc('full');
        }

        return $image[0];
    }

    /**
     * @return bool
     */
    public function isVertical()
    {
        return $this->getHeight() > $this->getWidth();
    }

    /**
     * @return bool
     */
    public function isHorizontal()
    {
        return $this->getWidth() >= $this->getHeight();
    }

    /**
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @param string $size
     *
     * @return bool|string
     */
    public function getSrcset($size = '')
    {
        if (empty($size)) {
            $size = $this->size;
        }

        return vehicaApp('image_srcset', $this->getId(), $size);
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->getMeta(self::ALT);
    }

    /**
     * @return bool
     */
    public function hasAlt()
    {
        return $this->getAlt() !== '';
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        $data = wp_get_attachment_image_src($this->getId(), 'full');
        if (!isset($data[1])) {
            return 0;
        }

        return (int)$data[1];
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        $data = wp_get_attachment_image_src($this->getId(), 'full');
        if (!isset($data[2])) {
            return 0;
        }

        return (int)$data[2];
    }

    /**
     * @param array $imageIds
     *
     * @return Collection
     */
    public static function getImages($imageIds)
    {
        $query = new WP_Query([
            'post__in' => $imageIds,
            'post_type' => 'attachment',
            'posts_per_page' => '-1',
            'post_status' => 'inherit',
            'orderby' => 'post__in'
        ]);

        return Collection::make($query->posts)->map(static function ($post) {
            return new Image($post);
        });
    }

}