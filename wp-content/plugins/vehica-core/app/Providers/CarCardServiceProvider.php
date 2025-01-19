<?php


namespace Vehica\Providers;


use Vehica\Core\ServiceProvider;

/**
 * Class CarCardServiceProvider
 * @package Vehica\Providers
 */
class CarCardServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('card_price_field', static function () {
            return vehicaApp('settings_config')->getCardPriceField();
        });

        $this->app->bind('card_price_fields', static function () {
            return vehicaApp('settings_config')->getCardPriceFields();
        });

        $this->app->bind('card_gallery_field', static function () {
            return vehicaApp('settings_config')->getCardGalleryField();
        });

        $this->app->bind('card_features', static function () {
            return vehicaApp('settings_config')->getCardFeatures();
        });

        $this->app->bind('row_primary_features', static function () {
            return vehicaApp('settings_config')->getRowPrimaryFeatures();
        });

        $this->app->bind('row_secondary_features', static function () {
            return vehicaApp('settings_config')->getRowSecondaryFeatures();
        });

        $this->app->bind('card_image_size', static function () {
            return vehicaApp('settings_config')->getCardImageSize();
        });

        $this->app->bind('row_image_size', static function () {
            return vehicaApp('settings_config')->getRowImageSize();
        });

        $this->app->bind('card_label_elements', static function () {
            return vehicaApp('settings_config')->getCardLabelElements();
        });

        $this->app->bind('card_label_type', static function () {
            return vehicaApp('settings_config')->getCardLabelType();
        });

        $this->app->bind('row_location_type', static function () {
            return vehicaApp('settings_config')->getRowLocation();
        });

        $this->app->bind('row_hide_calculate', static function () {
            return vehicaApp('settings_config')->rowHideCalculate();
        });

        $this->app->bind('card_multiline_features', static function () {
            return vehicaApp('settings_config')->isCardMultilineFeaturesEnabled();
        });
    }

}