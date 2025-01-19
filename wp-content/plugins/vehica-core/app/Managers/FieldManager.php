<?php

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Cocur\Slugify\Slugify;
use Exception;
use Vehica\Core\Manager;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\EmbedField;
use Vehica\Model\Post\Field\Field;
use Embed\Embed;
use Vehica\Model\Post\Field\RewritableField;
use Vehica\Model\Post\Field\TextField;
use WP_Post;

/**
 * Class FieldManager
 * @package Vehica\Managers
 */
class FieldManager extends Manager
{
    /**
     * TaxonomyManager constructor.
     */
    public function boot()
    {
        if (is_admin()) {
            add_filter('vehica_metaboxes', static function ($metaboxes) {
                $metaboxes[] = [
                    'name' => esc_html__('Attributes', 'vehica-core'),
                    'key' => 'attributes',
                    'context' => 'normal',
                    'post_types' => [
                        Car::POST_TYPE
                    ],
                    'config' => [],
                    'priority' => 'high'
                ];
                return $metaboxes;
            });

            add_action('save_post', [$this, 'savePostFields'], 10, 2);
            add_action('wp_ajax_vehica_embed', [$this, 'checkEmbedUrl']);
            add_action('wp_ajax_vehica_embed_preview', [$this, 'embedPreview']);

            add_action('admin_post_vehica_field_car_create', [$this, 'createCarField']);
            add_action('admin_post_vehica_field_car_delete', [$this, 'deleteCarField']);
            add_action('admin_post_vehica_field_car_update_name', [$this, 'updateCarFieldName']);
            add_action('admin_post_vehica_field_car_update_rewrite', [$this, 'updateCarFieldRewrite']);
            add_action('admin_post_vehica_field_car_update_type', [$this, 'updateCarFieldType']);
            add_action('admin_post_vehica_car_fields_update', [$this, 'updateCarFields']);

            add_action('admin_post_vehica_field_save', [$this, 'saveFieldSettings']);
        }

        if (is_admin() && current_user_can('manage_options')) {
            add_filter('enter_title_here', [$this, 'changeTitle']);
            add_filter('post_row_actions', [$this, 'removeQuickEdit'], 10, 2);
        }
    }

    /**
     * @param $actions
     * @param WP_Post $post
     * @return mixed
     */
    public function removeQuickEdit($actions, WP_Post $post)
    {
        if ($post->post_type !== Field::POST_TYPE) {
            return $actions;
        }

        if (isset($actions['inline hide-if-no-js'])) {
            unset($actions['inline hide-if-no-js']);
        }

        return $actions;
    }

    /**
     * @param string $title
     * @return string
     */
    public function changeTitle($title)
    {
        $screen = get_current_screen();

        if ($screen !== null && $screen->post_type === Field::POST_TYPE) {
            $title = esc_html__('Enter custom field name here', 'vehica-core');
        }

        return $title;
    }

    /**
     * @param int $postId
     * @param WP_Post $wpPost
     */
    public function savePostFields($postId, $wpPost)
    {
        if ($wpPost->post_type === 'page' || !current_user_can('edit_post', $postId) || wp_is_post_revision($wpPost)) {
            return;
        }

        $post = BasePost::getByPost($wpPost);

        if (
            !$post instanceof Car
            || !isset($_POST[$post->getEditNonce()])
            || !wp_verify_nonce($_POST[$post->getEditNonce()], $post->getEditNonce())
        ) {
            return;
        }
        $this->saveFields($post);

    }

    /**
     * @param FieldsUser $fieldsUser
     */
    public function saveFields(FieldsUser $fieldsUser)
    {
        vehicaApp('car_fields')->each(static function ($field) use ($fieldsUser) {
            /* @var Field $field */
            $value = isset($_POST[$field->getKey()]) ? $_POST[$field->getKey()] : '';
            $field->save($fieldsUser, $value);
        });
    }

    public function checkEmbedUrl()
    {
        if (empty($_POST['url'])) {
            wp_die();
        }
        $url = $_POST['url'];
        try {
            $embed = Embed::create($url);
        } catch (Exception $e) {
            wp_die();
        }

        foreach ($embed->getProviders() as $provider) {
            if (strpos($provider->getProviderName(), 'YouTube') !== false) {
                echo EmbedField::getYouTubeEmbed($embed, $_POST);
                wp_die();
            }
        }
        /*
         * Echo not escaped because contain oEmbed output.
         * Output might vary depends on provider so can't use wp_kses rules .
         */
        echo $embed->code;
        wp_die();
    }

    public function embedPreview()
    {
        if (empty($_POST['url']) || empty($_POST['fieldId'])) {
            wp_die();
        }

        $fieldId = (int)$_POST['fieldId'];
        $field = vehicaApp('embed_fields')->find(static function ($embedField) use ($fieldId) {
            /* @var EmbedField $embedField */
            return $embedField->getId() === $fieldId;
        });

        if (!$field instanceof EmbedField) {
            wp_die();
        }

        $embed = wp_oembed_get($_POST['url']);

        if (!$embed && strpos($_POST['url'], '.mp4') !== false) {
            echo do_shortcode('[video src="' . $_POST['url'] . '"]');
            wp_die();
        }

        if (empty($embed) && $field->allowRawHtml()) {
            echo stripslashes_deep($_POST['url']);
            wp_die();
        }

        echo vehica_filter($embed);
        wp_die();
    }

