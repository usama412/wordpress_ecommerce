<?php

namespace Vehica\Managers;


use Vehica\Core\Collection;
use Vehica\Core\Manager;

/**
 * Class FontsManager
 * @package Vehica\Managers
 */
class FontsManager extends Manager
{

    public function boot()
    {
        add_action('wp_enqueue_scripts', [$this, 'load'], 9999);
    }

    public function load()
    {
        Collection::make($this->getFonts())->map(function ($font) {
            if ($this->isGoogleFont($font)) {
                $this->enqueueGoogleFont($font);
            }
        });
    }

    /**
     * @return array
     */
    private function getFonts()
    {
        return array_unique([
            vehicaApp('heading_font'),
            vehicaApp('text_font'),
        ]);
    }

    /**
     * @param string $font
     * @return bool
     */
    private function isGoogleFont($font)
    {
        $path = vehicaApp('path') . '/config/fonts.php';
        /** @noinspection PhpIncludeInspection */
        $fonts = require $path;
        return in_array($font, $fonts['Google'], true);
    }

    /**
     * @param string $font
     */
    private function enqueueGoogleFont($font)
    {
        $fontName = str_replace(' ', '-', strtolower($font));
        $font = str_replace(' ', '+', $font) . ':300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
        $fontUrl = 'https://fonts.googleapis.com/css?family=' . urlencode($font);

        $subsets = [
            'ru_RU' => 'cyrillic',
            'bg_BG' => 'cyrillic',
            'he_IL' => 'hebrew',
            'el' => 'greek',
            'vi' => 'vietnamese',
            'uk' => 'cyrillic',
            'cs_CZ' => 'latin - ext',
            'ro_RO' => 'latin - ext',
            'pl_PL' => 'latin - ext',
        ];
        $locale = get_locale();

        if (isset($subsets[$locale])) {
            $fontUrl .= urlencode('&subset=' . $subsets[$locale]);
        }

        wp_enqueue_style('google-font-' . $fontName, $fontUrl, [], false);
    }

}