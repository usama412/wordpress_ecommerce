<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post\Field;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Field\Attribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Field\FieldType;
use Vehica\Model\Post\BasePost;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use WP_Post;

/**
 * Class Field
 *
 * @package Vehica\Model\Post\Field
 */
abstract class Field extends BasePost
{
    const POST_TYPE = 'vehica_custom_field';
    const TYPE = 'vehica_type';
    const DEFAULT_TYPE = 'vehica_text';
    const OPTION = 'vehica_fields';
    const IS_REQUIRED = 'vehica_is_required';
    const OBJECT_TYPE = 'vehica_object_type';
    const OBJECT_TYPE_CAR = 'vehica_object_type_car';
    const OBJECT_TYPE_USER = 'vehica_object_type_user';
    const NAME = 'vehica_name';
    const PANEL_VISIBILITY = 'vehica_panel_visibility';
    const PANEL_PLACEHOLDER = 'vehica_panel_placeholder';
    const VISIBILITY = 'vehica_visibility';

    /**
     * @var array
     */
    protected $settings = [
        Field::IS_REQUIRED,
        Field::NAME,
        Field::PANEL_VISIBILITY,
        Field::VISIBILITY,
    ];

    /**
     * @return string
     */
    public function getPostTypeKey()
    {
        return self::POST_TYPE;
    }

    /**
     * @return string
     */
    public function getType()
    {
        $type = $this->getMeta(self::TYPE);

        if (empty($type)) {
            return $this->getInitialType();
        }

        return $type;
    }

    /**
     * @return string
     */
    public function getPrettyType()
    {
        return $this->getType();
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        $fieldType = vehicaApp('field_types')->find(function ($fieldType) {
            /* @var FieldType $fieldType */
            return $fieldType->getKey() === $this->getType();
        });

        if (!$fieldType instanceof FieldType) {
            return esc_html__('Unknown field type', 'vehica-core');
        }

        return $fieldType->getName();
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->setMeta(self::TYPE, $type);
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        $isRequired = $this->getMeta(self::IS_REQUIRED);

        return !empty($isRequired);
    }

    /**
     * @param int $isRequired
     */
    public function setIsRequired($isRequired)
    {
        $isRequired = (int)$isRequired;
        $this->setMeta(self::IS_REQUIRED, $isRequired);
    }

    /**
     * @return string
     */
    public function getInitialType()
    {
        return TextField::KEY;
    }

