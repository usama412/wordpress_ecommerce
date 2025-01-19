<?php

namespace Vehica\Model\Post\Template;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Vehica\Core\BackgroundAccentUser;
use Vehica\Model\Post\BasePost;
use WP_Post;

/**
 * Class Template
 * @package Vehica\Model\Post\Template
 */
class Template extends BasePost implements BackgroundAccentUser
{
    const POST_TYPE = 'vehica_template';
    const NOT_SET = 'not_set';

    /**
     * Template types
     */
    const TYPE = 'vehica_type';
    const TYPE_LAYOUT = 'layout';
    const TYPE_CAR_SINGLE = 'car_single';
    const TYPE_CAR_ARCHIVE = 'car_archive';
    const TYPE_POST_SINGLE = 'post_single';
    const TYPE_POST_ARCHIVE = 'post_archive';
    const TYPE_USER = 'user';

    /**
     * @var Document
     */
    protected $document;

    /**
     * Template constructor.
     *
     * @param WP_Post $post
     */
    public function __construct(WP_Post $post)
    {
        parent::__construct($post);

        $this->document = Plugin::instance()->documents->get($this->getId());
    }

    public function display()
    {
        if (is_singular(self::POST_TYPE)) {
            $this->preparePreview();

            echo apply_filters('the_content', $this->model->post_content);
            return;
        }

        setup_postdata($this->model);
        echo Plugin::instance()->frontend->get_builder_content_for_display($this->getId());
        wp_reset_postdata();
    }

    public function preparePreview()
    {
        //
    }

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
    public function getType()
    {
        return $this->getMeta(self::TYPE);
    }

    /**
     * @return bool
     */
    public function isLayout()
    {
        return $this->getType() === self::TYPE_LAYOUT;
    }

    /**
     * @return bool
     */
    public function isCarSingle()
    {
        return $this->getType() === self::TYPE_CAR_SINGLE;
    }

    /**
     * @return bool
     */
    public function isCarArchive()
    {
        return $this->getType() === self::TYPE_CAR_ARCHIVE;
    }

    /**
     * @return bool
     */
    public function isPostSingle()
    {
        return $this->getType() === self::TYPE_POST_SINGLE;
    }

    /**
     * @return bool
     */
    public function isPostArchive()
    {
        return $this->getType() === self::TYPE_POST_ARCHIVE;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->getType() === self::TYPE_USER;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->setMeta(self::TYPE, $type);
    }

    /**
     * @return array
     */
    public function getJsonData()
    {
        return [
            'elementorEditUrl' => $this->getElementorEditUrl()
        ];
    }

    /**
     * @return string
     */
    public function getElementorEditUrl()
    {
        return $this->document->get_edit_url();
    }

    /**
     * @param WP_Post $post
     *
     * @return static
     */
    public static function get(WP_Post $post)
    {
        $type = get_post_meta($post->ID, self::TYPE, true);

        if ($type === self::TYPE_LAYOUT) {
            return new Layout($post);
        }

        if ($type === self::TYPE_CAR_SINGLE) {
            return new CarSingleTemplate($post);
        }

        if ($type === self::TYPE_CAR_ARCHIVE) {
            return new CarArchiveTemplate($post);
        }

        if ($type === self::TYPE_POST_SINGLE) {
            return new PostSingleTemplate($post);
        }

        if ($type === self::TYPE_POST_ARCHIVE) {
            return new PostArchiveTemplate($post);
        }

        if ($type === self::TYPE_USER) {
            return new UserTemplate($post);
        }

        return new Template($post);
    }

    /**
     * @return Template|false
     */
    public function getLayout()
    {
        $layoutId = (int)$this->document->get_settings('vehica_layout');
        $layout = Layout::getById($layoutId);

        if (!$layout instanceof Layout) {
            return vehicaApp('global_layout');
        }

        return $layout;
    }

    /**
     * @return string
     */
    public function getBackgroundAccent()
    {
        return (string)$this->document->get_settings('vehica_background_accent');
    }

    /**
     * @return bool
     */
    public function hasBackgroundAccent()
    {
        $backgroundAccent = $this->getBackgroundAccent();

        return $backgroundAccent !== '' && $backgroundAccent !== 'none';
    }

    /**
     * @return string
     */
    public function getWidgetCategory()
    {
        return '';
    }

    public function load()
    {
        global $vehicaLayout;
        $vehicaLayout = $this->getLayout();
        if ($vehicaLayout) {
            $vehicaLayout->display();
        } else {
            $this->display();
        }
    }

    /**
     * @param int $id
     *
     * @return static|false
     */
    public static function getById($id)
    {
        return vehicaApp('templates')->find(static function ($template) use ($id) {
            /* @var Template $template */
            return $template->getId() === $id;
        });
    }

    public function prepare()
    {
        // do something after template creation
    }

}