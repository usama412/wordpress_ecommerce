<?php


namespace Vehica\Widgets\Partials;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Image;
use Vehica\Model\Post\Post;

/**
 * Trait PostsPartialWidget
 * @package Vehica\Widgets\Partials
 */
trait PostsPartialWidget
{
    /**
     * @var Collection
     */
    protected $posts;

    /**
     * @var Collection
     */
    protected $images;

    protected function addColumnsControls()
    {
        $this->add_responsive_control(
            'vehica_per_row',
            [
                'label' => esc_html__('Columns', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '1of1' => esc_html__('1', 'vehica-core'),
                    '1of2' => esc_html__('2', 'vehica-core'),
                    '1of3' => esc_html__('3', 'vehica-core'),
                    '1of4' => esc_html__('4', 'vehica-core'),
                    '1of5' => esc_html__('5', 'vehica-core'),
                    '1of6' => esc_html__('6', 'vehica-core'),
                ],
                'default' => '1of3',
                'desktop_default' => '1of3',
                'tablet_default' => '1of1',
                'mobile_default' => '1of1',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'vehica_car_list_column_gap',
            [
                'label' => esc_html__('Columns Gap (px)', 'vehica-core'),
                'description' => esc_html__('Columns Gap works only if element contains more than one column', 'vehica-core'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'unit' => 'px',
                    'size' => 32,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .vehica-grid__element' => 'padding-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .vehica-grid' => ' margin-right: -{{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );
    }

    protected function addLimitControl()
    {
        $this->add_control(
            'vehica_limit',
            [
                'label' => esc_html__('Limit', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6
            ]
        );
    }

    protected function prepareRenderAttributes()
    {
        $columnClass = [
            'vehica-blog-card',
            'vehica-grid__element',
            'vehica-grid__element--' . $this->get_settings_for_display('vehica_per_row'),
            'vehica-grid__element--tablet-' . $this->get_settings_for_display('vehica_per_row_tablet'),
            'vehica-grid__element--mobile-' . $this->get_settings_for_display('vehica_per_row_mobile'),
        ];

        $this->add_render_attribute('column', 'class', implode(' ', $columnClass));
    }

    /**
     * @return Collection
     */
    public function getPosts()
    {
        if ($this->posts === null) {
            $this->preparePosts();
        }

        return $this->posts;
    }

    abstract protected function preparePosts();

    /**
     * @param Post $post
     */
    public function displayExcerpt(Post $post)
    {
        add_filter('excerpt_length', [$this, 'excerptLength'], 999);
        add_filter('excerpt_more', [$this, 'excerptEnd']);
        echo get_the_excerpt($post->getPost());
        remove_filter('excerpt_length', [$this, 'excerptLength']);
        remove_filter('excerpt_more', [$this, 'excerptEnd']);
    }

    /**
     * @return int
     */
    public function excerptLength()
    {
        return $this->getExcerptLength();
    }

    protected function addExcerptLengthControl()
    {
        $this->add_control(
            'vehica_excerpt_length',
            [
                'label' => esc_html__('Excerpt Length', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 18
            ]
        );
    }

    /**
     * @return string
     */
    public function excerptEnd()
    {
        return (string)$this->get_settings_for_display('vehica_excerpt_end');
    }

    protected function addExcerptEndControl()
    {
        $this->add_control(
            'vehica_excerpt_end',
            [
                'label' => esc_html__('Excerpt End', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => '...'
            ]
        );
    }

    /**
     * @return int
     */
    protected function getExcerptLength()
    {
        return (int)$this->get_settings_for_display('vehica_excerpt_length');
    }

    /**
     * @return bool
     */
    public function hasPosts()
    {
        return $this->getPosts()->isNotEmpty();
    }


    /**
     * @param Post $post
     * @return Image|false
     */
    public function getImageUrl(Post $post)
    {
        $imageId = $post->getImageId();
        $image = $this->images->find(static function ($image) use ($imageId) {
            /* @var Image $image */
            return $image->getId() === $imageId;
        });

        if (!$image) {
            return false;
        }

        return vehicaApp('image_url', $imageId, 'large');
    }

    /**
     * @return int
     */
    protected function getLimit()
    {
        return (int)$this->get_settings_for_display('vehica_limit');
    }

    /**
     * @param Post $post
     *
     * @return string
     */
    public function getAuthorImage(Post $post)
    {
        $user = $post->getUser();
        if (!$user) {
            return '';
        }

        return $user->getImageUrl('vehica_100_100');
    }

    /**
     * @param Post $post
     * @return string
     */
    public function hasAuthorImage(Post $post)
    {
        $user = $post->getUser();
        if (!$user) {
            return '';
        }

        return $user->getImageUrl('vehica_100_100');
    }

    protected function addShowAuthorControl()
    {
        $this->add_control(
            'show_author',
            [
                'label' => esc_html__('Display Author', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showAuthor()
    {
        $show = (int)$this->get_settings_for_display('show_author');
        return !empty($show);
    }

    protected function addShowReadMoreButtonControl()
    {
        $this->add_control(
            'show_read_more_button',
            [
                'label' => esc_html__('Display Read More Button', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showReadMoreButton()
    {
        $show = (int)$this->get_settings_for_display('show_read_more_button');
        return !empty($show);
    }

    protected function addShowPublishDateControl()
    {
        $this->add_control(
            'show_publish_date',
            [
                'label' => esc_html__('Display Publish Date', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showPublishDate()
    {
        $show = (int)$this->get_settings_for_display('show_publish_date');
        return !empty($show);
    }

    protected function addShowExcerptControl()
    {
        $this->add_control(
            'show_excerpt',
            [
                'label' => esc_html__('Display Excerpt', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showExcerpt()
    {
        $show = (int)$this->get_settings_for_display('show_excerpt');
        return !empty($show);
    }

}