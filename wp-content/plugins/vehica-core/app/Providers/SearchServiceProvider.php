<?php

namespace Vehica\Providers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\NumberField;
use Vehica\Search\FeaturedSearchFilter;
use Vehica\Search\QueryModifier\KeywordQueryModifier;
use Vehica\Search\QueryModifier\LimitQueryModifier;
use Vehica\Search\QueryModifier\OffsetQueryModifier;
use Vehica\Search\QueryModifier\SortQueryModifier;
use Vehica\Search\QueryModifier\UserQueryModifier;
use Vehica\Search\Searchable;
use Vehica\Search\SearchFilter;
use Vehica\Search\UrlModifier;
use Vehica\Search\UserSearchFilter;

/**
 * Class SearchServiceProvider
 *
 * @package Vehica\Providers
 */
class SearchServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('search_filters', static function () {
            $searchFilters = Collection::make([
                new UserSearchFilter(),
                new FeaturedSearchFilter(),
                new KeywordQueryModifier(),
            ]);

            vehicaApp('car_fields')->each(static function ($field) use (&$searchFilters) {
                /* @var Field $field */
                if ($field instanceof SearchFilter) {
                    $searchFilters[] = $field;
                }
            });

            return $searchFilters;
        });

        $this->app->bind('search_fields', static function () {
            $searchFilters = Collection::make();

            vehicaApp('usable_car_fields')->each(static function ($field) use (&$searchFilters) {
                /* @var Field $field */
                if ($field instanceof Searchable) {
                    $searchFilters[] = $field;
                }
            });

            return $searchFilters;
        });

        $this->app->bind('search_query_modifiers', static function () {
            return Collection::make([
                new LimitQueryModifier(),
                new OffsetQueryModifier(),
                new SortQueryModifier(),
                new UserQueryModifier(),
            ]);
        });

        $this->app->bind('search_url_modifiers', static function () {
            $urlModifiers = Collection::make();

            vehicaApp('search_filters')->each(static function ($searchFilter) use (&$urlModifiers) {
                if ($searchFilter instanceof UrlModifier) {
                    $urlModifiers[] = $searchFilter;
                }
            });

            vehicaApp('search_query_modifiers')->each(static function ($queryModifier) use (&$urlModifiers) {
                if ($queryModifier instanceof UrlModifier) {
                    $urlModifiers[] = $queryModifier;
                }
            });

            return $urlModifiers;
        });

        $this->app->bind('sort_by_default_options', static function () {
            $options = [
                [
                    'name' => vehicaApp('relevance_best_match_string'),
                    'type' => vehicaApp('featured_rewrite')
                ],
                [
                    'name' => vehicaApp('date_listed_newest_string'),
                    'type' => vehicaApp('newest_rewrite'),
                ],
            ];

            vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof NumberField;
            })->each(static function ($numberField) use (&$options) {
                /* @var NumberField $numberField */
                $options[] = [
                    'name' => $numberField->getName() . ': ' . vehicaApp('high_to_low_string'),
                    'type' => $numberField->getRewrite() . '-' . vehicaApp('high_to_low_rewrite')
                ];

                $options[] = [
                    'name' => $numberField->getName() . ': ' . vehicaApp('low_to_high_string'),
                    'type' => $numberField->getRewrite() . '-' . vehicaApp('low_to_high_rewrite')
                ];
            });

            return $options;
        });

        $this->app->bind('sort_by_options', static function () {
            $options = [
                vehicaApp('newest_rewrite') => vehicaApp('date_listed_newest_string'),
                vehicaApp('oldest_rewrite') => vehicaApp('oldest_string'),
                vehicaApp('featured_rewrite') => vehicaApp('relevance_best_match_string'),
                vehicaApp('name_a_z_rewrite') => vehicaApp('name_a_z_string'),
                vehicaApp('name_z_a_rewrite') => vehicaApp('name_z_a_string'),
            ];

            vehicaApp('car_fields')->filter(static function ($field) {
                return $field instanceof NumberField;
            })->each(static function ($numberField) use (&$options) {
                /* @var NumberField $numberField */
                $key = $numberField->getRewrite() . '-' . vehicaApp('high_to_low_rewrite');
                $options[$key] = sprintf(
                    esc_html__('%s: %s', 'vehica-core'),
                    $numberField->getName(),
                    vehicaApp('high_to_low_string')
                );

                $key = $numberField->getRewrite() . '-' . vehicaApp('low_to_high_rewrite');
                $options[$key] = sprintf(
                    esc_html__('%s: %s', 'vehica-core'),
                    $numberField->getName(),
                    vehicaApp('low_to_high_string')
                );
            });

            return $options;
        });
    }

}