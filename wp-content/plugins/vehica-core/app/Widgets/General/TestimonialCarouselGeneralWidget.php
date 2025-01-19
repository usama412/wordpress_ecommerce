<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;

/**
 * Class TestimonialCarouselGeneralWidget
 * @package Vehica\Widgets\General
 */
class TestimonialCarouselGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_testimonial_carousel_general_widget';
    const TEMPLATE = 'general/testimonial_carousel';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Testimonial Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'testimonial_carousel_content',
            [
                'label' => esc_html__('General', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addNoteControl();

        $this->addHeadingControl();

        $this->addSubHeadingControl();

        $this->addTestimonialsControl();

        $this->addAutoPlayControls();

        $this->addShowBulletsControl();

        $this->addShowStarsControl();

        $this->end_controls_section();

        $this->addStyleSections();
    }

    private function addStyleSections()
    {
        $this->addSectionHeadingStyleControls();

        $this->addSectionSubHeadingStyleControls();

        $this->addNameStyleControls();

        $this->addTitleStyleControls();

        $this->addTextStyleControls();
    }

    private function addSectionHeadingStyleControls()
    {
        $this->start_controls_section(
            'testimonial_carousel_section_heading',
            [
                'label' => esc_html__('Section Heading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'section_heading',
            '.vehica-heading__title'
        );

        $this->addTextTypographyControl(
            'section_heading',
            '.vehica-heading__title'
        );

        $this->end_controls_section();
    }

    private function addSectionSubHeadingStyleControls()
    {
        $this->start_controls_section(
            'testimonial_carousel_section_subheading',
            [
                'label' => esc_html__('Section Subheading', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'section_subheading',
            '.vehica-heading__text'
        );

        $this->addTextTypographyControl(
            'section_subheading',
            '.vehica-heading__text'
        );

        $this->end_controls_section();
    }

    private function addNameStyleControls()
    {
        $this->start_controls_section(
            'testimonial_carousel_name',
            [
                'label' => esc_html__('Name', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'name',
            '.vehica-testimonial-carousel__name'
        );

        $this->addTextTypographyControl(
            'name',
            '.vehica-testimonial-carousel__name'
        );

        $this->end_controls_section();
    }

    private function addTitleStyleControls()
    {
        $this->start_controls_section(
            'testimonial_carousel_title',
            [
                'label' => esc_html__('Title', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'title',
            '.vehica-testimonial-carousel__title'
        );

        $this->addTextTypographyControl(
            'title',
            '.vehica-testimonial-carousel__title'
        );

        $this->end_controls_section();
    }

    private function addTextStyleControls()
    {
        $this->start_controls_section(
            'testimonial_carousel_text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->addTextColorControl(
            'text',
            '.vehica-testimonial-carousel__text'
        );

        $this->addTextTypographyControl(
            'text',
            '.vehica-testimonial-carousel__text'
        );

        $this->end_controls_section();
    }

    private function addHeadingControl()
    {
        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Heading', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('testimonials_string'),
                'default' => ''
            ]
        );
    }

    private function addNoteControl()
    {
        $this->add_control(
            'carousel_note',
            [
                'raw' => '<strong>' . esc_html__('Please note!', 'vehica-core') . '</strong> ' . esc_html__('Carousel needs 3+ testimonials\'to style fully correctly. If you have 1-3 Testimonials only please use another widget.', 'vehica-core'),
                'type' => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'render_type' => 'ui',
            ]
        );
    }

    private function addSubHeadingControl()
    {
        $this->add_control(
            'subheading',
            [
                'label' => esc_html__('Subheading', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
            ]
        );
    }

    /**
     * @return string
     */
    public function getSubheading()
    {
        return (string)$this->get_settings_for_display('subheading');
    }

    public function hasSubheading()
    {
        return !empty(trim($this->getSubheading()));
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        $heading = (string)$this->get_settings_for_display('heading');

        if (empty($heading)) {
            return vehicaApp('testimonials_string');
        }

        return $heading;
    }

    private function addShowBulletsControl()
    {
        $this->add_control(
            'show_bullets',
            [
                'label' => esc_html__('Display Bullets', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showBullets()
    {
        $show = (int)$this->get_settings_for_display('show_bullets');
        return !empty($show);
    }

    private function addAutoPlayControls()
    {
        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );

        $this->add_control(
            'delay',
            [
                'label' => esc_html__('Delay (ms)', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5000,
                'condition' => [
                    'autoplay' => '1'
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    private function autoPlay()
    {
        $autoPlay = (int)$this->get_settings_for_display('autoplay');
        return !empty($autoPlay);
    }

    private function getDelay()
    {
        $delay = (int)$this->get_settings_for_display('delay');

        if (empty($delay)) {
            return 4000;
        }

        return $delay;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            'autoPlay' => $this->autoPlay(),
            'delay' => $this->getDelay(),
        ];
    }

    private function addShowStarsControl()
    {
        $this->add_control(
            'show_stars',
            [
                'label' => esc_html__('Display 5 Stars', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '0'
            ]
        );
    }

    /**
     * @return bool
     */
    public function showStars()
    {
        $hideStars = (int)$this->get_settings_for_display('show_stars');
        return !empty($hideStars);
    }

    private function addTestimonialsControl()
    {
        $testimonials = new Repeater();

        $testimonials->add_control(
            'name',
            [
                'label' => esc_html__('Name', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Joe Doe', 'vehica-core'),
            ]
        );

        $testimonials->add_control(
            'title',
            [
                'label' => esc_html__('Title', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Happy Customer', 'vehica-core'),
            ]
        );

        $testimonials->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'vehica-core'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => get_template_directory_uri() . '/assets/img/user-placeholder.png',
                ],
            ]
        );

        $testimonials->add_control(
            'text',
            [
                'label' => esc_html__('Text', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_html__('Their committed sales staff strive to find the right model for every customer to suit into their lifestyle and budget.', 'vehica-core'),
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label' => esc_html__('Testimonials', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $testimonials->get_controls(),
                'default' => [
                    [
                        'name' => 'Kate Stewart',
                        'title' => 'Happy Customer',
                        'image' => [
                            'url' => get_template_directory_uri() . '/assets/img/user-photo-100x100.jpg',
                        ],
                        'text' => 'Their committed sales staff strive to find the right model for every customer to suit their lifestyle and budget. 5-star!'
                    ],
                    [
                        'name' => 'John Myers',
                        'title' => 'Happy Customer',
                        'image' => [
                            'url' => get_template_directory_uri() . '/assets/img/user-photo-100x100.jpg',
                        ],
                        'text' => 'A great experience, plenty of useful information given and no pressure put on me to buy.  Helpful and friendly service.'
                    ],
                    [
                        'name' => 'Alana Dyan',
                        'title' => 'Happy Customer',
                        'image' => [
                            'url' => get_template_directory_uri() . '/assets/img/user-photo-100x100.jpg',
                        ],
                        'text' => 'Their committed sales staff strive to find the right model for every customer to suit their lifestyle and budget. 5-star!'
                    ],
                    [
                        'name' => 'Willard Donovan',
                        'title' => 'Happy Customer',
                        'image' => [
                            'url' => get_template_directory_uri() . '/assets/img/user-photo-100x100.jpg',
                        ],
                        'text' => 'A really positive car-buying experience. No hard sell, but plenty of time to show us the car and answer all our questions.'
                    ],
                ]
            ]
        );
    }

    /**
     * @return array
     */
    public function getTestimonials()
    {
        $testimonials = $this->get_settings_for_display('testimonials');

        if (!is_array($testimonials)) {
            return [];
        }

        return $testimonials;
    }

    public function getBreakpoints()
    {
        return [
            [
                'width' => 1200,
                'number' => 3,
            ],
            [
                'width' => 900,
                'number' => 2,
            ],
            [
                'width' => 0,
                'number' => 1,
            ],
        ];
    }

}