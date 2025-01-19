<?php

namespace Vehica\Managers\Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Plugin;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Config\Config;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Page;
use Vehica\Model\Post\Template\CarSingleTemplate;
use Vehica\Model\Post\Template\Layout;
use Vehica\Model\Post\Template\PostSingleTemplate;
use Vehica\Model\Post\Template\UserTemplate;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\Post\Post;
use Vehica\Model\User\User;
use Vehica\Panel\PaymentPackage;
use Vehica\Widgets\Controls\SelectRemoteControl;
use WP_Post;

/**
 * Class ElementorManager
 *
 * @package Vehica\Managers\Elementor
 */
class ElementorManager extends Manager
{

    public function boot()
    {
        add_action('elementor/editor/before_enqueue_scripts', [$this, 'scripts']);

        add_filter('elementor/utils/is_post_type_support', [$this, 'isPostTypeSupported'], 10, 3);

        add_action('wp_enqueue_scripts', [$this, 'loadCss']);


        add_action('elementor/documents/register_controls', [$this, 'controls'], 1000);

        add_action('admin_init', [$this, 'settings']);

        add_action('admin_init', static function () {
            if (did_action('elementor/loaded')) {
                remove_action('admin_init', [Plugin::$instance->admin, 'maybe_redirect_to_getting_started']);
            }
        }, 1);

        add_action('wp_footer', static function () {
            if (Plugin::$instance->preview->is_preview_mode()) {
                wp_enqueue_script('vehica-elementor-editor', vehicaApp('assets_js') . 'elementorEditor.js', ['jquery'],
                    vehicaApp('version'));
            }
        });

        add_action('admin_bar_menu', static function ($adminBar) {
            if (!current_user_can('manage_options')) {
                return;
            }

            global $vehicaLayout;
            if (!$vehicaLayout instanceof Layout) {
                return;
            }

            $adminBar->add_node([
                'id' => 'vehica-edit-layout',
                'title' => sprintf(esc_html__('Edit Header & Footer (%s)', 'vehica-core'), $vehicaLayout->getName()),
                'href' => $vehicaLayout->getElementorEditUrl(),
                'meta' => [
                    'class' => 'vehica-edit-layout',
                    'title' => esc_html__('Edit Header & Footer', 'vehica-core')
                ]
            ]);
        }, 999);
    }

    public function settings()
    {
        $supportedPostTypes = get_option('elementor_cpt_support', ['page', 'post']);
        if (!in_array(Template::POST_TYPE, $supportedPostTypes, true)) {
            $supportedPostTypes[] = Template::POST_TYPE;
        }

        if (!in_array(Page::POST_TYPE, $supportedPostTypes, true)) {
            $supportedPostTypes[] = Page::POST_TYPE;
        }

        if (!in_array(Post::POST_TYPE, $supportedPostTypes, true)) {
            $supportedPostTypes[] = Post::POST_TYPE;
        }

        $supportedPostTypes = array_filter($supportedPostTypes, static function ($value) {
            return !in_array($value, [
                Config::POST_TYPE,
                Field::POST_TYPE,
                Car::POST_TYPE,
                PaymentPackage::POST_TYPE,
            ], true);
        });
        update_option('elementor_cpt_support', $supportedPostTypes);

        $disableColors = get_option('elementor_disable_color_schemes');
        if ($disableColors !== 'yes') {
            update_option('elementor_disable_color_schemes', 'yes');
        }

        $disableTypography = get_option('elementor_disable_typography_schemes');
        if ($disableTypography !== 'yes') {
            update_option('elementor_disable_typography_schemes', 'yes');
        }

        update_option('elementor_css_print_method', 'external');
        update_option('elementor_unfiltered_files_upload', '1');
    }

    /**
     * @return void
     */
    public function scripts()
    {
        wp_enqueue_script('vehica-elementor', vehicaApp('assets_js') . 'elementor.js', ['jquery'],
            vehicaApp('version'));
        wp_enqueue_script('selectize', vehicaApp('url') . '/assets/js/selectize.min.js', ['jquery'],
            vehicaApp('version'));
        wp_enqueue_style('selectize', vehicaApp('url') . '/assets/css/selectize.css');
        wp_enqueue_script('vehica-admin', vehicaApp('assets_js') . 'build.js', ['jquery', 'selectize'],
            vehicaApp('version'));
        wp_enqueue_style('vehica-elementor', vehicaApp('assets_css') . 'elementor.css',
            [], vehicaApp('version'));
    }

    /**
     * @param bool $isSupported
     * @param int $postId
     * @param string $postType
     *
     * @return bool
     * @noinspection PhpUnusedParameterInspection
     */
    public function isPostTypeSupported($isSupported, $postId, $postType)
    {
        if ($postType === Template::POST_TYPE) {
            return true;
        }

        if (in_array($postType, [
            Field::POST_TYPE,
            Car::POST_TYPE,
            Post::POST_TYPE
        ], true)
        ) {
            return false;
        }

        return $isSupported;
    }

    public function loadCss()
    {
        if (class_exists(Plugin::class)) {
            Plugin::instance()->frontend->enqueue_styles();
        }
    }

