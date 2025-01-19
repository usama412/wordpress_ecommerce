<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Post;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\PostsPartialWidget;
use WP_Query;

/**
 * Class PostsGeneralWidget
 * @package Vehica\Widgets\General
 */
class PostsGeneralWidget extends GeneralWidget
{
    use PostsPartialWidget;

    const NAME = 'vehica_posts_general_widget';
    const TEMPLATE = 'general/posts';

    /**
     * @var int
     */
    protected $postsNumber = 0;

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Blog Posts', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addColumnsControls();

        $this->addLimitControl();

        $this->addCategoryControl();

        $this->addExcerptLengthControl();

        $this->addExcerptEndControl();

        $this->addShowAuthorControl();

        $this->addShowPublishDateControl();

        $this->addShowExcerptControl();

        $this->addShowReadMoreButtonControl();

        $this->end_controls_section();
    }

    protected function preparePosts()
    {
        $args = [
            'post_type' => Post::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'posts_per_page' => $this->getLimit(),
            'category__in' => $this->getCategories(),
        ];

        $query = new WP_Query($args);

        $this->postsNumber = $query->found_posts;

        $this->posts = Collection::make($query->posts)->map(static function ($post) {
            return new Post($post);
        });
    }

    protected function render()
    {
        $this->prepareRenderAttributes();

        $this->loadTemplate();
    }

    protected function addCategoryControl()
    {
        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Categories', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => get_rest_url() . 'wp/v2/categories',
                'multiple' => true
            ]
        );
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        $categories = $this->get_settings_for_display('categories');

        if (!is_array($categories)) {
            return [];
        }

        return $categories;
    }

}