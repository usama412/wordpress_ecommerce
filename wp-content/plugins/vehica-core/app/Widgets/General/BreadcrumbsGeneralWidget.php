<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Vehica\Components\Breadcrumb;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Page;
use Vehica\Model\Post\Post;
use Vehica\Model\Post\Template\CarSingleTemplate;
use Vehica\Model\Post\Template\PostSingleTemplate;
use Vehica\Model\Post\Template\Template;
use Vehica\Model\Post\Template\UserTemplate;
use Vehica\Model\Term\Term;
use Vehica\Model\User\User;

/**
 * Class BreadcrumbsGeneralWidget
 * @package Vehica\Widgets\General
 */
class BreadcrumbsGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_breadcrumbs_general_widget';
    const TEMPLATE = 'general/breadcrumbs';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Breadcrumbs', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME . '_style',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'label' => esc_html__('Typography', 'vehica-core'),
                'selector' => '{{WRAPPER}} .vehica-breadcrumbs'
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-breadcrumbs__link' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'color_hover',
            [
                'label' => esc_html__('Color Hover', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-breadcrumbs__link:hover' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'color_last',
            [
                'label' => esc_html__('Color Last Element', 'vehica-core'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .vehica-breadcrumbs__last' => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @return Collection
     */
    public function getBreadcrumbs()
    {
        if ($this->isPage()) {
            return $this->setLast($this->getPage());
        }

        if ($this->isCarArchive()) {
            return $this->setLast($this->getCarArchive());
        }

        if ($this->isCarSingle()) {
            return $this->setLast($this->getCarSingle());
        }

        if ($this->isUser()) {
            return $this->setLast($this->getUser());
        }

        if ($this->isBlogSingle()) {
            return $this->setLast($this->getBlogSingle());
        }

        if ($this->isBlogCategory()) {
            return $this->setLast($this->getBlogCategory());
        }

        if ($this->isBlogTag()) {
            return $this->setLast($this->getBlogTag());
        }

        if ($this->isBlogArchive()) {
            return $this->setLast($this->getBlogArchive());
        }

        return $this->setLast(Collection::make([$this->getHomeBreadcrumb()]));
    }

    /**
     * @return Collection
     */
    private function getBlogTag()
    {
        $breadcrumbs = $this->getBlogArchive();

        $breadcrumbs[] = $this->getCurrentTermBreadcrumb();

        return $breadcrumbs;
    }

    /**
     * @return Collection
     */
    private function getBlogCategory()
    {
        $breadcrumbs = $this->getBlogArchive();

        $breadcrumbs[] = $this->getCurrentTermBreadcrumb();

        return $breadcrumbs;
    }

    /**
     * @return Breadcrumb
     */
    private function getCurrentTermBreadcrumb()
    {
        $term = get_queried_object();

        return new Breadcrumb($term->name, get_term_link($term));
    }

    /**
     * @return bool
     */
    private function isBlogCategory()
    {
        return is_category();
    }

    /**
     * @return bool
     */
    private function isBlogTag()
    {
        return is_tag();
    }

    /**
     * @return Breadcrumb
     */
    private function getBlogArchiveBreadcrumb()
    {
        return new Breadcrumb(
            vehicaApp('blog_string'),
            get_post_type_archive_link(Post::POST_TYPE)
        );
    }

    /**
     * @return Collection
     */
    private function getBlogArchive()
    {
        return Collection::make([
            $this->getHomeBreadcrumb(),
            $this->getBlogArchiveBreadcrumb()
        ]);
    }

    /**
     * @return Collection
     */
    private function getBlogSingle()
    {
        $breadcrumbs = $this->getBlogArchive();
        $post = $this->getCurrentPost();

        if (!$post) {
            return $breadcrumbs;
        }

        $breadcrumbs[] = new Breadcrumb($post->getName(), $post->getUrl());

        return $breadcrumbs;
    }

    /**
     * @return Collection
     */
    private function getCarArchive()
    {
        $breadcrumbs = Collection::make([
            $this->getHomeBreadcrumb(),
            $this->getCarArchiveBreadcrumb()
        ]);

        foreach (vehicaApp('settings_config')->getCarBreadcrumbs() as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $breadcrumbs = $breadcrumbs->merge($this->termsToBreadcrumbs($this->getSelectedTerms($taxonomy)));
        }

        return $breadcrumbs;
    }

    /**
     * @param Taxonomy $taxonomy
     *
     * @return Collection
     */
    private function getSelectedTerms(Taxonomy $taxonomy)
    {
        $terms = Collection::make();

        if (empty($_GET[$taxonomy->getRewrite()])) {
            return $terms;
        }

        $termSlugs = $_GET[$taxonomy->getRewrite()];

        if (!is_array($termSlugs)) {
            $termSlugs = [$termSlugs];
        }

        return Collection::make($termSlugs)->map(static function ($termSlug) use ($taxonomy) {
            return Term::getBySlug($termSlug, $taxonomy->getKey());
        })->filter(static function ($term) {
            return $term instanceof Term;
        });
    }

    /**
     * @return Breadcrumb
     */
    private function getCarArchiveBreadcrumb()
    {
        return new Breadcrumb(
            vehicaApp('inventory_string'),
            get_post_type_archive_link(Car::POST_TYPE)
        );
    }

    /**
     * @return Collection
     */
    private function getCarSingle()
    {
        $car = $this->getCurrentCar();

        $breadcrumbs = Collection::make([
            $this->getHomeBreadcrumb(),
            $this->getCarArchiveBreadcrumb()
        ]);

        if (!$car) {
            return $breadcrumbs;
        }

        foreach (vehicaApp('settings_config')->getCarBreadcrumbs() as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $breadcrumbs = $breadcrumbs->merge($this->termsToBreadcrumbs($car->getTerms($taxonomy)));
        }

        $breadcrumbs[] = new Breadcrumb($car->getName(), $car->getUrl());

        return $this->setLast($breadcrumbs);
    }

    /**
     * @param Collection $terms
     *
     * @return Collection
     */
    private function termsToBreadcrumbs($terms)
    {
        return $terms->map(static function ($term) {
            /* @var Term $term */
            return new Breadcrumb($term->getName(), $term->getUrl());
        });
    }

    /**
     * @return Collection
     */
    private function getPage()
    {
        $page = Page::getCurrent();

        return Collection::make([
            $this->getHomeBreadcrumb(),
            new Breadcrumb($page->getName(), $page->getUrl())
        ]);
    }

    /**
     * @return Breadcrumb
     */
    private function getHomeBreadcrumb()
    {
        return new Breadcrumb(
            vehicaApp('homepage_string'),
            site_url()
        );
    }

    private function isPage()
    {
        return is_singular('page');
    }

    /**
     * @return bool
     */
    private function isBlogSingle()
    {
        if (is_singular(Post::POST_TYPE)) {
            return true;
        }

        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;

        return Template::getByPost($post)->isPostSingle();
    }

    /**
     * @return bool
     */
    private function isBlogArchive()
    {
        if (is_home()) {
            return true;
        }

        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;

        return Template::getByPost($post)->isPostArchive();
    }

    /**
     * @return bool
     */
    private function isCarArchive()
    {
        if (is_post_type_archive(Car::POST_TYPE)) {
            return true;
        }

        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;

        return Template::getByPost($post)->isCarArchive();
    }

    /**
     * @return bool
     */
    private function isCarSingle()
    {
        if (is_singular(Car::POST_TYPE)) {
            return true;
        }

        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;

        return Template::getByPost($post)->isCarSingle();
    }

    /**
     * @return Collection
     */
    private function getUser()
    {
        $breadcrumbs = Collection::make([$this->getHomeBreadcrumb()]);
        $user = $this->getCurrentUser();

        if (!$user) {
            return $breadcrumbs;
        }

        $breadcrumbs[] = new Breadcrumb($user->getName(), $user->getUrl());

        return $breadcrumbs;
    }

    /**
     * @return bool
     */
    private function isUser()
    {
        if (is_author()) {
            return true;
        }

        if (!is_singular(Template::POST_TYPE)) {
            return false;
        }

        global $post;

        return Template::getByPost($post)->isUser();
    }

    /**
     * @return User|false
     */
    private function getCurrentUser()
    {
        if (is_author()) {
            $user = get_user_by('slug', get_query_var('author_name'));

            return new User($user);
        }

        global $post;
        $template = Template::getByPost($post);
        if (!$template instanceof UserTemplate) {
            return false;
        }

        $template->preparePreview();

        global $vehicaUser;

        return $vehicaUser;
    }

    /**
     * @return Post|false
     */
    private function getCurrentPost()
    {
        if (!$this->isBlogSingle()) {
            return false;
        }

        if (is_singular(Post::POST_TYPE)) {
            return Post::getCurrent();
        }

        global $post;
        $template = Template::getByPost($post);
        if (!$template instanceof PostSingleTemplate) {
            return false;
        }

        $template->preparePreview();

        global $vehicaPost;

        return $vehicaPost;
    }

    /**
     * @return Car|false
     */
    private function getCurrentCar()
    {
        if (!$this->isCarSingle()) {
            return false;
        }

        if (is_singular(Car::POST_TYPE)) {
            return Car::getCurrent();
        }

        global $post;
        $template = Template::getByPost($post);
        if (!$template instanceof CarSingleTemplate) {
            return false;
        }

        $template->preparePreview();

        global $vehicaCar;

        return $vehicaCar;
    }

    /**
     * @param Collection $breadcrumbs
     *
     * @return Collection
     */
    private function setLast(Collection $breadcrumbs)
    {
        $breadcrumbs->last(static function ($breadcrumb) {
            /* @var Breadcrumb $breadcrumb */
            $breadcrumb->setLast();
        });

        return $breadcrumbs;
    }

    /**
     * @return bool
     */
    public function hasBackToResults()
    {
        return $this->getBackToResultsUrl() !== '' && $this->isCarSingle();
    }

    /**
     * @return string
     */
    public function getBackToResultsUrl()
    {
        if (!isset($_COOKIE['vehica_results']) || empty($_COOKIE['vehica_results'])) {
            return '';
        }

        return (string)$_COOKIE['vehica_results'];
    }

}