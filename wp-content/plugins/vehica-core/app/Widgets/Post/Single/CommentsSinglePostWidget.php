<?php


namespace Vehica\Widgets\Post\Single;


use Elementor\Controls_Manager;

/**
 * Class CommentsSinglePostWidget
 * @package Vehica\Widgets\Post\Single
 */
class CommentsSinglePostWidget extends SinglePostWidget
{
    const NAME = 'vehica_comments_single_post_widget';
    const TEMPLATE = 'post/single/comments';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Comments', 'vehica-core');
    }

    protected function register_controls()
    {
//        $this->addContentControls();

        $this->addStyleControls();
    }

    protected function addContentControls()
    {
        $this->start_controls_section(
            self::NAME . '_content'
        );


        $this->end_controls_section();
    }

    protected function addStyleControls()
    {
        $this->addHeadingStyleControls();

        $this->addAuthorStyleControls();

        $this->addPublishDateStyleControls();

        $this->addTextStyleControls();

    }

    protected function addHeadingStyleControls()
    {
        $this->start_controls_section(
            'vehica_comments_heading_style',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl(
            'vehica_comment_heading',
            '.vehica-font--comments'
        );

        $this->addTextColorControl(
            'vehica_comment_heading',
            '.vehica-font--comments'
        );

        $this->end_controls_section();
    }

    protected function addAuthorStyleControls()
    {
        $this->start_controls_section(
            'vehica_comment_author_style',
            [
                'label' => esc_html__('Author Name', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl(
            'vehica_comment_author_typography',
            '.vehica-font--author-name'
        );

        $this->addTextColorControl(
            'vehica_comment_author_color',
            '.vehica-font--author-name'
        );

        $this->end_controls_section();
    }

    protected function addPublishDateStyleControls()
    {
        $this->start_controls_section(
            'vehica_publish_date_style',
            [
                'label' => esc_html__('Publish Date', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl(
            'vehica_publish_date_typography',
            '.vehica-comment__date'
        );

        $this->addTextColorControl(
            'vehica_publish_date_color',
            '.vehica-comment__date'
        );

        $this->end_controls_section();
    }

    protected function addTextStyleControls()
    {
        $this->start_controls_section(
            'vehica_comment_text_style',
            [
                'label' => esc_html__('Comment Text', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextTypographyControl(
            'vehica_comment_text',
            '.vehica-comment__text'
        );

        $this->addTextColorControl(
            'vehica_comment_text',
            '.vehica-comment__text'
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}