    /**
     * @param WP_Post $post
     *
     * @return static
     */
    public static function get(WP_Post $post)
    {
        $type = get_post_meta($post->ID, self::TYPE, true);

        if ($type === Taxonomy::KEY) {
            return new Taxonomy($post);
        }

        if ($type === NumberField::KEY) {
            return new NumberField($post);
        }

        if ($type === TextField::KEY) {
            return new TextField($post);
        }

        if ($type === PriceField::KEY) {
            return new PriceField($post);
        }

        if ($type === GalleryField::KEY) {
            return new GalleryField($post);
        }

        if ($type === EmbedField::KEY) {
            return new EmbedField($post);
        }

        if ($type === DateTimeField::KEY) {
            return new DateTimeField($post);
        }

        if ($type === TrueFalseField::KEY) {
            return new TrueFalseField($post);
        }

        if ($type === HeadingField::KEY) {
            return new HeadingField($post);
        }

        if ($type === LocationField::KEY) {
            return new LocationField($post);
        }

        if ($type === AttachmentsField::KEY) {
            return new AttachmentsField($post);
        }

        return new TextField($post);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'id' => $this->getId(),
            'key' => $this->getKey(),
            'name' => $this->getName(),
            'type' => $this->getType(),
            'prettyType' => $this->getPrettyType(),
            'typeName' => $this->getTypeName(),
            'isRequired' => $this->isRequired(),
            'editLink' => $this->getEditLink(),
            'rewritable' => $this instanceof RewritableField,
        ], $this->getJsonData());
    }

    /**
     * @param FieldsUser $fieldsUser
     * @param mixed $value
     */
    abstract public function save(FieldsUser $fieldsUser, $value);

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return Attribute
     */
    abstract public function getAttribute(FieldsUser $fieldsUser);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function getDisplayValue($value)
    {
        return $value;
    }

    /**
     * @param FieldsUser $fieldsUser
     *
     * @return mixed
     */
    abstract public function getValue(FieldsUser $fieldsUser);

    /**
     * @param string $objectType
     */
    public function setObjectType($objectType)
    {
        $this->setMeta(self::OBJECT_TYPE, $objectType);
    }

    /**
     * @return string
     */
    protected function getObjectType()
    {
        $objectType = $this->getMeta(self::OBJECT_TYPE);

        if (empty($objectType)) {
            return self::OBJECT_TYPE_CAR;
        }

        return $objectType;
    }

    /**
     * @return bool
     */
    public function isCarField()
    {
        return $this->getObjectType() === self::OBJECT_TYPE_CAR;
    }

    /**
     * @return bool
     */
    public function isUserField()
    {
        return $this->getObjectType() === self::OBJECT_TYPE_USER;
    }

    /**
     * @return string
     */
    public function getEditLink()
    {
        return admin_url('admin.php?page=' . $this->getKey() . '&vehica_type=field');
    }

    /**
     * @return bool
     */
    public function isCore()
    {
        $isCore = $this->getMeta('vehica_demo');

        return !empty($isCore);
    }

    /**
     * @return bool
     */
    public function isTaxonomy()
    {
        return $this->getType() === Taxonomy::KEY;
    }

    /**
     * @return string
     */
    public function getName()
    {
        $name = parent::getName();

        return (string)apply_filters('wpml_translate_single_string', $name, 'Custom field', $name);
    }

    /**
     * @param array $settings
     */
    public function setVisibility($settings)
    {
        $this->setMeta(self::VISIBILITY, $settings);
    }

    /**
     * @return array
     */
    public static function getVisibilityOptions()
    {
        $options = [
            'not_logged' => esc_html__('Not logged users', 'vehica-core'),
        ];

        foreach (vehicaApp('user_roles') as $key => $name) {
            if ($key === 'administrator') {
                continue;
            }

            $options[$key] = $name;
        }

        return $options;
    }

    /**
     * @return array|string[]
     */
    public function getVisibilitySettings()
    {
        $settings = $this->getMeta(self::VISIBILITY);

        if (!is_array($settings)) {
            return [];
        }

        return $settings;
    }

    /**
     * @param string $setting
     * @return bool
     */
    public function isVisibilitySettingSet($setting)
    {
        return in_array($setting, $this->getVisibilitySettings(), true);
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        $settings = $this->getVisibilitySettings();

        if (empty($settings) || current_user_can('manage_options')) {
            return true;
        }

        if (!is_user_logged_in()) {
            return in_array('not_logged', $settings, true);
        }

        $user = wp_get_current_user();
        if (!$user) {
            return false;
        }

        foreach ($settings as $setting) {
            if (in_array($setting, (array)$user->roles, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $settings
     */
    public function setPanelVisibility($settings)
    {
        $this->setMeta(self::PANEL_VISIBILITY, $settings);
    }

    /**
     * @return array
     */
    public static function getPanelVisibilityOptions()
    {
        $options = [
            'not_logged' => esc_html__('Not logged users', 'vehica-core'),
        ];

        foreach (vehicaApp('user_roles') as $key => $name) {
            $options[$key] = $name;
        }

        return $options;
    }

    /**
     * @return array|string[]
     */
    public function getPanelVisibilitySettings()
    {
        $settings = $this->getMeta(self::PANEL_VISIBILITY);

        if (!is_array($settings)) {
            return [];
        }

        return $settings;
    }

    /**
     * @param string $setting
     * @return bool
     */
    public function isPanelVisibilitySettingSet($setting)
    {
        return in_array($setting, $this->getPanelVisibilitySettings(), true);
    }

    /**
     * @return bool
     */
    public function showOnPanel()
    {
        $settings = $this->getPanelVisibilitySettings();

        if (empty($settings)) {
            return true;
        }

        if (!is_user_logged_in()) {
            return in_array('not_logged', $settings, true);
        }

        $user = wp_get_current_user();
        if (!$user) {
            return false;
        }

        foreach ($settings as $setting) {
            if (in_array($setting, (array)$user->roles, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $placeholder
     */
    public function setPanelPlaceholder($placeholder)
    {
        $this->setMeta(self::PANEL_PLACEHOLDER, $placeholder);
    }

    /**
     * @return string
     */
    public function getPanelPlaceholder()
    {
        $placeholder = $this->getMeta(self::PANEL_PLACEHOLDER);

        if (empty($placeholder)) {
            return '';
        }

        return $placeholder;
    }

    /**
     * @return bool
     */
    public function hasPanelPlaceholder()
    {
        return $this->getPanelPlaceholder() !== '';
    }

}
