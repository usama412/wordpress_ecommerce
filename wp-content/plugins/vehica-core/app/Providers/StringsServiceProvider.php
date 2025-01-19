<?php

namespace Vehica\Providers;


use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;

/**
 * Class StringsServiceProvider
 * @package Vehica\Providers
 */
class StringsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('strings', static function () {
            /** @noinspection PhpIncludeInspection */
            $strings = require vehicaApp('config_path') . 'strings.php';
            $savedStrings = get_option('vehica_strings');
            $finalStrings = [];

            foreach ($strings as $key => $string) {
                if (!empty($savedStrings[$key])) {
                    $value = $savedStrings[$key];
                } else {
                    $value = $string;
                }

                $finalStrings[$key] = [
                    'key' => $key,
                    'name' => $string,
                    'value' => $value
                ];
            }

            usort($finalStrings, static function ($a, $b) {
                $aName = strtolower($a['name']);
                $bName = strtolower($b['name']);

                if ($aName === $bName) {
                    return 0;
                }

                return $aName > $bName ? 1 : -1;
            });

            return Collection::make($finalStrings);
        });

        vehicaApp('strings')->each(function ($string) {
            $this->app->bind($string['key'] . '_string', $string['value']);
        });

        $this->app->bind('rewrites', static function () {
            /** @noinspection PhpIncludeInspection */
            $rewrites = require vehicaApp('config_path') . 'rewrites.php';
            $savedRewrites = get_option('vehica_rewrites');
            $finalRewrites = [];

            foreach ($rewrites as $rewrite) {
                if (!empty($savedRewrites[$rewrite['key']]) && $savedRewrites[$rewrite['key']] !== '-') {
                    $value = $savedRewrites[$rewrite['key']];
                } else {
                    $value = $rewrite['value'];
                }

                $finalRewrites[$rewrite['key']] = [
                    'key' => $rewrite['key'],
                    'name' => $rewrite['name'],
                    'value' => $value
                ];
            }

            if ($finalRewrites['vehicle_single']['value'] === $finalRewrites['vehicle_archive']['value'] && vehicaApp('pretty_urls_enabled')) {
                $finalRewrites['vehicle_archive']['value'] .= 's';
            }

            return Collection::make($finalRewrites);
        });

        vehicaApp('rewrites')->each(function ($rewrite) {
            $this->app->bind($rewrite['key'] . '_rewrite', $rewrite['value']);
        });
    }

}