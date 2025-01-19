<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post;

if (!defined('ABSPATH')) {
    exit;
}

use DateTime;
use Exception;
use Vehica\Core\Model\Model;
use Vehica\Model\Post\Config\Config;
use Vehica\Model\Post\Field\Field;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\User\User;
use Vehica\Panel\PaymentPackage;
use WP_Error;
use WP_Post;
use WP_Query;

/**
 * Class BasePost
 *
 * @package Vehica\Model\Post
 */
abstract class BasePost extends Model
{
    /**
     * @var WP_Post
     */
    public $model;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param string $title
     *
     * @return int|WP_Error
     */
    public function setTitle($title)
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_title' => $title
        ]);
    }

    /**
     * @param string $description
     *
     * @return int|WP_Error
     */
    public function setDescription($description)
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_content' => $description
        ]);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->model->post_content;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->model->ID;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setMeta($key, $value)
    {
        return update_post_meta($this->getId(), $key, $value);
    }

    /**
     * @param string $key
     * @param bool $isSingle
     *
     * @return mixed
     */
    public function getMeta($key, $isSingle = true)
    {
        return get_post_meta($this->getId(), $key, $isSingle);
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->model->post_status;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->getStatus() === PostStatus::PUBLISH;
    }

    /**
     * @return bool
     */
    public function isPending()
    {
        return $this->getStatus() === PostStatus::PENDING;
    }

    /**
     * @return bool
     */
    public function isTrashed()
    {
        return $this->getStatus() === PostStatus::TRASH;
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->getStatus() === PostStatus::DRAFT;
    }

    /**
     * @return bool
     */
    public function isAutoDraft()
    {
        return $this->getStatus() === PostStatus::AUTO_DRAFT;
    }

    /**
     * @param int $id
     *
     * @return static|false
     */
    public static function getById($id)
    {
        if (empty($id)) {
            return false;
        }

        $post = get_post($id);

        if (!$post instanceof WP_Post) {
            return false;
        }

        return self::getByPost($post);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->model->post_title;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return get_the_excerpt($this->getModel());
    }

    /**
     * @return bool
     */
    public function hasName()
    {
        $name = $this->getName();

        return !empty($name);
    }

    /**
     * @param string $name
     *
     * @return int|false
     */
    public function setName($name)
    {
        $postId = wp_update_post([
            'ID' => $this->getId(),
            'post_title' => $name
        ]);

        if (is_wp_error($postId)) {
            return false;
        }

        return $postId;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->model->post_name;
    }

    /**
     * @param $slug
     *
     * @return int|false
     */
    public function setSlug($slug)
    {
        $postId = wp_update_post([
            'ID' => $this->getId(),
            'post_name' => $slug
        ]);

        if (is_wp_error($postId)) {
            return false;
        }

        return $postId;
    }

    /**
     * @return string|WP_Error
     */
    public function getUrl()
    {
        if ($this->isDraft() || $this->isPending()) {
            return get_preview_post_link($this->model);
        }

        return get_permalink($this->model);
    }

    /**
     * @param array $modelData
     *
     * @return static|WP_Error
     */
    public static function create($modelData = [])
    {
        $postId = wp_insert_post($modelData);

        if (is_wp_error($postId)) {
            return $postId;
        }

        return static::getById($postId);
    }

    /**
     * @param array $postData
     * @param array $settings
     */
    public function update($postData, $settings = [])
    {
        if (empty($settings)) {
            $settings = $this->getSettings();
        }

        foreach ($settings as $setting) {
            $value = array_key_exists($setting, $postData) ? $postData[$setting] : '';
            $method = 'set' . str_replace(' ', '',
                    ucwords(str_replace('_', ' ', str_replace(vehicaApp('prefix'), '', $setting))));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @return array
     */
    protected function getSettings()
    {
        return array_merge($this->settings, $this->getAdditionalSettings());
    }

    /**
     * @return array
     */
    protected function getAdditionalSettings()
    {
        return [];
    }

    /**
     * @param bool $forceDelete
     *
     * @return bool|WP_Error
     */
    public function delete($forceDelete = false)
    {
        $post = wp_delete_post($this->getId(), $forceDelete);

        if (is_wp_error($post)) {
            return $post;
        }

        if (!$post instanceof WP_Post) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return BasePost|static|false
     */
    public static function destroy($id)
    {
        $post = wp_delete_post($id);

        if (!$post) {
            return false;
        }

        return static::getByPost($post);
    }

    /**
     * @return string
     */
    public function getPublishDate()
    {
        return get_the_date(get_option('date_format'), $this->model);
    }

    /**
     * @return string
     */
    public function getLastUpdateDate()
    {
        try {
            return (new DateTime($this->model->post_modified))->format(get_option('date_format'));
        } catch (Exception $e) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return wpautop($this->model->post_content);
    }

    /**
     * @return string
     */
    public function getRawContent()
    {
        return $this->model->post_content;
    }

    /**
     * @return bool
     */
    public function hasContent()
    {
        $content = $this->getContent();

        return !empty($content);
    }

    /**
     * @return string
     */
    abstract public function getPostTypeKey();

    /**
     * @return int
     */
    public function getUserId()
    {
        return (int)$this->model->post_author;
    }

    /**
     * @return User|false
     */
    public function getUser()
    {
        if ($this->user === null) {
            $this->user = User::getById($this->getUserId());
        }

        return $this->user;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        if (!$this->hasUser()) {
            return '';
        }

        return $this->getUser()->getName();
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return $this->getUser() !== false;
    }

    /**
     * @param WP_Post $post
     *
     * @return static
     */
    public static function getByPost(WP_Post $post)
    {
        $postType = $post->post_type;

        if ($postType === Config::POST_TYPE) {
            return Config::get($post);
        }

        if ($postType === Template::POST_TYPE) {
            return Template::get($post);
        }

        if ($postType === Field::POST_TYPE) {
            return Field::get($post);
        }

        if ($postType === 'page') {
            return new Page($post);
        }

        if ($postType === 'attachment') {
            return new Image($post);
        }

        if ($postType === PaymentPackage::POST_TYPE) {
            return new PaymentPackage($post);
        }

        return new Car($post);
    }

    /**
     * @return string
     */
    public function getEditNonce()
    {
        return vehicaApp('prefix') . 'nonce_' . $this->getId();
    }

    /**
     * @return static
     */
    public static function getCurrent()
    {
        global $post;

        return new static($post);
    }

    /**
     * @return string
     */
    public function getEscapedContent()
    {
        return wp_strip_all_tags($this->model->post_content);
    }

    /**
     * @param int $wordsLimit
     * @param string|null $more
     *
     * @return string
     */
    public function getIntro($wordsLimit = 55, $more = null)
    {
        $text = $this->getEscapedContent();

        if (str_word_count($text) <= $wordsLimit) {
            return $text;
        }

        return wp_trim_words($text, $wordsLimit, $more);
    }

    /**
     * @return int|false
     * @noinspection SqlDialectInspection
     * @noinspection SqlNoDataSourceInspection
     */
    public function duplicate()
    {
        $counter = 1;
        $name = $this->getName();
        $check = true;

        while ($check) {
            $check = false;
            $query = new WP_Query([
                'post_type' => $this->getPostTypeKey(),
                'post_status' => PostStatus::PUBLISH,
            ]);

            foreach ($query->posts as $post) {
                /* @var WP_Post $post */
                if ($post->post_title === $name) {
                    $counter++;
                    $name = $this->getName() . ' #' . $counter;
                    $check = true;
                    break;
                }
            }
        }

        $postId = wp_insert_post([
            'post_title' => $name,
            'post_type' => $this->getPostTypeKey(),
            'post_status' => $this->getStatus(),
            'post_author' => $this->getUserId(),
            'post_content' => $this->model->post_content,
            'post_excerpt' => $this->model->post_excerpt,
            'post_modified' => current_time('mysql'),
        ]);

        if (is_wp_error($postId)) {
            return false;
        }

        global $wpdb;
        $meta = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=" . $this->getId());
        if (count($meta) !== 0) {
            $query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            $selectQuery = [];

            foreach ($meta as $meta_info) {
                $key = $meta_info->meta_key;
                if ($key === '_wp_old_slug') {
                    continue;
                }

                $value = addslashes($meta_info->meta_value);
                $selectQuery[] = "SELECT $postId, '$key', '$value'";
            }
            $query .= implode(" UNION ALL ", $selectQuery);
            $wpdb->query($query);
        }

        wp_update_post([
            'ID' => $postId,
            'post_title' => $name,
        ]);

        return $postId;
    }

    /**
     * @return WP_Post
     */
    public function getPost()
    {
        return $this->model;
    }

    /**
     * @return int|WP_Error
     */
    public function setPublish()
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_status' => PostStatus::PUBLISH
        ]);
    }

    /**
     * @return int|WP_Error
     */
    public function setPending()
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_status' => PostStatus::PENDING
        ]);
    }

    /**
     * @return int|WP_Error
     */
    public function setDraft()
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_status' => PostStatus::DRAFT
        ]);
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        if ($this->isPublished()) {
            return vehicaApp('active_string');
        }

        if ($this->isDraft()) {
            return vehicaApp('draft_string');
        }

        if ($this->isPending()) {
            return vehicaApp('pending_string');
        }

        return '';
    }

}