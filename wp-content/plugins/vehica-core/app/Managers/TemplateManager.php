<?php

namespace Vehica\Managers;

use Elementor\Core\Base\Document;
use Vehica\Core\Manager;
use Vehica\Core\Model\Interfaces\Templatable;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\TemplateType\TemplateType;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Template\Layout;
use Vehica\Model\Post\Template\Template;
use WP_Post;
use WP_Query;

/**
 * Class TemplateManager
 * @package Vehica\Managers
 */
class TemplateManager extends Manager
{

    public function boot()
    {
        if (!(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) && current_user_can('manage_options') && is_admin()) {
            add_action('save_post', [$this, 'save']);
            add_action('admin_post_vehica_template_create', [$this, 'create']);
            add_action('admin_post_vehica_template_delete', [$this, 'delete']);
            add_action('admin_post_vehica_template_set_name', [$this, 'setName']);
            add_action('admin_post_vehica_template_set', [$this, 'setTemplate']);
            add_action('admin_post_vehica_template_save_settings', [$this, 'saveSettings']);
            add_action('admin_post_vehica_set_global_layout', [$this, 'setGlobalLayout']);
        }

        add_filter('elementor/document/urls/edit', static function ($url, Document $document) {
            $post = $document->get_main_post();

            if ($post->post_type !== 'page') {
                return $url;
            }

            $blogId = (int)get_option('page_for_posts');
            if ($post->ID !== $blogId) {
                return $url;
            }

            if (!vehicaApp('post_archive_template')) {
                return $url;
            }

            return add_query_arg(
                [
                    'post' => vehicaApp('post_archive_template')->getId(),
                    'action' => 'elementor',
                ],
                admin_url('post.php')
            );
        }, 10, 2);
    }

    /**
     * @param int $postId
     */
    public function save($postId)
    {
        $post = BasePost::getById($postId);
        $nonce = $post->getEditNonce();

        if (
            !isset($_POST[$nonce])
            || !$post instanceof Templatable
            || !wp_verify_nonce($_POST[$nonce], $nonce)
            || !current_user_can('manage_options')
        ) {
            return;
        }

        $post->getTemplateTypes()->each(static function ($templateType) use ($post) {
            /* @var $templateType TemplateType */
            $templateKey = $templateType->getKey();
            if (isset($_POST[$templateKey])) {
                $post->setTemplate($templateKey, $_POST[$templateKey]);
            }
        });
    }

    public function create()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['templateType']) || empty($_POST['templateName'])) {
            return;
        }

        $counter = 1;
        $name = $_POST['templateName'];
        $check = true;

        while ($check) {
            $check = false;
            $query = new WP_Query([
                'post_type' => Template::POST_TYPE,
                'post_status' => PostStatus::PUBLISH,
                'meta_key' => Template::TYPE,
                'meta_value' => $_POST['templateType']
            ]);

            foreach ($query->posts as $post) {
                /* @var WP_Post $post */
                if ($post->post_title === $name) {
                    $counter++;
                    $name = $_POST['templateName'] . ' #' . $counter;
                    $check = true;
                    break;
                }
            }
        }

        $templateType = $_POST['templateType'];
        $template = Template::create([
            'post_title' => $name,
            'post_status' => PostStatus::PUBLISH,
            'post_type' => Template::POST_TYPE,
            'meta_input' => [
                Template::TYPE => $templateType
            ]
        ]);

        if (is_wp_error($template)) {
            echo json_encode([
                'success' => false,
                'message' => $template->get_error_message()
            ]);
            return;
        }

        $template->prepare();

        echo json_encode([
            'success' => true,
            'template' => $template
        ]);
    }

    public function setName()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['templateId']) || empty($_POST['templateName'])) {
            return;
        }

        $templateId = (int)$_POST['templateId'];
        $templateName = trim($_POST['templateName']);
        $template = Template::getById($templateId);

        if (!$template) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Template not found', 'vehica-core')
            ]);
            return;
        }

        $templateId = $template->setTitle($templateName);
        if (is_wp_error($templateId)) {
            echo json_encode([
                'success' => false,
                'message' => $templateId->get_error_message()
            ]);
            return;
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function delete()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (empty($_POST['templateId'])) {
            return;
        }

        $templateId = (int)$_POST['templateId'];
        $template = Template::destroy($templateId);

        if (!$template) {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Something went wrong', 'vehica-core'),
            ]);
            return;
        }

        echo json_encode([
            'success' => true
        ]);
    }

    public function setTemplate()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        if (!isset($_POST['templateId']) || empty($_POST['templateType'])) {
            return;
        }

        $templateId = (int)$_POST['templateId'];
        $templateType = $_POST['templateType'];

        if ($templateType === Template::TYPE_CAR_SINGLE) {
            vehicaApp('car_config')->setSingleTemplate($templateId);
        } elseif ($templateType === Template::TYPE_CAR_ARCHIVE) {
            vehicaApp('car_config')->setArchiveTemplate($templateId);
        } elseif ($templateType === Template::TYPE_POST_SINGLE) {
            vehicaApp('post_config')->setSingleTemplate($templateId);
        } elseif ($templateType === Template::TYPE_POST_ARCHIVE) {
            vehicaApp('post_config')->setArchiveTemplate($templateId);
        } elseif ($templateType === Template::TYPE_USER) {
            vehicaApp('user_config')->setSingleTemplate($templateId);
        } else {
            echo json_encode([
                'success' => false,
                'message' => esc_html__('Invalid template type', 'vehica-core')
            ]);
            return;
        }

        echo json_encode([
            'success' => true
        ]);
    }

    private function setPreview()
    {
        if (!isset($_POST['previewId']) || empty($_POST['templateType'])) {
            return;
        }

        $templateType = $_POST['templateType'];
        $previewId = (int)$_POST['previewId'];

        if ($templateType === Template::TYPE_CAR_SINGLE) {
            vehicaApp('car_config')->setPreviewCar($previewId);
        } elseif ($templateType === Template::TYPE_POST_SINGLE) {
            vehicaApp('post_config')->setPreviewPost($previewId);
        } elseif ($templateType === Template::TYPE_USER) {
            vehicaApp('user_config')->setPreviewUser($previewId);
        }
    }

    public function saveSettings()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $this->setPreview();

        echo json_encode([
            'success' => true
        ]);
    }

    public function setGlobalLayout()
    {
        if (empty($_POST['layoutId'])) {
            return;
        }

        $layoutId = (int)$_POST['layoutId'];
        Layout::setGlobal($layoutId);

        echo json_encode(['success' => true]);
    }

}