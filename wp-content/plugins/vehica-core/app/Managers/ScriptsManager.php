<?php

namespace Vehica\Managers;

use Vehica\Core\BaseCurrency;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Post;
use Vehica\Widgets\General\PanelGeneralWidget;

/**
 * Class ScriptsManager
 *
 * @package Vehica\Managers
 */
class ScriptsManager extends Manager
{

    public function boot()
    {
        add_action('wp_enqueue_scripts', [$this, 'load'], 20);

        add_action('wp_footer', static function () { ?>
            <div class="vehica-app">
                <portal-target name="footer"></portal-target>
            </div>
            <?php
        });
    }

    public function load()
    {
        $this->loadDatePicker();

        $this->loadMainJs();

        $this->loadFontAwesome();

        $this->loadSelect();

        $this->loadSwiper();

        $this->loadDropZone();

        $this->loadGoogleMaps();

        $this->loadSweetAlert2();

        if ($this->isSinglePost()) {
            $this->loadSinglePost();
        }

        if ($this->isSingleCar()) {
            $this->loadSingleCar();
        }

        $this->loadAutoNumeric();

        $this->loadStripe();

        $this->loadPayPal();

        $this->loadLazySizes();

        if ($this->isLoanCalculatorPage()) {
            wp_enqueue_script('autoNumeric');
        }
    }

    /**
     * @return bool
     */
    private function isLoanCalculatorPage()
    {
        global $post;
        if (!$post) {
            return false;
        }

        return vehicaApp('settings_config')->getCalculatorPageId() === $post->ID;
    }

    private function loadStripe()
    {
        if (!vehicaApp('settings_config')->isPaymentEnabled()) {
            return;
        }

        if (vehicaApp('woocommerce_mode')) {
            return;
        }

        if (!vehicaApp('settings_config')->isStripeEnabled()) {
            return;
        }

        if (!isset($_GET['action'])
            || (
                $_GET['action'] !== PanelGeneralWidget::ACTION_TYPE_CREATE_CAR &&
                $_GET['action'] !== PanelGeneralWidget::ACTION_TYPE_EDIT_CAR)
        ) {
            return;
        }

        if (!BaseCurrency::getSelected()) {
            return;
        }

        wp_enqueue_script(
            'stripe',
            'https://js.stripe.com/v3/',
            [],
            null
        );
    }

    private function loadPayPal()
    {
        if (!vehicaApp('settings_config')->isPaymentEnabled()) {
            return;
        }

        if (vehicaApp('woocommerce_mode')) {
            return;
        }

        if (!vehicaApp('settings_config')->isPayPalEnabled()) {
            return;
        }

        if (
            !isset($_GET['action'])
            || (
                $_GET['action'] !== PanelGeneralWidget::ACTION_TYPE_CREATE_CAR
                && $_GET['action'] !== PanelGeneralWidget::ACTION_TYPE_EDIT_CAR
            )
        ) {
            return;
        }

        if (!vehicaApp('current_base_currency_support_paypal')) {
            return;
        }

        wp_enqueue_script(
            'paypal',
            'https://www.paypal.com/sdk/js?client-id='
            . vehicaApp('settings_config')->getPayPalClientId()
            . '&currency=' . vehicaApp('settings_config')->getPaymentCurrency() . '&disable-funding=card,bancontact,blik,eps,giropay,ideal,mybank,p24,sepa,sofort,venmo',
            [],
            null
        );
    }

    private function loadSweetAlert2()
    {
        wp_register_script('sweetalert2', vehicaApp('url') . 'assets/js/sweetalert2.min.js',
            ['jquery'], '1.0.0', true);
        wp_register_style('sweetalert2', vehicaApp('url') . 'assets/css/sweetalert2.min.css', ['vehica']);
    }

    private function loadDropZone()
    {
        wp_register_style('dropzone', vehicaApp('url') . 'assets/css/dropzone.min.css', ['vehica']);
    }