    /**
     * @param Document $document
     */
    public function controls(Document $document)
    {
        $post = $this->getCurrentPost($document);
        if (!$post
            || !in_array($post->post_type, [
                Page::POST_TYPE,
                Template::POST_TYPE,
                'elementor_library'
            ], true)
        ) {
            return;
        }

        if ($post->post_type === Page::POST_TYPE) {
            $this->addPageControls($document);

            return;
        }

        if ($post->post_type === 'elementor_library') {
            $this->addElementorTemplateControls($document);

            return;
        }

        $template = Template::getByPost($post);
        if ($template->isLayout()) {
            return;
        }

        $this->startInjection($document);

        $this->addLayoutSelectionControl($document);

        if ($template->isCarSingle()) {
            $this->addPreviewCarSelectionControl($document);
        } elseif ($template->isUser()) {
            $this->addPreviewUserSelectionControl($document);
        } elseif ($template->isPostSingle()) {
            $this->addPreviewPostSelectionControl($document);
        }

        $this->addBackgroundAccentControl($document);

        $this->endInjection($document);

        $this->removeUnnecessaryControls($document);
    }

    /**
     * @param Document $document
     */
    private function addElementorTemplateControls(Document $document)
    {
        $context = get_post_meta($document->get_main_id(), 'vehica_template_context', true);

        $this->startInjection($document);

        $this->addLayoutSelectionControl($document);

        if ($context === 'car_single') {
            $this->addPreviewCarSelectionControl($document);
        } elseif ($context === 'user') {
            $this->addPreviewUserSelectionControl($document);
        } elseif ($context === 'post_single') {
            $this->addPreviewPostSelectionControl($document);
        }

        $this->addBackgroundAccentControl($document);

        $this->endInjection($document);

        $this->removeUnnecessaryControls($document);
    }

    /**
     * @param Document $document
     */
    private function addPageControls(Document $document)
    {
        $this->startInjection($document);

        $this->addLayoutSelectionControl($document);

        $this->addBackgroundAccentControl($document);

        $this->addEnableCompareControl($document);

        $this->endInjection($document);

        $this->removeUnnecessaryControls($document);
    }

    /**
     * @param Document $document
     *
     * @return WP_Post|false
     */
    private function getCurrentPost(Document $document)
    {
        $post = $document->get_post();
        if (($postId = wp_is_post_revision($post)) !== false) {
            $post = get_post($postId);
        }

        if (!$post instanceof WP_Post) {
            return false;
        }

        return $post;
    }

    /**
     * @return array
     */
    private function getLayoutsList()
    {
        return ['0' => esc_html__('Use Global', 'vehica-core')] + vehicaApp('layouts')->toList();
    }

    /**
     * @param Document $document
     */
    private function addLayoutSelectionControl(Document $document)
    {
        $document->add_control(
            'vehica_layout',
            [
                'label' => esc_html__('Layout', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->getLayoutsList(),
                'default' => '0',
                'description' => esc_html__('Change of "Layout" will instantly save this page', 'vehica-core')
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function addBackgroundAccentControl(Document $document)
    {
        $document->add_control(
            'vehica_background_accent',
            [
                'label' => esc_html__('Background image (high resolution only 1870px+)', 'vehica-core'),
                'label_block' => 1,
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => esc_html__('None', 'vehica-core'),
                    'dots' => esc_html__('Dots', 'vehica-core'),
                ],
                'default' => 'none'
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function addEnableCompareControl(Document $document)
    {
        $document->add_control(
            'vehica_compare',
            [
                'label' => esc_html__('Enable Compare', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0',
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function addPreviewCarSelectionControl(Document $document)
    {
        $document->add_control(
            CarSingleTemplate::PREVIEW_CAR,
            [
                'label' => esc_html__('Preview Car', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Car::getApiEndpoint(),
                'description' => esc_html__('Change of "Preview Car" will instantly save this page', 'vehica-core')
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function addPreviewUserSelectionControl(Document $document)
    {
        $document->add_control(
            UserTemplate::PREVIEW_USER,
            [
                'label' => esc_html__('Preview User', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => User::getApiEndpoint(),
                'description' => esc_html__('Change of "Preview User" will instantly save this page', 'vehica-core')
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function addPreviewPostSelectionControl(Document $document)
    {
        $document->add_control(
            PostSingleTemplate::PREVIEW_POST,
            [
                'label' => esc_html__('Preview Post', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => Post::getApiEndpoint(),
                'description' => esc_html__('Change of "Preview Post" will instantly save this page', 'vehica-core')
            ]
        );
    }

    /**
     * @param Document $document
     */
    private function removeUnnecessaryControls(Document $document)
    {
        $document->remove_control('template');

        $document->remove_control('template_default_description');

        $document->remove_control('template_canvas_description');

        $document->remove_control('template_header_footer_description');

        $document->remove_control('hide_title');
    }

    /**
     * @param Document $document
     */
    private function startInjection(Document $document)
    {
        $document->start_injection([
            'of' => 'post_status'
        ]);
    }

    /**
     * @param Document $document
     */
    private function endInjection(Document $document)
    {
        $document->end_injection();
    }

}