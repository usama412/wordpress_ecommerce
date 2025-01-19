<?php


namespace Vehica\Widgets\Post\Archive;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Post;
use Vehica\Widgets\Partials\PostsPartialWidget;
use WP_Query;
use WP_Term;

/**
 * Class PostsV2PostArchiveWidget
 * @package Vehica\Widgets\Post\Archive
 */
class PostsV2PostArchiveWidget extends PostArchiveWidget
{
    use PostsPartialWidget;

    const NAME = 'vehica_posts_v2_post_archive_widget';
    const TEMPLATE = 'post/archive/posts_v2';

    /**
     * @var int
     */
    protected $postsNumber = 0;

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Blog Grid V2', 'vehica-core');
    }

    protected function preparePosts()
    {
        $args = $this->getQueryPostsArgs();
        $args['paged'] = $this->getCurrentPage();
        $args['posts_per_page'] = $this->getLimit();

        $query = new WP_Query($args);

        $this->postsNumber = $query->found_posts;

        $this->posts = Collection::make($query->posts)->map(static function ($post) {
            return new Post($post);
        });
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME.'_content',
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
        $this->prepareRenderAttributes();

        $this->loadTemplate();
    }

    /**
     * @return array
     */
    protected function getQueryPostsArgs()
    {
        $args = [
            'post_type' => Post::POST_TYPE,
            'post_status' => PostStatus::PUBLISH,
        ];

        $search = get_query_var('s');
        if (!empty($search)) {
            $args['s'] = $search;
        }

        if (is_category()) {
            $category = get_category(get_query_var('cat'), false);
            if ($category instanceof WP_Term) {
                $args['category__in'] = [$category->term_id];
            }
        } elseif (is_tag()) {
            $tag = get_term_by('slug', get_query_var('tag'), 'post_tag');
            if ($tag instanceof WP_Term) {
                $args['tag__in'] = [$tag->term_id];
            }
        }

        return $args;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return (int) (isset($_GET['pagination']) ? $_GET['pagination'] : 1);
    }

    /**
     * @param  int  $page
     *
     * @return bool
     */
    public function isActivePage($page)
    {
        return $page === $this->getCurrentPage();
    }

    /**
     * @return array
     */
    public function getPages()
    {
        $pages = [];

        for ($page = 1; $page <= $this->getPageCount(); $page++) {
            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * @return float
     */
    public function getPageCount()
    {
        return ceil($this->getPostsNumber() / $this->getLimit());
    }

    /**
     * @return int
     */
    protected function getPostsNumber()
    {
        if ($this->posts === null) {
            $this->preparePosts();
        }

        return $this->postsNumber;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    /**
     * @param  int  $page
     *
     * @return string
     */
    public function getPaginationUrl($page)
    {
        $s = $_GET['s'] ?? '';
        $url = $this->getUrl().'?pagination='.$page;

        if (!empty($s)) {
            $url .= '&s='.$s;
        }

        return $url;
    }

    /**
     * @return bool
     */
    public function hasKeyword()
    {
        $keyword = $this->getKeyword();

        return !empty($keyword);
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return get_query_var('s');
    }

    /**
     * @return bool
     */
    public function hasPagination()
    {
        return $this->getPageCount() > 1;
    }

    /**
     * @return bool
     */
    public function hasPaginationNext()
    {
        return $this->getCurrentPage() < $this->getPageCount();
    }

    /**
     * @return bool
     */
    public function hasPaginationPrev()
    {
        return $this->getCurrentPage() !== 1;
    }

}