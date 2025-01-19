<?php


namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Components\Card\Car\Card;
use Vehica\Model\Post\Car;
use Vehica\Widgets\Partials\SearchResultsPartialWidget;
use WP_Error;

/**
 * Class SearchListingGeneralWidget
 * @package Vehica\Widgets\General
 */
class SearchListingGeneralWidget extends GeneralWidget
{
    use SearchResultsPartialWidget;

    const NAME = 'vehica_search_listing_general_widget';
    const TEMPLATE = 'general/search_listing';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Inventory Classic', 'vehica-core');
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

        $this->addLimitControl();

        $this->addInitialCardControl();

        $this->addShowViewSelector();

        $this->addShowCardLabelsControl();

        $this->end_controls_section();

        $this->addSearchFieldsControls();

        $this->addSortBySection();

        $this->addRowViewSidebarSection();
    }

    private function addRowViewSidebarSection()
    {
        $this->start_controls_section(
            'row_view_sidebar',
            [
                'label' => esc_html__('Row View Sidebar', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addShowColumnControl();

        $this->addColumnContentControl();

        $this->end_controls_section();
    }

    /**
     * @return bool
     */
    public function showColumn()
    {
        $show = (int)$this->get_settings_for_display('vehica_show_column');
        return !empty($show);
    }

    private function addShowColumnControl()
    {
        $this->add_control(
            'vehica_show_column',
            [
                'label' => esc_html__('"Row View" - sidebar for desktop', 'vehica-core'),
                'label_block' => true,
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );
    }

    private function addColumnContentControl()
    {
        $this->add_control(
            'vehica_column_content',
            [
                'label' => esc_html__('Column Content for "Rows" view', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => '<div class="vehica-vertical-ad"><div><h2>' . esc_html__('Contact us', 'vehica-core') . '</h2>
                       <div>' . esc_html__('Lowest prices and the best customer service guaranteed.', 'vehica-core') . '</div>
                        <div><a class="vehica-button" href="/">' . esc_html__('Contact us', 'vehica-core') . '</a></div>
                        </div>
                        </div>',
                'condition' => [
                    'vehica_show_column' => '1'
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getColumnContent()
    {
        return (string)$this->get_settings_for_display('vehica_column_content');
    }

    protected function addInitialCardControl()
    {
        $this->add_control(
            'vehica_initial_card',
            [
                'label' => esc_html__('Initial Card Type', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    Card::TYPE_V2 => esc_html__('Card', 'vehica-core'),
                    Card::TYPE_V3 => esc_html__('Row', 'vehica-core'),
                ],
                'default' => Card::TYPE_V3
            ]
        );
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if (is_post_type_archive(Car::POST_TYPE)) {
            return vehicaApp('car_archive_url');
        }

        global $post;
        $baseUrl = get_permalink($post);

        if (!$baseUrl || $baseUrl instanceof WP_Error) {
            return vehicaApp('car_archive_url');
        }

        return $baseUrl;
    }

    protected function render()
    {
        $this->additionalParams['base_url'] = $this->getBaseUrl();

        parent::render();
    }

}