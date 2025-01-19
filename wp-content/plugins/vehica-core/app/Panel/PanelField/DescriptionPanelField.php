<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Car;

/**
 * Class DescriptionPanelField
 *
 * @package Vehica\Panel\PanelField
 */
class DescriptionPanelField extends PanelField
{
    const KEY = 'vehica_description';

    /**
     * @return string
     */
    public function getKey()
    {
        return self::KEY;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return vehicaApp('description_string');
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return 'description';
    }

    /**
     * @param Car $car
     * @param array $data
     */
    public function update(Car $car, $data = [])
    {
        $description = isset($data['description']) ? $data['description'] : '';

        $car->setDescription($description);
    }

    /**
     * @return bool
     */
    public function isSingleValue()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return vehicaApp('settings_config')->isDescriptionRequired();
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function validate(array $data)
    {
        return true;
    }

    /**
     * @return array
     */
    public function getEditorConfig()
    {
        return apply_filters('vehica/panel/description/tinymceConfig', [
            'media_buttons' => false,
            'quicktags' => false,
            'teeny' => true,
            'wpautop' => false,
            'tinymce' => [
                'plugins' => 'colorpicker, lists, fullscreen, image, wordpress, wpeditimage, wplink, textcolor',
                'toolbar1' => 'forecolor, bold, italic, underline, strikethrough, bullist, numlist, alignleft, aligncenter, alignright, undo, redo, link, fullscreen',
            ]
        ]);
    }

}