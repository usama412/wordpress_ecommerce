<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Term\Term;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\SwiperPartialWidget;

/**
 * Class TermCarouselGeneralWidget
 * @package Vehica\Widgets\General
 */
class TermCarouselGeneralWidget extends GeneralWidget
{
    use SwiperPartialWidget;

    const NAME = 'vehica_term_carousel_general_widget';
    const TEMPLATE = 'general/term_carousel';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Term Carousel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME,
            [
                'label' => esc_html__('Term Carousel', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addTaxonomyControl();

        vehicaApp('taxonomies')->each(function ($taxonomy) {
            $this->addTermControls($taxonomy);
        });

        $this->addImageSizeControl('large');

        $this->end_controls_section();
    }

    private function addTaxonomyControl()
    {
        $taxonomies = [];
        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var Taxonomy $taxonomy */
            $taxonomies[$taxonomy->getKey()] = $taxonomy->getName();
        }

        $this->add_control(
            'vehica_taxonomy',
            [
                'label' => esc_html__('Taxonomy', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $taxonomies,
                'default' => !empty($taxonomies) ? key($taxonomies) : null
            ]
        );
    }

    private function addTermControls(Taxonomy $taxonomy)
    {
        $repeater = new Repeater();
        $repeater->add_control(
            'term',
            [
                'label' => esc_html__('Term', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => $taxonomy->getApiEndpoint(),
            ]
        );
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__('Image', 'vehica-core'),
                'type' => Controls_Manager::MEDIA
            ]
        );

        $this->add_control(
            'vehica_terms_' . $taxonomy->getKey(),
            [
                'label' => esc_html__('Terms', 'vehica-core'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'prevent_empty' => false,
                'condition' => [
                    'vehica_taxonomy' => $taxonomy->getKey()
                ]
            ]
        );
    }

    /**
     * @return string
     */
    private function getTaxonomy()
    {
        return (string)$this->get_settings_for_display('vehica_taxonomy');
    }

    /**
     * @return Collection
     */
    public function getTerms()
    {
        $termsData = $this->get_settings_for_display('vehica_terms_' . $this->getTaxonomy());

        if (!is_array($termsData) || empty($termsData)) {
            return Collection::make();
        }

        $imageSize = $this->getImageSize();

        return Collection::make($termsData)->map(static function ($data) use ($imageSize) {
            $termId = (int)$data['term'];
            $term = Term::getById($termId);
            if (!$term) {
                return false;
            }

            if (empty($data['image']['id'])) {
                $image = $data['image']['url'];
            } else {
                $image = wp_get_attachment_image_url($data['image']['id'], $imageSize);
            }

            return [
                'name' => $term->getName(),
                'url' => $term->getUrl(),
                'image' => $image
            ];
        })->filter(static function ($term) {
            return $term !== false;
        });
    }

    /**
     * @return array
     */
    public function getBreakpoints()
    {
        return [
            [
                'width' => 1300,
                'number' => 6
            ],
            [
                'width' => 1024,
                'number' => 4
            ],
            [
                'width' => 760,
                'number' => 3
            ],
            [
                'width' => 450,
                'number' => 2
            ],
            [
                'width' => 0,
                'number' => 1
            ],
        ];
    }

}