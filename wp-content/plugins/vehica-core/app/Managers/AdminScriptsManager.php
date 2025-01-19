<?php

namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Car;

/**
 * Class AdminScriptsManager
 * @package Vehica\Managers
 */
class AdminScriptsManager extends Manager
{

    public function boot()
    {
        add_action('admin_enqueue_scripts', [$this, 'load']);
    }

    /**
     * @return bool
     */
    private function loadAdminScripts()
    {
        return is_user_logged_in();
    }

    /**
     * @param  string  $hook
     */
    public function load($hook)
    {
        if (!$this->loadAdminScripts()) {
            return;
        }

        $this->loadMediaUploader();

        $this->loadMainJs();

        $this->loadSweetAlerts();

        $this->loadFontAwesome();

        $this->loadMainCss();

        $this->loadSelectize();

        $this->loadGoogleMaps();

        $this->loadLazySizes();

        if ($this->isVehicaPage($hook) || $this->isPostPage($hook)) {
            $this->loadFonts();
        }
    }

    /**
     * @param  string  $hook
     * @return bool
     */
    private function isPostPage($hook)
    {
        return ($hook === 'post-new.php' && isset($_GET['post_type']) && $_GET['post_type'] === Car::POST_TYPE)
            || ($hook === 'post.php' && isset($_GET['action']) && $_GET['action'] === 'edit');
    }

    /**
     * @param  string  $hook
     * @return bool
     */
    private function isVehicaPage($hook)
    {
        return strpos($hook, 'vehica_panel') !== false
            || strpos($hook, 'vehica_design') !== false
            || strpos($hook, 'vehica_') !== false;
    }

    private function loadFonts()
    {
        wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons');
        wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css?family=Oswald:400,700|Roboto:300,400,500,700');
    }

    private function loadLazySizes()
    {
        wp_enqueue_script('lazysizes', vehicaApp('url').'assets/js/lazysizes.min.js');
    }

    private function loadGoogleMaps()
    {
        if (empty(vehicaApp('google_maps_api_key'))) {
            return;
        }

        $url = '//maps.googleapis.com/maps/api/js?key='.vehicaApp('google_maps_api_key').'&libraries=places&callback=mapLoaded';
        if (!empty(vehicaApp('settings_config')->getGoogleMapsLanguage())) {
            $url .= '&language='.vehicaApp('settings_config')->getGoogleMapsLanguage();
        }

        wp_enqueue_script('google-maps', $url, [], false, true);

        $snazzyCode = trim(vehicaApp('settings_config')->getGoogleMapsSnazzyCode());
        if (empty($snazzyCode)) {
            return;
        }
        ob_start();
        ?>
        var VehicaSnazzy = <?php echo vehica_embed($snazzyCode); ?>;
        <?php

        wp_add_inline_script('google-maps', ob_get_clean());
    }

    private function loadSelectize()
    {
        wp_enqueue_script('selectize', vehicaApp('url').'/assets/js/selectize.min.js', ['jquery'],
            vehicaApp('version'));
        wp_enqueue_style('selectize', vehicaApp('url').'/assets/css/selectize.css');
    }

    private function loadMainCss()
    {
        wp_enqueue_style('vehica-main', vehicaApp('url').'assets/css/main.css', [],
            vehicaApp('version'));
    }

    private function loadFontAwesome()
    {
        wp_enqueue_style('font-awesome', vehicaApp('url').'assets/css/all.min.css');
    }

    private function loadSweetAlerts()
    {
        wp_enqueue_style('swal2', vehicaApp('url').'assets/css/sweetalert2.min.css');
    }

    private function loadMainJs()
    {
        wp_enqueue_script('vehica-admin', vehicaApp('url').'assets/js/build.min.js',
            ['jquery', 'selectize'], vehicaApp('version'), true);
    }

    private function loadMediaUploader()
    {
        wp_enqueue_media();
    }

}