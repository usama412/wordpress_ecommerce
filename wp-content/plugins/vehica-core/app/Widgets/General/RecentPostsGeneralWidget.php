<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Post;
use WP_Query;

/**
 * Class RecentPostsGeneralWidget
 * @package Vehica\Widgets\General
 */
class RecentPostsGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_recent_posts_general_widget';
    const TEMPLATE = 'general/recent_posts';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Recent Posts', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab'   => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addLimitControl();

        $this->end_controls_section();
    }

    private function addLimitControl()
    {
        $this->add_control(
            'vehica_limit',
            [
                'label'   => esc_html__('Limit', 'vehica-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => 4
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getPosts()
    {
        return Collection::make((new WP_Query([
            'post_type'      => 'post',
            'post_status'    => PostStatus::PUBLISH,
            'posts_per_page' => $this->getLimit()
        ]))->posts)->map(static function ($post) {
            return new Post($post);
        });
    }

    /**
     * @return int
     */
    private function getLimit()
    {
        return (int)$this->get_settings_for_display('vehica_limit');
    }

}