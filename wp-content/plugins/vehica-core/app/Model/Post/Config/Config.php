<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Config;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Model\Post\BasePost;
use WP_Post;

/**
 * Class Config
 * @package Vehica\Model\Post\Config
 */
abstract class Config extends BasePost
{
    const POST_TYPE = 'vehica_config';
    const TYPE = 'vehica_config_type';

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
     * @param WP_Post $post
     * @return static|false
     */
    public static function get(WP_Post $post)
    {
        $type = get_post_meta($post->ID, self::TYPE, true);

        if ($type === CarConfig::KEY) {
            return new CarConfig($post);
        }

        if ($type === PostConfig::KEY) {
            return new PostConfig($post);
        }

        if ($type === UserConfig::KEY) {
            return new UserConfig($post);
        }

        if ($type === SettingsConfig::KEY) {
            return new SettingsConfig($post);
        }

        return false;
    }

}