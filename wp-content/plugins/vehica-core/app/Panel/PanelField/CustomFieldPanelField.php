<?php


namespace Vehica\Panel\PanelField;


use Vehica\Model\Post\Field\AttachmentsField;
use Vehica\Model\Post\Field\DateTimeField;
use Vehica\Model\Post\Field\EmbedField;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\LocationField;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Field\TextField;

/**
 * Class CustomFieldPanelField
 *
 * @package Vehica\Panel\PanelField
 */
abstract class CustomFieldPanelField extends PanelField
{
    /**
     * @var Field
     */
    protected $field;

    /**
     * CustomFieldPanelField constructor.
     *
     * @param Field $field
     * @param array $config
     * @param null $car
     */
    public function __construct(Field $field, $config = [], $car = null)
    {
        parent::__construct($config, $car);

        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->field->getKey();
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->field->getName();
    }

    /**
     * @return Field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param array $data
     *
     * @return array|false
     */
    protected function getAttributeData($data = [])
    {
        if (!isset($data['attributes'])) {
            return false;
        }

        foreach ($data['attributes'] as $attribute) {
            if ((int)$attribute['id'] === $this->getField()->getId()) {
                return $attribute;
            }
        }

        return false;
    }

    /**
     * @param Field $field
     * @param array $config
     *
     * @return bool|CustomFieldPanelField
     */
    public static function create($field, $config = [])
    {
        if ($field instanceof Taxonomy) {
            return new TaxonomyPanelField($field, $config);
        }

        if ($field instanceof PriceField) {
            return new PricePanelField($field, $config);
        }

        if ($field instanceof NumberField) {
            return new NumberPanelField($field, $config);
        }

        if ($field instanceof EmbedField) {
            return new EmbedPanelField($field, $config);
        }

        if ($field instanceof TextField) {
            return new TextPanelField($field, $config);
        }

        if ($field instanceof GalleryField) {
            return new GalleryPanelField($field, $config);
        }

        if ($field instanceof LocationField) {
            return new LocationPanelField($field, $config);
        }

        if ($field instanceof DateTimeField) {
            return new DateTimePanelField($field, $config);
        }

        if ($field instanceof AttachmentsField) {
            return new AttachmentsPanelField($field, $config);
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isRequired()
    {
        return $this->field->isRequired();
    }

    /**
     * @return bool
     */
    public function hasPlaceholder()
    {
        return $this->field->hasPanelPlaceholder();
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->field->getPanelPlaceholder();
    }

}