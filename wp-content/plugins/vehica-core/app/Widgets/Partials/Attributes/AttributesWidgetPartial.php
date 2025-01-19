<?php

namespace Vehica\Widgets\Partials\Attributes;

if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Vehica\Widgets\Partials\WidgetPartial;
use Vehica\Core\Collection;

/**
 * Trait AttributesElementPartial
 * @package Vehica\Widgets\Partials\Attributes
 */
trait AttributesWidgetPartial
{
    use WidgetPartial;

    /**
     * @var Collection|null
     */
    private $attributes;

    /**
     * @return int
     */
    protected function getAttributesLimit()
    {
        return (int)$this->get_settings_for_display(BaseAttributesWidget::LIMIT);
    }

    /**
     * @return bool
     */
    public function showAllAttributes()
    {
        $showAll = $this->get_settings_for_display(BaseAttributesWidget::SHOW_ALL);
        if (!empty($showAll)) {
            return true;
        }

        $limit = $this->getAttributesLimit();
        $attributes = $this->getAttributes();

        return count($attributes) <= $limit;
    }

    /**
     * @return Collection
     */
    abstract protected function prepareAttributes();

    /**
     * @return Collection
     */
    public function getVisibleAttributes()
    {
        if ($this->showAllAttributes()) {
            return $this->getAttributes();
        }

        $limit = $this->getAttributesLimit();
        return $this->getAttributes()->slice(0, $limit);
    }

    /**
     * @return bool
     */
    public function hasHiddenAttributes()
    {
        return !$this->showAllAttributes();
    }

    /**
     * @return Collection
     */
    public function getHiddenAttributes()
    {
        $limit = $this->getAttributesLimit();
        return $this->getAttributes()->slice($limit);
    }

    /**
     * @return string
     */
    public function getShowMoreAttributesText()
    {
        $showMoreText = $this->get_settings_for_display(BaseAttributesWidget::SHOW_MORE_TEXT);
        /* @var string $showMoreText */
        return $showMoreText;
    }

    /**
     * @return string
     */
    public function getHideMoreAttributesText()
    {
        $hideMoreText = $this->get_settings_for_display(BaseAttributesWidget::HIDE_MORE_TEXT);
        /* @var string $hideMoreText */
        return $hideMoreText;
    }

    protected function addStandardControls()
    {
        $this->add_control(
            BaseAttributesWidget::SHOW_ALL,
            [
                'label' => esc_html__('Show all attributes at start', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            BaseAttributesWidget::LIMIT,
            [
                'label' => esc_html__('Limit', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => '4',
                'condition' => [
                    BaseAttributesWidget::SHOW_ALL . '!' => '1'
                ]
            ]
        );
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        if ($this->attributes === null) {
            $this->prepareAttributes();
        }

        return $this->attributes;
    }

    /**
     * @return bool
     */
    public function hasAttributes()
    {
        if ($this->attributes === null) {
            $this->prepareAttributes();
        }

        return count($this->attributes) > 0;
    }

}