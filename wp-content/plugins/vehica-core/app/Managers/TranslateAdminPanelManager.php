<?php

namespace Vehica\Managers;


use Cocur\Slugify\Slugify;
use Vehica\Core\Manager;

/**
 * Class TranslateAdminPanelManager
 * @package Vehica\Managers
 */
class TranslateAdminPanelManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_panel_translate_save', [$this, 'save']);
    }

    public function save()
    {
        if (!$this->currentUserCanManageOptions()) {
            exit;
        }

        $this->saveStaticRewrites();

        $this->saveStaticStrings();

        do_action('vehica_flush_rewrites');

        wp_redirect(admin_url('admin.php?page=vehica_panel_rename_and_translate'));
        exit;
    }

    private function saveStaticRewrites()
    {
        update_option('vehica_rewrites', $this->getRewrites(), true);
    }

    /**
     * @return array
     */
    private function getRewrites()
    {
        $rewrites = [];

        vehicaApp('rewrites')->each(function ($rewrite, $rewriteKey) use (&$rewrites) {
            $rewrites[$rewriteKey] = trim($this->getRewriteValue($rewrite, $rewriteKey));
        });

        return $rewrites;
    }

    /**
     * @param array $rewrite
     * @param string $rewriteKey
     * @return string
     */
    private function getRewriteValue($rewrite, $rewriteKey)
    {
        $key = $rewriteKey . '_rewrite';

        if (empty(preg_replace('/\s+/', '', $_POST[$key]))) {
            return $rewrite['value'];
        }

        return Slugify::create(['trim' => false])->slugify($_POST[$key]);
    }

    private function saveStaticStrings()
    {
        update_option('vehica_strings', $this->getStrings(), true);
    }

    /**
     * @return array
     */
    private function getStrings()
    {
        $strings = [];

        vehicaApp('strings')->each(function ($string) use (&$strings) {
            $strings[$string['key']] = $this->getStringValue($string);
        });

        return $strings;
    }

    /**
     * @param array $string
     * @return string
     */
    private function getStringValue($string)
    {
        $key = $string['key'] . '_string';

        if (!isset($_POST[$key])) {
            return $string['value'];
        }

        return stripslashes_deep($_POST[$key]);
    }

}