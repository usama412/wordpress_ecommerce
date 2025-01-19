<?php


namespace Vehica\Widgets\Car\Single;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\AttachmentsField;

/**
 * Class AttachmentsSingleCarWidget
 * @package Vehica\Widgets\Car\Single
 */
class AttachmentsSingleCarWidget extends SingleCarWidget
{
    const NAME = 'vehica_attachments_single_car_widget';
    const TEMPLATE = 'car/single/attachments';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Attachments', 'vehica-core');
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

        $this->addAttachmentsFieldControl();

        $this->displayLabelControl();

        $this->end_controls_section();
    }

    private function displayLabelControl()
    {
        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Display Title', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'default' => '1'
            ]
        );

        $this->add_control(
            'label',
            [
                'label' => esc_html__('Title', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => vehicaApp('attachments_string'),
                'condition' => [
                    'show_title' => '1'
                ]
            ]
        );
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        $label = (string)$this->get_settings_for_display('label');

        if (empty($label)) {
            return vehicaApp('attachments_string');
        }

        return $label;
    }

    /**
     * @return bool
     */
    public function showLabel()
    {
        return !empty((int)$this->get_settings_for_display('show_title'));
    }

    private function getAttachmentFieldOptions()
    {
        $options = [];

        foreach (vehicaApp('attachments_fields') as $attachmentsField) {
            /* @var AttachmentsField $attachmentsField */
            $options[$attachmentsField->getKey()] = $attachmentsField->getName();
        }

        return $options;
    }

    private function addAttachmentsFieldControl()
    {
        $options = $this->getAttachmentFieldOptions();

        $this->add_control(
            'attachments_field',
            [
                'label' => esc_html__('Attachment Field', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => $options,
                'default' => !empty($options) ? key($options) : null,
            ]
        );
    }

    /**
     * @return AttachmentsField|false
     */
    private function getAttachmentField()
    {
        $key = $this->get_settings_for_display('attachments_field');
        if (empty($key)) {
            return false;
        }

        return vehicaApp('attachments_fields')->find(static function ($attachmentsField) use ($key) {
            /* @var AttachmentsField $attachmentsField */
            return $attachmentsField->getKey() === $key;
        });
    }

    /**
     * @return Collection
     */
    public function getAttachments()
    {
        $car = $this->getCar();
        $attachmentsField = $this->getAttachmentField();

        if (!$car || !$attachmentsField) {
            return Collection::make();
        }

        return $attachmentsField->getDisplayValue($attachmentsField->getValue($car));
    }

    protected function render()
    {
        parent::render();

        $this->loadTemplate();
    }

}