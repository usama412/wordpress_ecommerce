<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Post;
use Vehica\Model\Term\Term;
use Vehica\Widgets\Partials\PostsPartialWidget;
use WP_Query;

/**
 * Class RelatedPostsSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class RelatedPostsSinglePostWidget extends SinglePostWidget
{
    use PostsPartialWidget;

    const NAME = 'vehica_related_posts_single_post_widget';
    const TEMPLATE = 'post/single/related_posts';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Related Posts', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->addContentControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addColumnsControls();

        $this->addLimitControl();

        $this->addExcerptLengthControl();

        $this->addExcerptEndControl();

        $this->addShowAuthorControl();

        $this->addShowPublishDateControl();

        $this->addShowExcerptControl();

        $this->addShowReadMoreButtonControl();

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->prepareRenderAttributes();

        $this->loadTemplate();
    }

    protected function preparePosts()
    {
        $post = $this->getPost();
        if (!$post) {
            $this->posts = Collection::make();

            return;
        }

        $args = [
            'ignore_sticky_posts' => true,
            'posts_per_page' => $this->getLimit(),
            'post_type' => Post::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
            'post__not_in' => [$this->getPostId()],
            'category__in' => $post->getCategories()->map(static function ($category) {
                /* @var Term $category */
                return $category->getId();
            })->all()
        ];

        $this->posts = Collection::make((new WP_Query($args))->posts)->map(static function ($post) {
            return new Post($post);
        });
    }

}