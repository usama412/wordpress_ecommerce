<?php

namespace Vehica\Core;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Config\Config;
use WP_Error;

/**
 * Class CreateConfig
 * @package Vehica\Core
 */
class CreateConfig
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $name;

    /**
     * CreateConfig constructor.
     * @param string $key
     * @param string $name
     */
    public function __construct($key, $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return vehicaApp('configs')->find(function ($config) {
                /* @var Config $config */
                return $config->getType() === $this->key;
            }) !== false;
    }

    /**
     * @return int|WP_Error
     */
    public function create()
    {
        return wp_insert_post([
            'post_title' => $this->name,
            'post_type' => Config::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'meta_input' => [
                Config::TYPE => $this->key
            ]
        ]);
    }

}