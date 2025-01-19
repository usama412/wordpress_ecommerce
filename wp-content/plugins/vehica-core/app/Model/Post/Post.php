<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Plugin;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use WP_Error;
use WP_Query;

/**
 * Class Post
 *
 * @package Vehica\Model
 */
class Post extends BasePost
{
    const KEY = 'vehica_posts';
    const POST_TYPE = 'post';

    /**
     * @var array
     */
    protected $terms = [];

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
    public static function getApiEndpoint()
    {
        return get_rest_url(null, 'wp/v2/posts');
    }

    /**
     * @return bool
     */
    private function checkIfElementorFilterApplied()
    {
        global $wp_filter;
        foreach ($wp_filter['the_content'] as $callbacks) {
            foreach ($callbacks as $callbackKey => $callback) {
                if (strpos($callbackKey, 'apply_builder_in_content') !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    public function displayContent()
    {
        $elementorFilterApplied = $this->checkIfElementorFilterApplied();

        if ($elementorFilterApplied && Plugin::instance()->editor->is_edit_mode()) {
            Plugin::instance()->frontend->remove_content_filter();
            echo apply_filters('the_content', wp_kses_post($this->model->post_content));
            Plugin::instance()->frontend->add_content_filter();
        } else {
            echo apply_filters('the_content', wp_kses_post($this->model->post_content));
        }
    }

    /**
     * @param string $size
     *
     * @return bool
     */
    public function hasImage($size = 'large')
    {
        return $this->getImageUrl($size) !== false;
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return (int)get_post_thumbnail_id($this->model);
    }

    /**
     * @param string $size
     *
     * @return bool
     */
    public function hasImageUrl($size = 'large')
    {
        return $this->getImageUrl($size) !== false;
    }

    /**
     * @param string $size
     *
     * @return string|false
     */
    public function getImageUrl($size)
    {
        $imageId = get_post_thumbnail_id($this->model);

        if (empty($imageId)) {
            return false;
        }

        return vehicaApp('image_url', $imageId, $size);
    }

    protected function prepareTerms(Taxonomy $taxonomy)
    {
        $terms = wp_get_post_terms($this->getId(), $taxonomy->getKey());
        $this->terms[$taxonomy->getKey()] = Collection::make($terms)->map(static function ($term) {
            return new Term($term);
        });
    }

    /**
     * @param Taxonomy $taxonomy
     *
     * @return bool
     */
    public function hasTerms(Taxonomy $taxonomy)
    {
        return $this->getTerms($taxonomy)->isNotEmpty();
    }

    /**
     * @param Taxonomy $taxonomy
     *
     * @return Collection
     */
    public function getTerms(Taxonomy $taxonomy)
    {
        $taxonomyKey = $taxonomy->getKey();

        if (!isset($this->terms[$taxonomyKey])) {
            $this->prepareTerms($taxonomy);
        }

        return $this->terms[$taxonomyKey];
    }

    /**
     * @return static|false
     */
    public static function first()
    {
        $query = new WP_Query([
            'post_type' => static::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'orderby' => 'title',
            'order' => 'ASC',
        ]);

        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($query->posts as $post) {
            return new static ($post);
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function getTags()
    {
        $tags = wp_get_post_tags($this->getId());

        if (is_wp_error($tags)) {
            return Collection::make();
        }

        return Collection::make($tags)->map(static function ($tag) {
            return new Term($tag);
        });
    }

    /**
     * @return Collection
     */
    public function getCategories()
    {
        $categories = wp_get_post_categories($this->getId(), [
            'fields' => 'all'
        ]);

        if (is_wp_error($categories)) {
            return Collection::make();
        }

        return Collection::make($categories)->map(static function ($category) {
            return new Term($category);
        });
    }

    /**
     * @return bool
     */
    public function hasComments()
    {
        $comments = get_comments([
            'post_id' => $this->getId()
        ]);

        return count($comments) > 0;
    }

    /**
     * @return array|int
     */
    public function getComments()
    {
        return get_comments([
            'post_id' => $this->getId(),
            'status' => 'approve',
            'include_unapproved' => array(
                is_user_logged_in() ? get_current_user_id() : $this->getUnapprovedCommentAuthorEmail()
            )
        ]);
    }

    /**
     * @return string
     */
    private function getUnapprovedCommentAuthorEmail()
    {
        $email = '';

        if (!empty($_GET['unapproved']) && !empty($_GET['moderation-hash'])) {
            $commentId = (int)$_GET['unapproved'];
            $comment = get_comment($commentId);

            if ($comment && hash_equals($_GET['moderation-hash'], wp_hash($comment->comment_date_gmt))) {
                $email = $comment->comment_author_email;
            }
        }

        if (!$email) {
            $commenter = wp_get_current_commenter();
            $email = $commenter['comment_author_email'];
        }

        return $email;
    }

    /**
     * @return int|string
     */
    public function getCommentsCount()
    {
        return get_comments_number($this->getPost());
    }

    /**
     * @param int $userId
     *
     * @return int|WP_Error
     */
    public function setUser($userId)
    {
        return wp_update_post([
            'ID' => $this->getId(),
            'post_author' => $userId,
        ]);
    }

}