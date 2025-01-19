<?php

namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Field\Field;
use Vehica\Panel\PanelField\CustomFieldPanelField;
use Vehica\Panel\PanelField\DescriptionPanelField;
use Vehica\Panel\PanelField\NamePanelField;
use Vehica\Panel\PaymentPackage;
use WP_Query;

/**
 * Class FrontendPanelServiceProvider
 *
 * @package Vehica\Providers
 */
class FrontendPanelServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('payment_packages', static function () {
            $query = new WP_Query([
                'post_type' => PaymentPackage::POST_TYPE,
                'post_status' => PostStatus::PUBLISH,
                'posts_per_page' => '-1'
            ]);

            return Collection::make($query->posts)->map(static function ($package) {
                return new PaymentPackage($package);
            });
        });

        $this->app->bind('valid_payment_packages', static function () {
            return vehicaApp('payment_packages')->filter(static function ($paymentPackage) {
                /* @var PaymentPackage $paymentPackage */
                return $paymentPackage->getPrice() > 0;
            })->sort(static function ($a, $b) {
                /* @var PaymentPackage $a */
                /* @var PaymentPackage $b */
                return $a->getPrice() > $b->getPrice() ? 1 : 0;
            });
        });

        $this->app->bind('selected_panel_fields', static function () {
            $fields = get_option('vehica_submit_panel_fields');

            if (!is_array($fields) || empty($fields)) {
                return vehicaApp('panel_fields');
            }

            return Collection::make($fields)->map(static function ($panelField) {
                if (!isset($panelField['key'])) {
                    return false;
                }

                if (!isset($panelField['config'])) {
                    $panelField['config'] = [];
                }

                if ($panelField['key'] === NamePanelField::KEY) {
                    return new NamePanelField($panelField['config']);
                }

                if ($panelField['key'] === DescriptionPanelField::KEY) {
                    return new DescriptionPanelField($panelField['config']);
                }

                $field = vehicaApp('car_fields')->find(static function ($field) use ($panelField) {
                    /* @var Field $field */
                    return $field->getKey() === $panelField['key'];
                });

                if (!$field) {
                    return false;
                }

                return CustomFieldPanelField::create($field, $panelField['config']);
            })->filter(static function ($panelField) {
                return $panelField !== false;
            });
        });

        $this->app->bind('panel_fields', static function () {
            return vehicaApp('car_fields')->map(static function ($field) {
                /* @var Field $field */
                if (!$field->showOnPanel()) {
                    return false;
                }

                return CustomFieldPanelField::create($field);
            })->filter(static function ($panelField) {
                return $panelField !== false;
            })->merge(Collection::make([
                new NamePanelField(),
                new DescriptionPanelField()
            ]));
        });
    }

}