<?php


namespace Vehica\Providers;


use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Core\ServiceProvider;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Page;
use Vehica\Model\Term\Term;
use Vehica\Model\User\User;
use WooCommerce;

/**
 * Class SettingsServiceProvider
 *
 * @package Vehica\Providers
 */
class SettingsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('primary_menu_id', static function () {
            return vehicaApp('settings_config')->getMainMenuId();
        });

        $this->app->bind('email', static function () {
            return vehicaApp('settings_config')->getMail();
        });

        $this->app->bind('phone', static function () {
            return vehicaApp('settings_config')->getPhone();
        });

        $this->app->bind('phone_url', static function () {
            return apply_filters('vehica/phone', vehicaApp('settings_config')->getPhone());
        });

        $this->app->bind('address', static function () {
            return vehicaApp('settings_config')->getAddress();
        });

        $this->app->bind('logo_id', static function () {
            return vehicaApp('settings_config')->getLogoId();
        });

        $this->app->bind('logo_url', static function () {
            $logo = vehicaApp('image_url', vehicaApp('logo_id'), 'full');
            if ($logo) {
                return $logo;
            }

            $logo = vehicaApp('image_url', vehicaApp('inverse_logo_id'), 'medium');
            if ($logo) {
                return $logo;
            }

            $logo = vehicaApp('image_url', vehicaApp('inverse_logo_id'), 'full');
            if ($logo) {
                return $logo;
            }

            return '';
        });

        $this->app->bind('inverse_logo_id', static function () {
            return vehicaApp('settings_config')->getLogoInverseId();
        });

        $this->app->bind('inverse_logo_url', static function () {
            $logo = vehicaApp('image_url', vehicaApp('inverse_logo_id'), 'full');
            if ($logo) {
                return $logo;
            }

            $logo = vehicaApp('image_url', vehicaApp('logo_id'), 'medium');
            if ($logo) {
                return $logo;
            }

            $logo = vehicaApp('image_url', vehicaApp('logo_id'), 'full');
            if ($logo) {
                return $logo;
            }

            return '';
        });

        $this->app->bind('copyrights_text', static function () {
            return vehicaApp('settings_config')->getCopyrightsText();
        });

        $this->app->bind('short_info', static function () {
            return vehicaApp('settings_config')->getFooterAboutUs();
        });

        $this->app->bind('youtube_url', static function () {
            return vehicaApp('settings_config')->getYoutubeProfile();
        });

        $this->app->bind('facebook_url', static function () {
            return vehicaApp('settings_config')->getFacebookProfile();
        });

        $this->app->bind('twitter_url', static function () {
            return vehicaApp('settings_config')->getTwitterProfile();
        });

        $this->app->bind('instagram_url', static function () {
            return vehicaApp('settings_config')->getInstagramProfile();
        });

        $this->app->bind('linkedin_url', static function () {
            return vehicaApp('settings_config')->getLinkedinProfile();
        });

        $this->app->bind('tiktok_url', static function () {
            return vehicaApp('settings_config')->getTikTokProfile();
        });

        $this->app->bind('telegram_url', static function () {
            return vehicaApp('settings_config')->getTelegramProfile();
        });

        $this->app->bind('footer_menu_id', static function () {
            return vehicaApp('settings_config')->getFooterMenuId();
        });

        $this->app->bind('decimal_separator', static function () {
            return vehicaApp('settings_config')->getDecimalSeparator();
        });

        $this->app->bind('thousands_separator', static function () {
            return vehicaApp('settings_config')->getThousandsSeparator();
        });

        $this->app->bind('numbering_system', static function () {
            return vehicaApp('settings_config')->getNumberingSystem();
        });

        $this->app->bind('google_maps_api_key', static function () {
            if (!vehicaApp('settings_config')) {
                return '';
            }

            return vehicaApp('settings_config')->getGoogleMapsApiKey();
        });

        $this->app->bind('map_zoom', static function () {
            return vehicaApp('settings_config')->getGoogleMapsZoomLevel();
        });

        $this->app->bind('map_initial_position', static function () {
            return vehicaApp('settings_config')->getGoogleMapsInitialPosition();
        });

        $this->app->bind('map_type', static function () {
            return vehicaApp('settings_config')->getGoogleMapsType();
        });

        $this->app->bind('primary_color', static function () {
            return vehicaApp('settings_config')->getPrimaryColor();
        });

        $this->app->bind('sticky_menu', static function () {
            return vehicaApp('settings_config')->isStickyMenuEnabled();
        });

        $this->app->bind('hide_importer', static function () {
            if (!vehicaApp('settings_config')) {
                return false;
            }

            return vehicaApp('settings_config')->hideImporter();
        });

        $this->app->bind('blog_page_id', static function () {
            return vehicaApp('settings_config')->getBlogPageId();
        });

        $this->app->bind('blog_page', static function () {
            return vehicaApp('settings_config')->getBlogPage();
        });

        $this->app->bind('is_user_confirmation_enabled', static function () {
            return vehicaApp('settings_config')->isUserConfirmationEnabled();
        });

        $this->app->bind('panel_page_id', static function () {
            return vehicaApp('settings_config')->getPanelPageId();
        });

        $this->app->bind('panel_page', static function () {
            return Page::getById(vehicaApp('panel_page_id'));
        });

        $this->app->bind('panel_page_url', static function () {
            if (!vehicaApp('panel_page')) {
                return site_url();
            }

            return vehicaApp('panel_page')->getUrl();
        });

        $this->app->bind('login_page_id', static function () {
            return vehicaApp('settings_config')->getLoginPageId();
        });

        $this->app->bind('login_page', static function () {
            return Page::getById(vehicaApp('login_page_id'));
        });

        $this->app->bind('login_page_url', static function () {
            if (!vehicaApp('login_page')) {
                return site_url();
            }

            return vehicaApp('login_page')->getUrl();
        });

        $this->app->bind('register_page_id', static function () {
            return vehicaApp('settings_config')->getRegisterPageId();
        });

        $this->app->bind('register_page', static function () {
            return Page::getById(vehicaApp('register_page_id'));
        });

        $this->app->bind('register_page_url', static function () {
            if (!vehicaApp('register_page')) {
                return site_url();
            }

            return vehicaApp('register_page')->getUrl();
        });

        $this->app->bind('demo_imported', static function () {
            $demo = get_option('vehica_demo');

            return !empty($demo);
        });

        $this->app->bind('calculator_page_url', static function () {
            $calculatorPage = vehicaApp('settings_config')->getCalculatorPage();

            if (!$calculatorPage instanceof Page) {
                return false;
            }

            return $calculatorPage->getUrl();
        });

        $this->app->bind('show_favorite', static function () {
            return vehicaApp('settings_config')->showFavorite();
        });

        $this->app->bind('show_photo_count', static function () {
            return vehicaApp('settings_config')->showPhotoCount();
        });

        $this->app->bind('woocommerce_mode', static function () {
            return vehicaApp('settings_config')->isWoocommerceModeEnabled() && class_exists(WooCommerce::class);
        });

        $this->app->bind('builtin_mode', static function () {
            return vehicaApp('settings_config')->isBuiltinModeEnabled();
        });

        $this->app->bind('show_menu_button', static function () {
            if (!is_user_logged_in()) {
                return vehicaApp('settings_config')->displayMenuButton();
            }

            return User::getCurrent()->canCreateCars() && vehicaApp('settings_config')->displayMenuButton();
        });

        $this->app->bind('show_menu_account', static function () {
            return vehicaApp('settings_config')->displayMenuAccount();
        });

        $this->app->bind('show_contact_for_price', static function () {
            return vehicaApp('settings_config')->showContactForPrice();
        });

        $this->app->bind('panel_card_features', static function () {
            return Collection::make(vehicaApp('settings_config')->getPanelCardFeatures())->map(static function ($featureId) {
                return vehicaApp('simple_text_car_fields')->find(static function (SimpleTextAttribute $simpleTextAttribute) use ($featureId) {
                    return $simpleTextAttribute->getId() === $featureId;
                });
            })->filter(static function ($simpleTextAttribute) {
                return $simpleTextAttribute !== false;
            });
        });

        $this->app->bind('car_archive_url', static function () {
            return get_post_type_archive_link(Car::POST_TYPE);
        });

        $this->app->bind('auto_title_fields', static function () {
            return Collection::make(vehicaApp('settings_config')->getAutoCarTitleFieldIds())->map(static function ($fieldId) {
                return vehicaApp('simple_text_car_fields')->find(static function ($field) use ($fieldId) {
                    /* @var SimpleTextAttribute $field */
                    return $field->getId() === $fieldId;
                });
            })->filter(static function ($field) {
                return $field !== false;
            });
        });

        $this->app->bind('show_social_auth', static function () {
            return vehicaApp('show_google_auth') || vehicaApp('show_facebook_auth');
        });

        $this->app->bind('show_google_auth', static function () {
            return vehicaApp('settings_config')->isGoogleAuthEnabled()
                && !empty(vehicaApp('settings_config')->getGoogleAuthClientId())
                && !empty(vehicaApp('settings_config')->getGoogleAuthClientSecret());
        });

        $this->app->bind('show_facebook_auth', static function () {
            return vehicaApp('settings_config')->isFacebookAuthEnabled()
                && !empty(vehicaApp('settings_config')->getFacebookAppId())
                && !empty(vehicaApp('settings_config')->getFacebookAppSecret());
        });

        $this->app->bind('recaptcha', static function () {
            return vehicaApp('settings_config')->isRecaptchaEnabled()
                && !empty(vehicaApp('settings_config')->getRecaptchaSecret())
                && !empty(vehicaApp('settings_config')->getRecaptchaSite());
        });

        $this->app->bind('terms_excluded_from_search', static function () {
            return vehicaApp('settings_config')->getExcludeFromSearch();
        });

        $this->app->bind('cars_excluded_from_search', static function () {
            $excludedTermIds = vehicaApp('terms_excluded_from_search');

            if (empty($excludedTermIds)) {
                return [];
            }

            $excludedCarIds = [];

            foreach ($excludedTermIds as $termId) {
                $term = Term::getById($termId);
                if ($term) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $excludedCarIds = array_merge($excludedCarIds, $term->getCarIds());
                }
            }

            return $excludedCarIds;
        });

        $this->app->bind('is_compare_enabled', static function () {
            return vehicaApp('settings_config')->isCompareEnabled();
        });

        $this->app->bind('compare_mode', static function () {
            return vehicaApp('settings_config')->getCompareMode();
        });

        $this->app->bind('user_role_mode', static function () {
            return vehicaApp('settings_config')->getUserRoleMode();
        });

        $this->app->bind('show_user_roles', static function () {
            return vehicaApp('user_role_mode') === 'enabled';
        });

        $this->app->bind('initial_user_role', static function () {
            if (vehicaApp('user_role_mode') === 'hidden_private') {
                return vehicaApp('private_user_role');
            }

            if (vehicaApp('user_role_mode') === 'hidden_business') {
                return vehicaApp('business_user_role');
            }

            return vehicaApp('private_user_role');
        });

        $this->app->bind('taxonomy_url', static function () {
            return vehicaApp('settings_config')->getCarBreadcrumbs();
        });

        $this->app->bind('pretty_urls_enabled', static function () {
            if (!vehicaApp('settings_config')) {
                return false;
            }

            return vehicaApp('settings_config')->prettyUrlsEnabled();
        });

        $this->app->bind('policy_label', static function () {
            return vehicaApp('settings_config')->getPolicyLabel();
        });
    }

}