    private function loadGoogleMaps()
    {
        if (empty(vehicaApp('google_maps_api_key'))) {
            return;
        }

        $url = '//maps.googleapis.com/maps/api/js?key=' . vehicaApp('google_maps_api_key') . '&libraries=places&callback=mapLoaded';
        if (!empty(vehicaApp('settings_config')->getGoogleMapsLanguage())) {
            $url .= '&language=' . vehicaApp('settings_config')->getGoogleMapsLanguage();
        }

        wp_register_script('google-maps', $url, [], false, true);

        wp_register_script('infobox', vehicaApp('assets') . 'js/infobox.min.js', ['google-maps'], false, true);

        wp_register_script('marker-with-label', vehicaApp('assets') . 'js/markerWithLabel.min.js', ['google-maps'], false, true);

        wp_register_script('spiderfier', vehicaApp('assets') . 'js/spiderfier.min.js', ['google-maps'], false, true);

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

    private function loadLazySizes()
    {
        wp_enqueue_script('lazysizes', vehicaApp('url') . 'assets/js/lazysizes.min.js');

        ob_start();
        ?>
        window.lazySizesConfig = window.lazySizesConfig || {};
        window.lazySizesConfig.loadMode = 1
        window.lazySizesConfig.init = 0
        <?php
        wp_add_inline_script('lazysizes', ob_get_clean(), 'before');
    }

    private function loadSwiper()
    {
        wp_enqueue_script('swiper', vehicaApp('url') . 'assets/js/swiper.min.js', [],
            '4.3.3', true);
    }

    private function loadAutoNumeric()
    {
        wp_register_script('autoNumeric', vehicaApp('url') . 'assets/js/autoNumeric.min.js', [], '4.6');
    }

    private function loadMainJs()
    {
        $deps = ['swiper', 'jquery', 'sweetalert2'];

        if ($this->isPanelPage()) {
            $deps[] = 'autoNumeric';
        }

        if ($this->checkIfLoadReCaptcha()) {
            $this->loadReCaptcha();
        }

        if ($this->isPanelPage() && !empty(vehicaApp('google_maps_api_key'))) {
            $deps[] = 'google-maps';
        }

        wp_enqueue_script('vehica-elements', vehicaApp('url') . 'assets/js/elements.min.js', $deps, vehicaApp('version'), true);

        if ($this->isPanelPage()) {
            ob_start();
            ?>
            window.VehicaEventBus.$on('removeFavorite', function(carId) {
            jQuery('#vehica-car-' + carId).remove();
            if (!jQuery.trim(jQuery('#vehica-favorite-cars').html()).length) {
            jQuery('.vehica-panel-list.vehica-container').hide();
            jQuery('#vehica-favorite-is-empty').show();
            }
            });
            <?php
            wp_add_inline_script('vehica-elements', ob_get_clean(), 'after');
        }

        wp_localize_script('vehica-elements', 'Vehica', [
            'currentCurrency' => vehicaApp('current_currency'),
            'currencies' => vehicaApp('currencies'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'carsApiEndpoint' => get_rest_url() . 'vehica/v1/cars',
            'mobileBreakpoint' => vehicaApp('mobile_breakpoint'),
            'tabletBreakpoint' => vehicaApp('tablet_breakpoint'),
        ]);
    }

    private function loadSelect()
    {
        wp_enqueue_style('vue-select', vehicaApp('url') . 'assets/css/vue-select.min.css', ['vehica']);
    }

    private function loadFontAwesome()
    {
        if (wp_style_is('elementor-icons-fa-regular', 'registered')) {
            wp_enqueue_style('elementor-icons-fa-regular');
            wp_enqueue_style('elementor-icons-fa-solid');
            wp_enqueue_style('elementor-icons-fa-brands');
        } else {
            wp_enqueue_style('font-awesome-5', vehicaApp('url') . 'assets/css/all.min.css');
        }
    }

    private function loadSingleCar()
    {
        wp_enqueue_script('vehica-gallery', vehicaApp('url') . 'assets/js/gallery.js', ['jquery', 'photo-swipe'], '1.0.0',
            true);
        wp_enqueue_script('photo-swipe', vehicaApp('url') . 'assets/js/photo-swipe.min.js', [], '4.1.3', true);
        wp_enqueue_style('photo-swipe', vehicaApp('url') . 'assets/css/gallery.css', [], '4.1.3');
        wp_enqueue_style('photo-swipe-skin', vehicaApp('url') . 'assets/css/gallery/skin.css', ['photo-swipe'],
            '4.1.3');
    }

    /**
     * @return bool
     */
    private function isSingleCar()
    {
        return is_singular(Car::POST_TYPE);
    }

    /**
     * @return bool
     */
    private function isSinglePost()
    {
        return is_singular(Post::POST_TYPE);
    }

    private function loadSinglePost()
    {
        if (comments_open()) {
            wp_enqueue_script('comment-reply');
        }
    }

    /**
     * @return bool
     */
    private function isPanelPage()
    {
        if (!is_page()) {
            return false;
        }

        global $post;

        return vehicaApp('panel_page_id') === $post->ID;
    }

    /**
     * @return bool
     */
    private function isLoginPage()
    {
        if (!is_page()) {
            return false;
        }

        global $post;

        return vehicaApp('login_page_id') === $post->ID;
    }

    private function loadReCaptcha()
    {
        wp_enqueue_script(
            'recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . vehicaApp('settings_config')->getRecaptchaSite(),
            [],
            null
        );
    }

    private function checkIfLoadReCaptcha()
    {
        if (!vehicaApp('recaptcha')) {
            return false;
        }

        return !is_user_logged_in();
    }

    private function loadDatePicker()
    {
        if (!vehicaApp('date_time_fields')->count()) {
            return;
        }

        wp_enqueue_script('pickadate', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/picker.js', ['jquery']);
        wp_enqueue_script('pickadate-date', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/picker.date.js', ['pickadate', 'jquery']);
        wp_enqueue_script('pickadate-time', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/compressed/picker.time.js', ['pickadate', 'jquery']);

        wp_enqueue_style('pickadate', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/classic.css');
        wp_enqueue_style('pickadate-date', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/classic.date.css', ['pickadate']);
        wp_enqueue_style('pickadate-time', 'https://cdnjs.cloudflare.com/ajax/libs/pickadate.js/3.5.6/themes/classic.time.css', ['pickadate']);
    }

}