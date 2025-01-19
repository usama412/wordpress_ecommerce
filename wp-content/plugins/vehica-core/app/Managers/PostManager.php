<?php

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Manager;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Post;

/**
 * Class PostManager
 * @package Vehica\Managers
 */
class PostManager extends Manager
{

    public function boot()
    {
        add_action('save_post', [$this, 'save'], 10, 2);

        add_action('admin_menu', [$this, 'removePageAttributes']);

        add_action('admin_post_vehica_duplicate_post', [$this, 'duplicatePost']);

        add_action('admin_post_vehica_admin_duplicate_post', [$this, 'adminDuplicatePost']);
    }

    /**
     * @param int $postId
     */
    public function save($postId)
    {
        if ($this->isAjax() && !$this->currentUserCanManageOptions()) {
            return;
        }

        $post = BasePost::getById($postId);
        if (!$post) {
            return;
        }

        $nonce = $post->getEditNonce();

        if (!isset($_POST[$nonce]) || !wp_verify_nonce($_POST[$nonce], $nonce)) {
            return;
        }

        remove_action('save_post', [$this, 'save']);

        $post->update($_POST);

        add_action('save_post', [$this, 'save'], 10, 2);
    }

    public function prepareCustomPost()
    {
        global $post;
        global $vehicaPost;
        $vehicaPost = new Car($post);
    }

    public function removePageAttributes()
    {
        remove_meta_box('pageparentdiv', 'page', 'side');
    }

    public function adminDuplicatePost()
    {
        /** @noinspection AdditionOperationOnArraysInspection */
        $params = $_POST + $_GET;
        if (empty($params['postId']) || !current_user_can('manage_options')) {
            wp_redirect(admin_url());
            exit;
        }

        $postId = (int)$params['postId'];
        $post = Post::getById($postId);
        if (!$postId) {
            wp_redirect(admin_url('edit.php?post_type=' . $post->getPostTypeKey()));
            exit;
        }

        $duplicatedPostId = $post->duplicate();
        if (!$duplicatedPostId) {
            wp_redirect(admin_url('edit.php?post_type=' . $post->getPostTypeKey()));
            exit;
        }

        $duplicatedPost = Post::getById($duplicatedPostId);
        if (!$duplicatedPost) {
            wp_redirect(admin_url('edit.php?post_type=' . $post->getPostTypeKey()));
            exit;
        }

        wp_redirect(admin_url('edit.php?post_type=' . $post->getPostTypeKey()));
        exit;
    }

    public function duplicatePost()
    {
        /** @noinspection AdditionOperationOnArraysInspection */
        $params = $_POST + $_GET;
        if (empty($params['postId']) || !current_user_can('manage_options')) {
            return;
        }

        $postId = (int)$params['postId'];
        $post = Post::getById($postId);
        if (!$postId) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $duplicatedPostId = $post->duplicate();
        if (!$duplicatedPostId) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        $duplicatedPost = Post::getById($duplicatedPostId);
        if (!$duplicatedPost) {
            echo json_encode([
                'success' => false
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'post' => $duplicatedPost
        ]);
    }

}