    public function createCarField()
    {
        if (!isset($_POST['fieldName'], $_POST['fieldType']) || !current_user_can('manage_options')) {
            return;
        }

        $fieldName = $_POST['fieldName'];
        $fieldType = $_POST['fieldType'];

        if (empty($fieldName)) {
            $fieldName = esc_html__('New Field', 'vehica-core');
        }

        if (empty($fieldType)) {
            $fieldType = TextField::KEY;
        }

        $field = Field::create([
            'post_title' => $fieldName,
            'post_status' => PostStatus::PUBLISH,
            'post_type' => Field::POST_TYPE,
            'meta_input' => [
                Field::OBJECT_TYPE => Field::OBJECT_TYPE_CAR,
                Field::TYPE => $fieldType
            ]
        ]);

        $isSuccess = !is_wp_error($field);
        if ($isSuccess) {
            $message = esc_html__('Field created successfully', 'vehica-core');
        } else {
            $message = $field->get_error_message();
        }

        echo json_encode([
            'success' => $isSuccess,
            'field' => $isSuccess ? $field : false,
            'message' => $message
        ]);
    }

    public function deleteCarField()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['fieldId'])) {
            return;
        }

        $fieldId = (int)$_POST['fieldId'];
        $field = Field::destroy($fieldId);

        update_option(FlushRewriteRulesManager::OPTION_KEY, 1);

        if (!$field) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Something went wrong :(', 'vehica-core')
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => sprintf(
                esc_html__('Field %s successfully deleted.', 'vehica-core'),
                $field->getName()
            )
        ]);
    }

    public function updateCarFieldName()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['fieldId']) || empty($_POST['fieldName'])) {
            return;
        }

        $fieldId = (int)$_POST['fieldId'];
        $fieldName = trim($_POST['fieldName']);
        $field = Field::getById($fieldId);

        if (!$field) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Field not found', 'vehica-core')
            ]);
            return;
        }

        $fieldId = $field->setTitle($fieldName);
        if (is_wp_error($field)) {
            echo json_encode([
                'success' => false,
                'message' => $fieldId->get_error_message()
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => esc_html__('Field name changed successfully', 'vehica-core')
        ]);
    }

    public function updateCarFieldRewrite()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['fieldId']) || empty($_POST['rewrite'])) {
            return;
        }

        $fieldId = (int)$_POST['fieldId'];
        $field = Field::getById($fieldId);
        if (!$field) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Field not found', 'vehica-core')
            ]);
            return;
        }

        $rewrite = Slugify::create()->slugify($_POST['rewrite']);
        $check = vehicaApp('rewritable_fields')->find(static function ($rewritableField) use ($rewrite, $field) {
            /* @var RewritableField $rewritableField */
            return $rewritableField->getRewrite() === $rewrite && $rewritableField->getKey() !== $field->getKey();
        });

        if ($check) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Invalid rewrite', 'vehica-core')
            ]);
            return;
        }

        if (!$this->validateRewrite($rewrite)) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Invalid rewrite', 'vehica-core')
            ]);
            return;
        }

        if (empty($rewrite)) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Invalid rewrite', 'vehica-core')
            ]);
            return;
        }

        if (!$field instanceof RewritableField) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Field type not rewritable', 'vehica-core')
            ]);
            return;
        }

        $field->setRewrite($rewrite);

        echo json_encode([
            'success' => true,
            'rewrite' => $rewrite
        ]);
    }

    /**
     * @param string $rewrite
     * @return bool
     */
    private function validateRewrite($rewrite)
    {
        return !in_array($rewrite, [
            'id',
            'p',
        ], true);
    }

    public function updateCarFieldType()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['fieldId']) || empty($_POST['fieldType'])) {
            return;
        }

        $fieldId = (int)$_POST['fieldId'];
        $field = Field::getById($fieldId);
        $fieldType = $_POST['fieldType'];

        if (!$field) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Field not found', 'vehica-core')
            ]);
            return;
        }

        $field->update([Field::TYPE => $fieldType], [Field::TYPE]);

        echo json_encode([
            'success' => true,
            'message' => esc_html__('Field type changed successfully', 'vehica-core'),
            'field' => Field::getById($fieldId)
        ]);
    }

    public function updateCarFields()
    {
        if (!isset($_POST['fields']) || !current_user_can('manage_options')) {
            return;
        }

        vehicaApp('car_config')->setFields($_POST['fields']);

        echo json_encode([
            'success' => true
        ]);
    }

    public function saveFieldSettings()
    {
        if (empty($_POST['fieldId']) || !current_user_can('manage_options')) {
            return;
        }

        $fieldId = (int)$_POST['fieldId'];
        $field = Field::getById($fieldId);
        $field->update($_POST);

        wp_redirect(admin_url('admin.php?page=vehica_panel_car_fields'));
        exit;
    }

}