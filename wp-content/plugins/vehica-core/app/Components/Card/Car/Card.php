<?php


namespace Vehica\Components\Card\Car;


use Vehica\Components\CardLabel;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\Price\PriceField;

/**
 * Class Card
 *
 * @package Vehica\Components\Card\Car
 */
abstract class Card
{
    const TYPE_V1 = 'vehica_card_v1';
    const TYPE_V2 = 'vehica_card_v2';
    const TYPE_V3 = 'vehica_card_v3';
    const TYPE_V4 = 'vehica_card_v4';
    const TYPE_V5 = 'vehica_card_v5';

    /**
     * @var bool
     */
    protected $showLabels = false;

    /**
     * @return GalleryField|false
     */
    private function getGalleryField()
    {
        return vehicaApp('card_gallery_field');
    }

    /**
     * @param Car $car
     * @param string $imageSize
     *
     * @return string
     */
    public function getImage(Car $car, $imageSize = '')
    {
        $galleryField = $this->getGalleryField();
        if (!$galleryField) {
            return '';
        }

        if (empty($imageSize)) {
            $imageSize = $this->getImageSize();
        }

        $imageIds = $galleryField->getValue($car);
        if (empty($imageIds)) {
            return '';
        }

        foreach ($imageIds as $imageId) {
            if (empty($imageId)) {
                continue;
            }

            $url = wp_get_attachment_image_url($imageId, $imageSize);
            if (!empty($url)) {
                return $url;
            }
        }

        return '';
    }

    /**
     * @param Car $car
     *
     * @return int
     */
    public function getImageCount(Car $car)
    {
        $galleryField = $this->getGalleryField();

        if (!$galleryField) {
            return 0;
        }

        return count($galleryField->getValue($car));
    }

    /**
     * @return string
     */
    abstract protected function getImageSize();

    /**
     * @param Car $car
     *
     * @return string
     */
    public function getPrice(Car $car)
    {
        foreach (vehicaApp('card_price_fields') as $priceField) {
            /* @var PriceField $priceField */
            $value = $priceField->getFormattedValueByCurrency($car);
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    /**
     * @return bool
     */
    public function showLabels()
    {
        return $this->showLabels;
    }


    /**
     * @param Car $car
     */
    public function loadTemplate(Car $car)
    {
        global $vehicaCurrentCar;
        $vehicaCurrentCar = $car;
        get_template_part('templates/card/car/' . $this->getTemplate());
    }

    /**
     * @return string
     */
    abstract protected function getTemplate();

    /**
     * @param array $cardConfig
     *
     * @return Card
     */
    public static function create(array $cardConfig)
    {
        $card = isset($cardConfig['type']) ? $cardConfig['type'] : self::TYPE_V3;
        if (strpos($card, 'vehica_card_') === false) {
            $card = 'vehica_card_' . $card;
        }

        if ($card === self::TYPE_V1) {
            return CardV1::create($cardConfig);
        }

        if ($card === self::TYPE_V2) {
            return CardV2::create($cardConfig);
        }

        if ($card === self::TYPE_V3) {
            return CardV3::create($cardConfig);
        }

        if ($card === self::TYPE_V4) {
            return CardV4::create($cardConfig);
        }

        if ($card === self::TYPE_V5) {
            return CardV5::create($cardConfig);
        }

        return CardV1::create($cardConfig);
    }

    /**
     * @return string
     */
    public function getImagePadding()
    {
        $sizes = vehicaApp('image_sizes');
        $imageSize = $this->getImageSize();

        if (!isset($sizes[$imageSize])) {
            return '75%';
        }

        return (($sizes[$imageSize]['height'] / $sizes[$imageSize]['width']) * 100) . '%';
    }

    /**
     * @return string
     */
    abstract public function getType();

    /**
     * @param Car $car
     * @return array
     */
    public function getLabels(Car $car)
    {
        return $car->getLabels();
    }

    /**
     * @param Car $car
     * @return CardLabel|false
     */
    public function getLabel(Car $car)
    {
        return $car->getLabel();
    }

    public function loadBigCardTemplate()
    {
        get_template_part('templates/card/car/card_big_v1');
    }

}