<?php /** @noinspection PhpUnused */

namespace Vehica\Model\Post\Config;

use Elementor\Plugin;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Page;
use Vehica\Model\Post\Post;
use Vehica\Model\Term\Term;

/**
 * Class SettingsConfig
 *
 * @package Vehica\Model\Post\Config
 */
class SettingsConfig extends Config
{
    const KEY = 'vehica_settings_config';

    /**
     * @param string $thousandsSeparator
     */
    public function setThousandsSeparator($thousandsSeparator)
    {
        $this->setMeta(Setting::THOUSANDS_SEPARATOR, $thousandsSeparator);
    }

    /**
     * @return string
     */
    public function getThousandsSeparator()
    {
        return $this->getMeta(Setting::THOUSANDS_SEPARATOR);
    }

    /**
     * @param string $decimalSeparator
     */
    public function setDecimalSeparator($decimalSeparator)
    {
        $this->setMeta(Setting::DECIMAL_SEPARATOR, $decimalSeparator);
    }

    /**
     * @return string
     */
    public function getDecimalSeparator()
    {
        $decimalSeparator = $this->getMeta(Setting::DECIMAL_SEPARATOR);

        if (empty($decimalSeparator)) {
            return '.';
        }

        return $decimalSeparator;
    }

    /**
     * @param string $primaryColor
     */
    public function setPrimaryColor($primaryColor)
    {
        $this->setMeta(Setting::PRIMARY_COLOR, $primaryColor);
    }

    /**
     * @return string
     */
    public function getPrimaryColor()
    {
        $color = $this->getMeta(Setting::PRIMARY_COLOR);

        if (empty($color)) {
            return Setting::PRIMARY_COLOR_DEFAULT;
        }

        return $color;
    }

    /**
     * @param array $postData
     * @param array $settings
     */
    public function update($postData, $settings = [])
    {
        if (empty($settings)) {
            $settings = Setting::getSettings();
        }

        parent::update($postData, $settings);

        Plugin::instance()->files_manager->clear_cache();
    }

    /**
     * @return array
     */
    public function getFonts()
    {
        $path = vehicaApp('path') . '/config/fonts.php';
        return file_exists($path) ? require $path : [];
    }

    /**
     * @param string $font
     */
    public function setHeadingFont($font)
    {
        $this->setMeta(Setting::HEADING_FONT, $font);
    }

    /**
     * @return string
     */
    public function getHeadingFont()
    {
        $headingFont = $this->getMeta(Setting::HEADING_FONT);

        if (empty($headingFont)) {
            return Setting::HEADING_FONT_DEFAULT;
        }

        return $headingFont;
    }

    /**
     * @param string $font
     *
     * @return bool
     */
    public function isHeadingFont($font)
    {
        return $this->getHeadingFont() === $font;
    }

    /**
     * @param string $font
     */
    public function setTextFont($font)
    {
        $this->setMeta(Setting::TEXT_FONT, $font);
    }

    /**
     * @return string
     */
    public function getTextFont()
    {
        $textFont = $this->getMeta(Setting::TEXT_FONT);

        if (empty($textFont)) {
            return Setting::TEXT_FONT_DEFAULT;
        }

        return $textFont;
    }

    /**
     * @param string $font
     *
     * @return bool
     */
    public function isTextFont($font)
    {
        return $this->getTextFont() === $font;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->setMeta(Setting::EMAIL, $email);
    }

    /**
     * @return string
     */
    public function getMail()
    {
        $email = $this->getMeta(Setting::EMAIL);

        if (empty($email)) {
            return '';
        }

        return $email;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->setMeta(Setting::PHONE, $phone);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->getMeta(Setting::PHONE);
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->setMeta(Setting::ADDRESS, $address);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getMeta(Setting::ADDRESS);
    }

    /**
     * @param int $logo
     */
    public function setLogo($logo)
    {
        $logo = (int)$logo;
        set_theme_mod('custom_logo', $logo);
    }

    /**
     * @return int
     */
    public function getLogoId()
    {
        return (int)get_theme_mod('custom_logo');
    }

    /**
     * @return bool
     */
    public function hasLogo()
    {
        $logoId = $this->getLogoId();

        return !empty($logoId);
    }

    /**
     * @param int $logo
     */
    public function setLogoInverse($logo)
    {
        $logo = (int)$logo;
        $this->setMeta(Setting::LOGO_INVERSE, $logo);
    }

    /**
     * @return int
     */
    public function getLogoInverseId()
    {
        return (int)$this->getMeta(Setting::LOGO_INVERSE);
    }

    /**
     * @return bool
     */
    public function hasLogoInverse()
    {
        $logoId = $this->getLogoInverseId();

        return !empty($logoId);
    }

    /**
     * @param array $breadcrumbs
     */
    public function setCarBreadcrumbs($breadcrumbs)
    {
        $this->setMeta(Setting::CAR_BREADCRUMBS, $breadcrumbs);
    }

    /**
     * @param int $taxonomyId
     *
     * @return bool
     */
    public function isCarBreadcrumb($taxonomyId)
    {
        return $this->getCarBreadcrumbIds()->contain($taxonomyId);
    }

    /**
     * @return Collection
     */
    public function getCarBreadcrumbIds()
    {
        $breadcrumbs = $this->getMeta(Setting::CAR_BREADCRUMBS);

        if (!is_array($breadcrumbs)) {
            return Collection::make();
        }

        return Collection::make($breadcrumbs)->map(static function ($taxonomyId) {
            return (int)$taxonomyId;
        });
    }

    /**
     * @return Collection
     */
    public function getCarBreadcrumbs()
    {
        return $this->getCarBreadcrumbIds()->map(static function ($taxonomyId) {
            return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyId) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->getId() === $taxonomyId;
            });
        })->filter(static function ($taxonomy) {
            return $taxonomy !== false;
        })->values();
    }


    public function setListingsTableTaxonomies($taxonomies)
    {
        $this->setMeta(Setting::LISTINGS_TABLE_TAXONOMIES, $taxonomies);
    }

    public function getListingsTableTaxonomyIds()
    {
        $ids = $this->getMeta(Setting::LISTINGS_TABLE_TAXONOMIES);
        if (empty($ids)) {
            return [];
        }

        return Collection::make($ids)->map(static function ($taxonomyId) {
            return (int)$taxonomyId;
        })->all();
    }

    public function getListingsTableTaxonomies(): Collection
    {
        return Collection::make($this->getListingsTableTaxonomyIds())->map(static function ($taxonomyId) {
            return vehicaApp('taxonomies')->find(static function ($taxonomy) use ($taxonomyId) {
                /* @var Taxonomy $taxonomy */
                return $taxonomy->getId() === $taxonomyId;
            });
        })->filter(static function ($taxonomy) {
            return $taxonomy !== false;
        })->values();
    }

    /**
     * @param string $facebookApi
     */
    public function setFacebookApi($facebookApi)
    {
        $this->setMeta(Setting::FACEBOOK_API, $facebookApi);
    }

    /**
     * @return mixed
     */
    public function getFacebookApi()
    {
        return $this->getMeta(Setting::FACEBOOK_API);
    }

    /**
     * @return bool
     */
    public function hasFacebookApi()
    {
        return !empty($this->getFacebookApi());
    }

    /**
     * @param string $facebookProfile
     */
    public function setFacebookProfile($facebookProfile)
    {
        $this->setMeta(Setting::FACEBOOK_PROFILE, $facebookProfile);
    }

    /**
     * @return string
     */
    public function getFacebookProfile()
    {
        return $this->getMeta(Setting::FACEBOOK_PROFILE);
    }

    /**
     * @param string $twitterProfile
     */
    public function setTwitterProfile($twitterProfile)
    {
        $this->setMeta(Setting::TWITTER_PROFILE, $twitterProfile);
    }

    /**
     * @return string
     */
    public function getTwitterProfile()
    {
        return $this->getMeta(Setting::TWITTER_PROFILE);
    }

    /**
     * @param string $instagramProfile
     */
    public function setInstagramProfile($instagramProfile)
    {
        $this->setMeta(Setting::INSTAGRAM_PROFILE, $instagramProfile);
    }

    /**
     * @return string
     */
    public function getInstagramProfile()
    {
        return $this->getMeta(Setting::INSTAGRAM_PROFILE);
    }

    /**
     * @param string $linkedinProfile
     */
    public function setLinkedinProfile($linkedinProfile)
    {
        $this->setMeta(Setting::LINKEDIN_PROFILE, $linkedinProfile);
    }

    /**
     * @return string
     */
    public function getLinkedinProfile()
    {
        return $this->getMeta(Setting::LINKEDIN_PROFILE);
    }

    /**
     * @param string $youtubeProfile
     */
    public function setYoutubeProfile($youtubeProfile)
    {
        $this->setMeta(Setting::YOUTUBE_PROFILE, $youtubeProfile);
    }

    /**
     * @return string
     */
    public function getYoutubeProfile()
    {
        return $this->getMeta(Setting::YOUTUBE_PROFILE);
    }

    /**
     * @param string $tikTokProfile
     */
    public function setTikTokProfile($tikTokProfile)
    {
        $this->setMeta(Setting::TIKTOK_PROFILE, $tikTokProfile);
    }

    /**
     * @return string
     */
    public function getTikTokProfile()
    {
        return $this->getMeta(Setting::TIKTOK_PROFILE);
    }

    public function setTelegramProfile($telegramProfile)
    {
        $this->setMeta(Setting::TELEGRAM_PROFILE, $telegramProfile);
    }

    public function getTelegramProfile()
    {
        return $this->getMeta(Setting::TELEGRAM_PROFILE);
    }

    /**
     * @param string $googleMapsApiKey
     */
    public function setGoogleMapsApiKey($googleMapsApiKey)
    {
        $googleMapsApiKey = trim($googleMapsApiKey);
        $this->setMeta(Setting::GOOGLE_MAPS_API_KEY, $googleMapsApiKey);
    }

    /**
     * @return string
     */
    public function getGoogleMapsApiKey()
    {
        return $this->getMeta(Setting::GOOGLE_MAPS_API_KEY);
    }

    /**
     * @param string $language
     */
    public function setGoogleMapsLanguage($language)
    {
        $this->setMeta(Setting::GOOGLE_MAPS_LANGUAGE, $language);
    }

    /**
     * @return string
     */
    public function getGoogleMapsLanguage()
    {
        $language = $this->getMeta(Setting::GOOGLE_MAPS_LANGUAGE);

        if (empty($language)) {
            return '';
        }

        return $language;
    }

    /**
     * @return int
     */
    public function getGoogleMapsZoomLevel()
    {
        $zoom = (int)$this->getMeta(Setting::GOOGLE_MAPS_INITIAL_ZOOM);

        if (empty($zoom)) {
            return 8;
        }

        return $zoom;
    }

    /**
     * @param int $zoom
     */
    public function setGoogleMapsInitialZoom($zoom)
    {
        $this->setMeta(Setting::GOOGLE_MAPS_INITIAL_ZOOM, (int)$zoom);
    }

    /**
     * @return string
     */
    public function getGoogleMapsType()
    {
        return 'roadmap';
    }

    /**
     * @param float $initialPosition
     */
    public function setGoogleMapsInitialPositionLat($initialPosition)
    {
        $initialPosition = (float)$initialPosition;
        $this->setMeta(Setting::GOOGLE_MAPS_INITIAL_POSITION_LAT, $initialPosition);
    }

    /**
     * @param float $initialPosition
     */
    public function setGoogleMapsInitialPositionLng($initialPosition)
    {
        $initialPosition = (float)$initialPosition;
        $this->setMeta(Setting::GOOGLE_MAPS_INITIAL_POSITION_LNG, $initialPosition);
    }

    /**
     * @return array
     */
    public function getGoogleMapsInitialPosition()
    {
        $lat = (double)$this->getMeta(Setting::GOOGLE_MAPS_INITIAL_POSITION_LAT);
        $lat = !empty($lat) ? $lat : Setting::GOOGLE_MAPS_INITIAL_POSITION_DEFAULT_LAT;

        $lng = (double)$this->getMeta(Setting::GOOGLE_MAPS_INITIAL_POSITION_LNG);
        $lng = !empty($lng) ? $lng : Setting::GOOGLE_MAPS_INITIAL_POSITION_DEFAULT_LNG;

        return [
            'lat' => $lat,
            'lng' => $lng
        ];
    }

    /**
     * @param int $menuId
     */
    public function setMainMenu($menuId)
    {
        $menuId = (int)$menuId;
        $this->setMeta(Setting::MAIN_MENU, $menuId);
    }

    /**
     * @return int
     */
    public function getMainMenuId()
    {
        return (int)$this->getMeta(Setting::MAIN_MENU);
    }

    /**
     * @param Term|int $menu
     *
     * @return bool
     */
    public function isMainMenu($menu)
    {
        if ($menu instanceof Term) {
            return $menu->getId() === $this->getMainMenuId();
        }

        return $menu === $this->getMainMenuId();
    }

    /**
     * @param int $stickyMenu
     */
    public function setStickyMenu($stickyMenu)
    {
        $stickyMenu = (int)$stickyMenu;
        $this->setMeta(Setting::STICKY_MENU, $stickyMenu);
    }

    /**
     * @return bool
     */
    public function isStickyMenuEnabled()
    {
        $stickyMenu = $this->getMeta(Setting::STICKY_MENU);

        return !empty($stickyMenu);
    }

    /**
     * @param int $menuId
     */
    public function setFooterMenu($menuId)
    {
        $menuId = (int)$menuId;
        $this->setMeta(Setting::FOOTER_MENU, $menuId);
    }

    /**
     * @return int
     */
    public function getFooterMenuId()
    {
        return (int)$this->getMeta(Setting::FOOTER_MENU);
    }

    /**
     * @param Term|int $menu
     *
     * @return bool
     */
    public function isFooterMenu($menu)
    {
        if ($menu instanceof Term) {
            return $menu->getId() === $this->getFooterMenuId();
        }

        return $menu === $this->getFooterMenuId();
    }

    /**
     * @param string $footerAboutUs
     */
    public function setFooterAboutUs($footerAboutUs)
    {
        $this->setMeta(Setting::FOOTER_ABOUT_US, $footerAboutUs);
    }

    /**
     * @return string
     */
    public function getFooterAboutUs()
    {
        return $this->getMeta(Setting::FOOTER_ABOUT_US);
    }

    /**
     * @param string $copyrightsText
     */
    public function setCopyrightsText($copyrightsText)
    {
        $this->setMeta(Setting::COPYRIGHTS_TEXT, $copyrightsText);
    }

    /**
     * @return string
     */
    public function getCopyrightsText()
    {
        return $this->getMeta(Setting::COPYRIGHTS_TEXT);
    }

    /**
     * @param int $pageId
     */
    public function setBlogPage($pageId)
    {
        $pageId = (int)$pageId;

        if (empty($pageId) || $pageId === $this->getHomepageId()) {
            return;
        }

        update_option('page_for_posts', $pageId);
        update_option('show_on_front', 'page');
    }

    /**
     * @return int
     */
    public function getBlogPageId()
    {
        return (int)get_option('page_for_posts');
    }

    /**
     * @return Page|false
     */
    public function getBlogPage()
    {
        $pageId = vehicaApp('blog_page_id');

        if (empty($pageId)) {
            return false;
        }

        return Page::getById($pageId);
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isBlogPage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getBlogPageId();
        }

        return $page === $this->getBlogPageId();
    }

    /**
     * @param int $pageId
     */
    public function setHomepage($pageId)
    {
        $pageId = (int)$pageId;

        if (empty($pageId) || $pageId === $this->getBlogPageId()) {
            return;
        }

        update_option('page_on_front', $pageId);
        update_option('show_on_front', 'page');
    }

    /**
     * @return int
     */
    public function getHomepageId()
    {
        return (int)get_option('page_on_front');
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isHomepage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getHomepageId();
        }

        return $page === $this->getHomepageId();
    }

    /**
     * @param int $pageId
     */
    public function setComparePage($pageId)
    {
        $this->setMeta(Setting::COMPARE_PAGE, (int)$pageId);
    }

    /**
     * @return int
     */
    public function getComparePageId()
    {
        return (int)$this->getMeta(Setting::COMPARE_PAGE);
    }

    /**
     * @return Page|false
     */
    public function getComparePage()
    {
        $pageId = $this->getComparePageId();
        if (empty($pageId)) {
            return false;
        }

        return Page::getById($pageId);
    }

    /**
     * @return string
     */
    public function getComparePageUrl()
    {
        $page = $this->getComparePage();

        if (!$page) {
            return '';
        }

        return $page->getUrl();
    }

    /**
     * @param int $pageId
     */
    public function setCalculatorPage($pageId)
    {
        $pageId = (int)$pageId;
        $this->setMeta(Setting::CALCULATOR_PAGE, $pageId);
    }

    /**
     * @return int
     */
    public function getCalculatorPageId()
    {
        return (int)$this->getMeta(Setting::CALCULATOR_PAGE);
    }

    /**
     * @return Page|false
     */
    public function getCalculatorPage()
    {
        $pageId = $this->getCalculatorPageId();

        return Page::getById($pageId);
    }

    /**
     * @param int $errorPage
     */
    public function setErrorPage($errorPage)
    {
        $errorPage = (int)$errorPage;
        $this->setMeta(Setting::ERROR_PAGE, $errorPage);
    }

    /**
     * @return int
     */
    public function getErrorPageId()
    {
        return (int)$this->getMeta(Setting::ERROR_PAGE);
    }

    /**
     * @param int $hideImporter
     */
    public function setHideImporter($hideImporter)
    {
        $hideImporter = (int)$hideImporter;
        $this->setMeta(Setting::HIDE_IMPORTER, $hideImporter);
    }

    /**
     * @return bool
     */
    public function hideImporter()
    {
        $hideImporter = $this->getMeta(Setting::HIDE_IMPORTER);

        return !empty($hideImporter);
    }

    /**
     * @param int $userConfirmation
     */
    public function setUserConfirmation($userConfirmation)
    {
        $userConfirmation = (int)$userConfirmation;
        $this->setMeta(Setting::USER_CONFIRMATION, $userConfirmation);
    }

    /**
     * @return bool
     */
    public function isUserConfirmationEnabled()
    {
        $enabled = (int)$this->getMeta(Setting::USER_CONFIRMATION);

        return !empty($enabled);
    }

    /**
     * @param int $pageId
     */
    public function setPanelPage($pageId)
    {
        $pageId = (int)$pageId;
        $this->setMeta(Setting::PANEL_PAGE, $pageId);
    }

    /**
     * @return int
     */
    public function getPanelPageId()
    {
        return (int)$this->getMeta(Setting::PANEL_PAGE);
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isPanelPage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getPanelPageId();
        }

        return $page === $this->getPanelPageId();
    }

    /**
     * @param int $pageId
     */
    public function setCtaPage($pageId)
    {
        $pageId = (int)$pageId;
        $this->setMeta(Setting::CTA_PAGE, $pageId);
    }

    /**
     * @return int
     */
    public function getCtaPageId()
    {
        return (int)$this->getMeta(Setting::CTA_PAGE);
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isCtaPage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getCtaPageId();
        }

        return $page === $this->getCtaPageId();
    }

    /**
     * @param int $pageId
     */
    public function setLoginPage($pageId)
    {
        $pageId = (int)$pageId;
        $this->setMeta(Setting::LOGIN_PAGE, $pageId);
    }

    /**
     * @return int
     */
    public function getLoginPageId()
    {
        return (int)$this->getMeta(Setting::LOGIN_PAGE);
    }

    /**
     * @return bool
     */
    public function hasLoginPage()
    {
        return $this->getLoginPage() !== false;
    }

    /**
     * @return Page|false
     */
    public function getLoginPage()
    {
        return Page::getById($this->getLoginPageId());
    }

    /**
     * @return string
     */
    public function getLoginPageUrl()
    {
        $loginPage = $this->getLoginPage();

        if (!$loginPage) {
            return site_url();
        }

        return $loginPage->getUrl();
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isLoginPage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getLoginPageId();
        }

        return $page === $this->getLoginPageId();
    }

    /**
     * @param int $pageId
     */
    public function setRegisterPage($pageId)
    {
        $pageId = (int)$pageId;
        $this->setMeta(Setting::REGISTER_PAGE, $pageId);
    }

    /**
     * @return int
     */
    public function getRegisterPageId()
    {
        $registerPageId = (int)$this->getMeta(Setting::REGISTER_PAGE);

        if (empty($registerPageId)) {
            return $this->getLoginPageId();
        }

        return $registerPageId;
    }

    /**
     * @return bool
     */
    public function hasRegisterPage()
    {
        return $this->getRegisterPage() !== false;
    }

    /**
     * @return Page|false
     */
    public function getRegisterPage()
    {
        return Page::getById($this->getRegisterPageId());
    }

    /**
     * @return string
     */
    public function getRegisterPageUrl()
    {
        $registerPage = $this->getRegisterPage();

        if (!$registerPage) {
            return site_url();
        }

        return $registerPage->getUrl();
    }

    /**
     * @param Post|int $page
     *
     * @return bool
     */
    public function isRegisterPage($page)
    {
        if ($page instanceof Post) {
            return $page->getId() === $this->getRegisterPageId();
        }

        return $page === $this->getRegisterPageId();
    }

    /**
     * @param string $currency
     */
    public function setPaymentCurrency($currency)
    {
        $this->setMeta(Setting::PAYMENT_CURRENCY, $currency);
    }

    /**
     * @return string
     */
    public function getPaymentCurrency()
    {
        return (string)$this->getMeta(Setting::PAYMENT_CURRENCY);
    }

    /**
     * @param string $clientId
     */
    public function setPayPalClientId($clientId)
    {
        $this->setMeta(Setting::PAY_PAL_CLIENT_ID, trim($clientId));
    }

    /**
     * @return string
     */
    public function getPayPalClientId()
    {
        return apply_filters('vehica/payPalClientId', $this->getRawPayPalClientId());
    }

    /**
     * @return string
     */
    public function getRawPayPalClientId()
    {
        return (string)$this->getMeta(Setting::PAY_PAL_CLIENT_ID);
    }

    /**
     * @param string $secret
     */
    public function setPayPalSecret($secret)
    {
        $this->setMeta(Setting::PAY_PAL_SECRET, trim($secret));
    }

    /**
     * @return string
     */
    public function getPayPalSecret()
    {
        return apply_filters('vehica/payPalSecret', $this->getRawPayPalSecret());
    }

    public function getRawPayPalSecret()
    {
        return (string)$this->getMeta(Setting::PAY_PAL_SECRET);
    }

    /**
     * @param string $key
     */
    public function setStripeKey($key)
    {
        $key = trim($key);
        $this->setMeta(Setting::STRIPE_KEY, $key);
    }

    /**
     * @return string
     */
    public function getStripeKey()
    {
        return apply_filters('vehica/stripeKey', $this->getRawStripeKey());
    }

    /**
     * @return string
     */
    public function getRawStripeKey()
    {
        return (string)$this->getMeta(Setting::STRIPE_KEY);
    }

    /**
     * @param string $key
     */
    public function setStripeSecretKey($key)
    {
        $key = trim($key);
        $this->setMeta(Setting::STRIPE_SECRET_KEY, $key);
    }

    /**
     * @return string
     */
    public function getStripeSecretKey()
    {
        return apply_filters('vehica/stripeSecretKey', $this->getRawStripeSecretKey());
    }

    /**
     * @return string
     */
    public function getRawStripeSecretKey()
    {
        return (string)$this->getMeta(Setting::STRIPE_SECRET_KEY);
    }

    /**
     * @param int $collect
     */
    public function setStripeCollectZipCode($collect)
    {
        $this->setMeta(Setting::STRIPE_COLLECT_ZIP_CODE, (int)$collect);
    }

    /**
     * @return bool
     */
    public function stripeCollectZipCode()
    {
        return $this->getMeta(Setting::STRIPE_COLLECT_ZIP_CODE) !== '0';
    }

    /**
     * @param int $monetizationTestMode
     */
    public function setMonetizationTestMode($monetizationTestMode)
    {
        $monetizationTestMode = (int)$monetizationTestMode;
        $this->setMeta(Setting::MONETIZATION_TEST_MODE, $monetizationTestMode);
    }

    /**
     * @return bool
     */
    public function isMonetizationTestModeEnabled()
    {
        $monetizationTestMode = (int)$this->getMeta(Setting::MONETIZATION_TEST_MODE);

        return !empty($monetizationTestMode);
    }

    /**
     * @param string $role
     */
    public function setPrivateUserRole($role)
    {
        $this->setMeta(Setting::PRIVATE_USER_ROLE, $role);
    }

    /**
     * @return string
     */
    public function getPrivateUserRole()
    {
        $role = (string)$this->getMeta(Setting::PRIVATE_USER_ROLE);

        if (empty($role)) {
            return 'vehica_private_role';
        }

        return $role;
    }

    /**
     * @param string $role
     */
    public function setBusinessUserRole($role)
    {
        $this->setMeta(Setting::BUSINESS_USER_ROLE, $role);
    }

    /**
     * @return string
     */
    public function getBusinessUserRole()
    {
        $role = (string)$this->getMeta(Setting::BUSINESS_USER_ROLE);

        if (empty($role)) {
            return 'vehica_business_role';
        }

        return $role;
    }

    /**
     * @param int $size
     */
    public function setMaxImageFileSize($size)
    {
        $size = (int)$size;
        $this->setMeta(Setting::MAX_IMAGE_FILE_SIZE, $size);
    }

    /**
     * @return int
     */
    public function getMaxImageFileSize()
    {
        $size = (int)$this->getMeta(Setting::MAX_IMAGE_FILE_SIZE);

        if (empty($size)) {
            return 8;
        }

        return $size;
    }

    /**
     * @param int $number
     */
    public function setMaxImageNumber($number)
    {
        $number = (int)$number;
        $this->setMeta(Setting::MAX_IMAGE_NUMBER, $number);
    }

    /**
     * @return int
     */
    public function getMaxImageNumber()
    {
        $number = $this->getMeta(Setting::MAX_IMAGE_NUMBER);

        if (empty($number)) {
            return 10;
        }

        return $number;
    }

    /**
     * @param int $size
     */
    public function setMaxAttachmentFileSize($size)
    {
        $size = (int)$size;
        $this->setMeta(Setting::MAX_ATTACHMENT_FILE_SIZE, $size);
    }

    /**
     * @return int
     */
    public function getMaxAttachmentFileSize()
    {
        $size = (int)$this->getMeta(Setting::MAX_ATTACHMENT_FILE_SIZE);

        if (empty($size)) {
            return 8;
        }

        return $size;
    }

    /**
     * @param int $number
     */
    public function setMaxAttachmentNumber($number)
    {
        $number = (int)$number;
        $this->setMeta(Setting::MAX_ATTACHMENT_NUMBER, $number);
    }

    /**
     * @return int
     */
    public function getMaxAttachmentNumber()
    {
        $number = $this->getMeta(Setting::MAX_ATTACHMENT_NUMBER);

        if (empty($number)) {
            return 10;
        }

        return $number;
    }

    /**
     * @param int $submitWithoutLogin
     */
    public function setSubmitWithoutLogin($submitWithoutLogin)
    {
        $submitWithoutLogin = (int)$submitWithoutLogin;
        $this->setMeta(Setting::SUBMIT_WITHOUT_LOGIN, $submitWithoutLogin);
    }

    /**
     * @return bool
     */
    public function isSubmitWithoutLoginEnabled()
    {
        $submitWithoutLogin = (int)$this->getMeta(Setting::SUBMIT_WITHOUT_LOGIN);

        return !empty($submitWithoutLogin);
    }

    /**
     * @param int $moderation
     */
    public function setModeration($moderation)
    {
        $moderation = (int)$moderation;
        $this->setMeta(Setting::MODERATION, $moderation);
    }

    /**
     * @return bool
     */
    public function isModerationEnabled()
    {
        $moderation = (int)$this->getMeta(Setting::MODERATION);

        return !empty($moderation);
    }

    /**
     * @param int $paymentEnabled
     */
    public function setEnablePayment($paymentEnabled)
    {
        $paymentEnabled = (int)$paymentEnabled;
        $this->setMeta(Setting::ENABLE_PAYMENT, $paymentEnabled);
    }

    /**
     * @return bool
     */
    public function isPaymentEnabled()
    {
        $paymentEnabled = (int)$this->getMeta(Setting::ENABLE_PAYMENT);

        return !empty($paymentEnabled) || !empty($this->getMeta(Setting::MONETIZATION_SYSTEM));
    }

    /**
     * @param int $enablePaypal
     */
    public function setEnablePayPal($enablePaypal)
    {
        $enablePaypal = (int)$enablePaypal;
        $this->setMeta(Setting::ENABLE_PAY_PAL, $enablePaypal);
    }

    /**
     * @return bool
     */
    public function isPayPalEnabled()
    {
        $enablePayPal = (int)$this->getMeta(Setting::ENABLE_PAY_PAL);

        return !empty($enablePayPal);
    }

    public function setEnableStripe($enableStripe)
    {
        $enableStripe = (int)$enableStripe;
        $this->setMeta(Setting::ENABLE_STRIPE, $enableStripe);
    }

    /**
     * @return bool
     */
    public function isStripeEnabled()
    {
        $enableStripe = (int)$this->getMeta(Setting::ENABLE_STRIPE);

        return !empty($enableStripe);
    }

    /**
     * @param array $cardFeatures
     */
    public function setCardFeatures($cardFeatures)
    {
        $this->setMeta(Setting::CARD_FEATURES, $cardFeatures);
    }

    /**
     * @return Collection
     */
    public function getCardFeatures()
    {
        $featureIds = $this->getMeta(Setting::CARD_FEATURES);

        if (!is_array($featureIds) || empty($featureIds)) {
            return Collection::make();
        }

        return $this->getSimpleTextAttributes($featureIds);
    }

    /**
     * @param array $attributeIds
     * @return Collection
     */
    private function getSimpleTextAttributes($attributeIds)
    {
        return Collection::make($attributeIds)->map(static function ($featureId) {
            $featureId = (int)$featureId;
            return vehicaApp('simple_text_car_fields')->find(static function ($simpleTextAttribute) use ($featureId) {
                /* @var SimpleTextAttribute $simpleTextAttribute */
                return $simpleTextAttribute->getId() === $featureId;
            });
        })->filter(static function ($feature) {
            return $feature !== false;
        });
    }

    /**
     * @param int $simpleTextAttributeId
     * @return bool
     */
    public function isCardFeature($simpleTextAttributeId)
    {
        return $this->getCardFeatures()->find(static function ($cardFeature) use ($simpleTextAttributeId) {
            /* @var SimpleTextAttribute $cardFeature */
            return $cardFeature->getId() === $simpleTextAttributeId;
        });
    }

    /**
     * @param array $rowPrimaryFeatures
     */
    public function setRowPrimaryFeatures($rowPrimaryFeatures)
    {
        $this->setMeta(Setting::ROW_PRIMARY_FEATURES, $rowPrimaryFeatures);
    }

    /**
     * @return Collection
     */
    public function getRowPrimaryFeatures()
    {
        $featureIds = $this->getMeta(Setting::ROW_PRIMARY_FEATURES);

        if (!is_array($featureIds) || empty($featureIds)) {
            return Collection::make();
        }

        return $this->getSimpleTextAttributes($featureIds);
    }

    /**
     * @param int $simpleTextAttributeId
     * @return bool
     */
    public function isRowPrimaryFeature($simpleTextAttributeId)
    {
        return $this->getRowPrimaryFeatures()->find(static function ($rowPrimaryFeature) use ($simpleTextAttributeId) {
            /* @var SimpleTextAttribute $rowPrimaryFeature */
            return $rowPrimaryFeature->getId() === $simpleTextAttributeId;
        });
    }

    /**
     * @param array $rowSecondaryFeatures
     */
    public function setRowSecondaryFeatures($rowSecondaryFeatures)
    {
        $this->setMeta(Setting::ROW_SECONDARY_FEATURES, $rowSecondaryFeatures);
    }

    /**
     * @return Collection
     */
    public function getRowSecondaryFeatures()
    {
        $featureIds = $this->getMeta(Setting::ROW_SECONDARY_FEATURES);

        if (!is_array($featureIds) || empty($featureIds)) {
            return Collection::make();
        }

        return $this->getSimpleTextAttributes($featureIds);
    }

    /**
     * @param int $simpleTextAttributeId
     * @return bool
     */
    public function isRowSecondaryFeature($simpleTextAttributeId)
    {
        return $this->getRowSecondaryFeatures()->find(static function ($rowSecondaryFeature) use ($simpleTextAttributeId) {
            /* @var SimpleTextAttribute $rowSecondaryFeature */
            return $rowSecondaryFeature->getId() === $simpleTextAttributeId;
        });
    }

    /**
     * @param array $priceFieldIds
     */
    public function setCardPriceField($priceFieldIds)
    {
        $this->setMeta(Setting::CARD_PRICE_FIELD, $priceFieldIds);
    }

    /**
     * @return int
     */
    public function getCardPriceFieldId()
    {
        return (int)$this->getMeta(Setting::CARD_PRICE_FIELD);
    }

    /**
     * @return PriceField|false
     */
    public function getCardPriceField()
    {
        $priceFieldId = $this->getCardPriceFieldId();
        $priceField = vehicaApp('price_fields')->find(static function ($priceField) use ($priceFieldId) {
            /* @var PriceField $priceField */
            return $priceField->getId() === $priceFieldId;
        });

        if (!$priceField instanceof PriceField) {
            return vehicaApp('price_fields')->first();
        }

        return $priceField;
    }

    /**
     * @return array|int[]
     */
    public function getCardPriceFieldIds()
    {
        $priceFieldIds = $this->getMeta(Setting::CARD_PRICE_FIELD);

        if (!is_array($priceFieldIds)) {
            return [(int)$priceFieldIds];
        }

        return Collection::make($priceFieldIds)->map(static function ($priceFieldId) {
            return (int)$priceFieldId;
        })->all();
    }

    /**
     * @return Collection
     */
    public function getCardPriceFields()
    {
        return Collection::make($this->getCardPriceFieldIds())->map(static function ($priceFieldId) {
            return vehicaApp('price_fields')->find(static function ($priceField) use ($priceFieldId) {
                /* @var PriceField $priceField */
                return $priceField->getId() === $priceFieldId;
            });
        })->filter(static function ($priceField) {
            return $priceField !== false;
        });
    }

    /**
     * @param int $galleryFieldId
     */
    public function setCardGalleryField($galleryFieldId)
    {
        $galleryFieldId = (int)$galleryFieldId;
        $this->setMeta(Setting::CARD_GALLERY_FIELD, $galleryFieldId);
    }

    /**
     * @return int
     */
    public function getCardGalleryFieldId()
    {
        return (int)$this->getMeta(Setting::CARD_GALLERY_FIELD);
    }

    /**
     * @return GalleryField|false
     */
    public function getCardGalleryField()
    {
        $galleryFieldId = $this->getCardGalleryFieldId();
        $galleryField = vehicaApp('gallery_fields')->find(static function ($galleryField) use ($galleryFieldId) {
            /* @var PriceField $galleryField */
            return $galleryField->getId() === $galleryFieldId;
        });

        if (!$galleryField instanceof GalleryField) {
            return vehicaApp('gallery_fields')->first();
        }

        return $galleryField;
    }

    /**
     * @param int $enableFreeListing
     */
    public function setEnableFreeListing($enableFreeListing)
    {
        $enableFreeListing = (int)$enableFreeListing;
        $this->setMeta(Setting::ENABLE_FREE_LISTING, $enableFreeListing);
    }

    /**
     * @return bool
     */
    public function isFreeListingEnabled()
    {
        $isFreeListingEnabled = (int)$this->getMeta(Setting::ENABLE_FREE_LISTING);
        return !empty($isFreeListingEnabled);
    }

    /**
     * @param int $days
     */
    public function setFreeListingExpire($days)
    {
        $days = (int)$days;
        $this->setMeta(Setting::FREE_LISTING_EXPIRE, $days);
    }

    /**
     * @return int
     */
    public function getFreeListingExpire()
    {
        return (int)$this->getMeta(Setting::FREE_LISTING_EXPIRE);
    }

    /**
     * @param int $days
     */
    public function setFreeListingFeaturedExpire($days)
    {
        $days = (int)$days;
        $this->setMeta(Setting::FREE_LISTING_FEATURED_EXPIRE, $days);
    }

    /**
     * @return int
     */
    public function getFreeListingFeaturedExpire()
    {
        return (int)$this->getMeta(Setting::FREE_LISTING_FEATURED_EXPIRE);
    }

    /**
     * @param int $enableUserRegister
     */
    public function setEnableUserRegister($enableUserRegister)
    {
        $enableUserRegister = (int)$enableUserRegister;
        $this->setMeta(Setting::ENABLE_USER_REGISTER, $enableUserRegister);
    }

    /**
     * @return bool
     */
    public function isUserRegisterEnabled()
    {
        $enableUserRegister = (int)$this->getMeta(Setting::ENABLE_USER_REGISTER);
        return !empty($enableUserRegister);
    }

    /**
     * @param int $hideFavorite
     */
    public function setHideFavorite($hideFavorite)
    {
        $hideFavorite = (int)$hideFavorite;
        $this->setMeta(Setting::HIDE_FAVORITE, $hideFavorite);
    }

    /**
     * @return bool
     */
    public function showFavorite()
    {
        $hide = (int)$this->getMeta(Setting::HIDE_FAVORITE);
        return empty($hide);
    }

    /**
     * @param int $hidePhotoCount
     */
    public function setCardHidePhotoCount($hidePhotoCount)
    {
        $hidePhotoCount = (int)$hidePhotoCount;
        $this->setMeta(Setting::CARD_HIDE_PHOTO_COUNT, $hidePhotoCount);
    }

    /**
     * @return bool
     */
    public function showPhotoCount()
    {
        $hide = (int)$this->getMeta(Setting::CARD_HIDE_PHOTO_COUNT);
        return empty($hide);
    }

    /**
     * @param string $size
     */
    public function setCardImageSize($size)
    {
        $this->setMeta(Setting::CARD_IMAGE_SIZE, $size);
    }

    /**
     * @return string
     */
    public function getCardImageSize()
    {
        $size = $this->getMeta(Setting::CARD_IMAGE_SIZE);

        if (empty($size)) {
            return 'vehica_335_186';
        }

        return $size;
    }

    /**
     * @param string $size
     */
    public function setRowImageSize($size)
    {
        $this->setMeta(Setting::ROW_IMAGE_SIZE, $size);
    }

    /**
     * @return string
     */
    public function getRowImageSize()
    {
        $size = $this->getMeta(Setting::ROW_IMAGE_SIZE);

        if (empty($size)) {
            return 'vehica_335_186';
        }

        return $size;
    }


    /**
     * @return array
     */
    public function getCardLabelElements()
    {
        $elements = $this->getMeta(Setting::CARD_LABEL);

        if (empty($elements)) {
            return ['featured'];
        }

        return $elements;
    }

    /**
     * @param string $element
     * @return bool
     */
    public function isCardLabelElement($element)
    {
        return in_array($element, $this->getCardLabelElements(), true);
    }

    /**
     * @param array $elements
     */
    public function setCardLabel($elements)
    {
        if (!is_array($elements)) {
            $elements = ['featured'];
        }

        $this->setMeta(Setting::CARD_LABEL, $elements);
    }

    /**
     * @param string $type
     */
    public function setCardLabelType($type)
    {
        $this->setMeta(Setting::CARD_LABEL_TYPE, $type);
    }

    /**
     * @return string
     */
    public function getCardLabelType()
    {
        $type = $this->getMeta(Setting::CARD_LABEL_TYPE);

        if (empty($type)) {
            return 'single';
        }

        return $type;
    }

    /**
     * @return bool
     */
    public function isWoocommerceModeEnabled()
    {
        return $this->getMonetizationSystem() === 'woocommerce';
    }

    /**
     * @return bool
     */
    public function isBuiltinModeEnabled()
    {
        return $this->getMonetizationSystem() === 'builtin';
    }

    /**
     * @param string $monetizationSystem
     */
    public function setMonetizationSystem($monetizationSystem)
    {
        $this->setMeta(Setting::MONETIZATION_SYSTEM, $monetizationSystem);

        $this->setEnablePayment(!empty($monetizationSystem) ? 1 : 0);
    }

    /**
     * @return string
     */
    public function getMonetizationSystem()
    {
        $monetizationSystem = (string)$this->getMeta(Setting::MONETIZATION_SYSTEM);

        if (empty($monetizationSystem) && $this->isPaymentEnabled()) {
            return 'builtin';
        }

        return $monetizationSystem;
    }

    /**
     * @param int $displayMenuButton
     */
    public function setDisplayMenuButton($displayMenuButton)
    {
        $displayMenuButton = (int)$displayMenuButton;
        $this->setMeta(Setting::DISPLAY_MENU_BUTTON, $displayMenuButton);
    }

    /**
     * @return bool
     */
    public function displayMenuButton()
    {
        $displayMenuButton = $this->getMeta(Setting::DISPLAY_MENU_BUTTON);

        if ($displayMenuButton === '') {
            return true;
        }

        return !empty($displayMenuButton);
    }

    /**
     * @param int $displayMenuAccount
     */
    public function setDisplayMenuAccount($displayMenuAccount)
    {
        $displayMenuAccount = (int)$displayMenuAccount;
        $this->setMeta(Setting::DISPLAY_MENU_ACCOUNT, $displayMenuAccount);
    }

    /**
     * @return bool
     */
    public function displayMenuAccount()
    {
        $displayMenuAccount = $this->getMeta(Setting::DISPLAY_MENU_ACCOUNT);

        if ($displayMenuAccount === '') {
            return true;
        }

        return !empty($displayMenuAccount);
    }

    /**
     * @param int $show
     */
    public function setShowContactForPrice($show)
    {
        $show = (int)$show;
        $this->setMeta(Setting::SHOW_CONTACT_FOR_PRICE, $show);
    }

    /**
     * @return bool
     */
    public function showContactForPrice()
    {
        $show = (int)$this->getMeta(Setting::SHOW_CONTACT_FOR_PRICE);
        return !empty($show);
    }

    /**
     * @param string $locationType
     */
    public function setRowLocation($locationType)
    {
        $this->setMeta(Setting::ROW_LOCATION, $locationType);
    }

    /**
     * @param string $locationType
     * @return bool
     */
    public function isRowLocation($locationType)
    {
        return $this->getRowLocation() === $locationType;
    }

    /**
     * @return string|false
     */
    public function getRowLocation()
    {
        $rowLocation = $this->getMeta(Setting::ROW_LOCATION);

        if ($rowLocation === '') {
            return 'user_location';
        }

        if (empty($rowLocation)) {
            return false;
        }

        return $rowLocation;
    }

    /**
     * @param int $hide
     */
    public function setRowHideCalculate($hide)
    {
        $hide = (int)$hide;
        $this->setMeta(Setting::ROW_HIDE_CALCULATE, $hide);
    }

    /**
     * @return int
     */
    public function rowHideCalculate()
    {
        return (int)$this->getMeta(Setting::ROW_HIDE_CALCULATE);
    }

    /**
     * @param int $approve
     */
    public function setApproveListingAfterEdit($approve)
    {
        $this->setMeta(Setting::APPROVE_LISTING_AFTER_EDIT, (int)$approve);
    }

    /**
     * @return bool
     */
    public function listingAfterEditMustBeApproved()
    {
        return !empty($this->getMeta(Setting::APPROVE_LISTING_AFTER_EDIT));
    }

    public function setPanelCardFeatures($features)
    {
        $this->setMeta(Setting::PANEL_CARD_FEATURES, $features);
    }

    /**
     * @return array
     */
    public function getPanelCardFeatures()
    {
        $featureIds = $this->getMeta(Setting::PANEL_CARD_FEATURES);

        if (!is_array($featureIds)) {
            return [];
        }

        return Collection::make($featureIds)->map(static function ($featureId) {
            return (int)$featureId;
        })->all();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function isPanelCardFeature($id)
    {
        return in_array((int)$id, $this->getPanelCardFeatures(), true);
    }

    /**
     * @param string $setting
     */
    public function setPanelPhoneNumber($setting)
    {
        $this->setMeta(Setting::PANEL_PHONE_NUMBER, $setting);
    }

    /**
     * @return string
     */
    public function getPanelPhoneNumber()
    {
        $setting = $this->getMeta(Setting::PANEL_PHONE_NUMBER);

        if (empty($setting)) {
            return 'optional_show';
        }

        return $setting;
    }

    /**
     * @param string $setting
     * @return bool
     */
    public function isPanelPhoneNumberSetting($setting)
    {
        return $this->getPanelPhoneNumber() === $setting;
    }

    /**
     * @param int $allow
     */
    public function setPanelAllowHidePhone($allow)
    {
        $this->setMeta(Setting::PANEL_ALLOW_HIDE_PHONE, (int)$allow);
    }

    /**
     * @return bool
     */
    public function isPanelHidePhoneAllowed()
    {
        return !empty((int)$this->getMeta(Setting::PANEL_ALLOW_HIDE_PHONE));
    }

    /**
     * @param string $key
     */
    public function setRecaptchaSite($key)
    {
        $this->setMeta(Setting::RECAPTCHA_SITE, $key);
    }

    /**
     * @return string
     */
    public function getRecaptchaSite()
    {
        return (string)$this->getMeta(Setting::RECAPTCHA_SITE);
    }

    /**
     * @param int $enable
     */
    public function setEnableRecaptcha($enable)
    {
        $this->setMeta(Setting::ENABLE_RECAPTCHA, (int)$enable);
    }

    /**
     * @return bool
     */
    public function isRecaptchaEnabled()
    {
        return !empty((int)$this->getMeta(Setting::ENABLE_RECAPTCHA));
    }

    /**
     * @param string $key
     */
    public function setRecaptchaSecret($key)
    {
        $this->setMeta(Setting::RECAPTCHA_SECRET, $key);
    }

    /**
     * @return string
     */
    public function getRecaptchaSecret()
    {
        return (string)$this->getMeta(Setting::RECAPTCHA_SECRET);
    }

    /**
     * @param int $enable
     */
    public function setEnableAddPackageRegister($enable)
    {
        $this->setMeta(Setting::ENABLE_ADD_PACKAGE_REGISTER, (int)$enable);
    }

    /**
     * @return bool
     */
    public function isAddPackageWhenRegisterEnabled()
    {
        return !empty((int)$this->getMeta(Setting::ENABLE_ADD_PACKAGE_REGISTER));
    }

    /**
     * @param int $number
     */
    public function setRegisterPackageNumber($number)
    {
        $this->setMeta(Setting::REGISTER_PACKAGE_NUMBER, (int)$number);
    }

    /**
     * @return int
     */
    public function getRegisterPackageNumber()
    {
        return (int)$this->getMeta(Setting::REGISTER_PACKAGE_NUMBER);
    }

    /**
     * @param int $days
     */
    public function setRegisterPackageExpire($days)
    {
        $this->setMeta(Setting::REGISTER_PACKAGE_EXPIRE, (int)$days);
    }

    /**
     * @return int
     */
    public function getRegisterPackageExpire()
    {
        return (int)$this->getMeta(Setting::REGISTER_PACKAGE_EXPIRE);
    }

    /**
     * @param int $days
     */
    public function setRegisterPackageFeaturedExpire($days)
    {
        $this->setMeta(Setting::REGISTER_PACKAGE_FEATURED_EXPIRE, (int)$days);
    }

    /**
     * @return int
     */
    public function getRegisterPackageFeaturedExpire()
    {
        return (int)$this->getMeta(Setting::REGISTER_PACKAGE_FEATURED_EXPIRE);
    }

    /**
     * @param array $fieldIds
     */
    public function setAutoCarTitle($fieldIds)
    {
        $this->setMeta(Setting::AUTO_CAR_TITLE, $fieldIds);
    }

    /**
     * @return array
     */
    public function getAutoCarTitleFieldIds()
    {
        $fieldIds = $this->getMeta(Setting::AUTO_CAR_TITLE);

        if (!is_array($fieldIds)) {
            return [];
        }

        return Collection::make($fieldIds)->map(static function ($fieldId) {
            return (int)$fieldId;
        })->all();
    }

    /**
     * @return Collection
     */
    public function getAutoCarTitleFields()
    {
        return Collection::make($this->getAutoCarTitleFieldIds())
            ->map(static function ($fieldId) {
                return vehicaApp('car_fields')->find(static function ($field) use ($fieldId) {
                    /* @var Field $field */
                    return $field->getId() === $fieldId;
                });
            })->filter(static function ($field) {
                return $field !== false;
            });
    }

    /**
     * @param string $name
     */
    public function setSenderName($name)
    {
        $this->setMeta(Setting::SENDER_NAME, $name);
    }

    /**
     * @return string
     */
    public function getSenderName()
    {
        return (string)$this->getMeta(Setting::SENDER_NAME);
    }

    /**
     * @param string $mail
     */
    public function setSenderMail($mail)
    {
        $this->setMeta(Setting::SENDER_MAIL, $mail);
    }

    /**
     * @return string
     */
    public function getSenderMail()
    {
        return (string)$this->getMeta(Setting::SENDER_MAIL);
    }

    /**
     * @param string $type
     */
    public function setUserAddressType($type)
    {
        $this->setMeta(Setting::USER_ADDRESS_TYPE, $type);
    }

    /**
     * @return string
     */
    public function getUserAddressType()
    {
        $type = $this->getMeta(Setting::USER_ADDRESS_TYPE);

        if (empty($type)) {
            return 'text_input';
        }

        return $type;
    }

    /**
     * @return bool
     */
    public function isUserAddressTextInput()
    {
        return !$this->isUserAddressMap();
    }

    /**
     * @return bool
     */
    public function isUserAddressMap()
    {
        return $this->getUserAddressType() === 'map';
    }

    /**
     * @param string $code
     */
    public function setGoogleMapsSnazzyCode($code)
    {
        $this->setMeta(Setting::GOOGLE_MAPS_SNAZZY_CODE, $code);
    }

    /**
     * @return string
     */
    public function getGoogleMapsSnazzyCode()
    {
        $code = trim($this->getMeta(Setting::GOOGLE_MAPS_SNAZZY_CODE));

        if (empty($code)) {
            return '';
        }

        return $code;
    }

    /**
     * @return bool
     */
    public function hasGoogleMapsSnazzyCode()
    {
        return $this->getGoogleMapsSnazzyCode() !== '';
    }

    /**
     * @param array $location
     */
    public function setGoogleMapsSnazzyLocation($location)
    {
        $this->setMeta(Setting::GOOGLE_MAPS_SNAZZY_LOCATION, $location);
    }

    /**
     * @return array
     */
    public function getGoogleMapsSnazzyLocation()
    {
        $location = $this->getMeta(Setting::GOOGLE_MAPS_SNAZZY_LOCATION);

        if (!is_array($location) || empty($location)) {
            return [];
        }

        return $location;
    }

    /**
     * @param string $location
     * @return bool
     */
    public function isGoogleMapsSnazzyLocationSelected($location)
    {
        $locations = $this->getGoogleMapsSnazzyLocation();

        if (empty($locations)) {
            return true;
        }

        return in_array($location, $locations, true);
    }

    /**
     * @param string $clientId
     */
    public function setGoogleAuthClientId($clientId)
    {
        $this->setMeta(Setting::GOOGLE_AUTH_CLIENT_ID, $clientId);
    }

    /**
     * @return string
     */
    public function getGoogleAuthClientId()
    {
        $clientId = $this->getMeta(Setting::GOOGLE_AUTH_CLIENT_ID);

        if (empty($clientId)) {
            return '';
        }

        return $clientId;
    }

    /**
     * @param string $clientSecret
     */
    public function setGoogleAuthClientSecret($clientSecret)
    {
        $this->setMeta(Setting::GOOGLE_AUTH_CLIENT_SECRET, $clientSecret);
    }

    /**
     * @return string
     */
    public function getGoogleAuthClientSecret()
    {
        $clientSecret = $this->getMeta(Setting::GOOGLE_AUTH_CLIENT_SECRET);

        if (empty($clientSecret)) {
            return '';
        }

        return $clientSecret;
    }

    /**
     * @param int $enable
     */
    public function setEnableGoogleAuth($enable)
    {
        $this->setMeta(Setting::ENABLE_GOOGLE_AUTH, (int)$enable);
    }

    /**
     * @return bool
     */
    public function isGoogleAuthEnabled()
    {
        $enabled = (int)$this->getMeta(Setting::ENABLE_GOOGLE_AUTH);
        return !empty($enabled);
    }

    public function setEnableFacebookAuth($enable)
    {
        $enable = (int)$enable;
        $this->setMeta(Setting::ENABLE_FACEBOOK_AUTH, $enable);
    }

    /**
     * @return bool
     */
    public function isFacebookAuthEnabled()
    {
        $enabled = (int)$this->getMeta(Setting::ENABLE_FACEBOOK_AUTH);
        return !empty($enabled);
    }

    /**
     * @param string $appId
     */
    public function setFacebookAppId($appId)
    {
        $this->setMeta(Setting::FACEBOOK_APP_ID, $appId);
    }

    /**
     * @return string
     */
    public function getFacebookAppId()
    {
        $appId = $this->getMeta(Setting::FACEBOOK_APP_ID);

        if (empty($appId)) {
            return '';
        }

        return $appId;
    }

    /**
     * @param string $appSecret
     */
    public function setFacebookAppSecret($appSecret)
    {
        $this->setMeta(Setting::FACEBOOK_APP_SECRET, $appSecret);
    }

    /**
     * @return string
     */
    public function getFacebookAppSecret()
    {
        $appSecret = $this->getMeta(Setting::FACEBOOK_APP_SECRET);

        if (empty($appSecret)) {
            return '';
        }

        return $appSecret;
    }

    /**
     * @param array $termIds
     */
    public function setExcludeFromSearch($termIds)
    {
        $this->setMeta(Setting::EXCLUDE_FROM_SEARCH, $termIds);
    }

    /**
     * @return array
     */
    public function getExcludeFromSearch()
    {
        $termIds = $this->getMeta(Setting::EXCLUDE_FROM_SEARCH);

        if (!is_array($termIds)) {
            return [];
        }

        return Collection::make($termIds)->map(static function ($termId) {
            return (int)$termId;
        })->all();
    }

    /**
     * @param int $compare
     */
    public function setCompare($compare)
    {
        $this->setMeta(Setting::COMPARE, (int)$compare);
    }

    /**
     * @return bool
     */
    public function isCompareEnabled()
    {
        return !empty($this->getMeta(Setting::COMPARE));
    }

    /**
     * @return int
     */
    public function getCompareMode()
    {
        return (int)$this->getMeta(Setting::COMPARE);
    }

    /**
     * @param $enable
     */
    public function setEnableCustomTemplates($enable)
    {
        $this->setMeta(Setting::ENABLE_CUSTOM_TEMPLATES, (int)$enable);
    }

    /**
     * @return bool
     */
    public function customTemplatesEnabled()
    {
        $enabled = (int)$this->getMeta(Setting::ENABLE_CUSTOM_TEMPLATES);
        return !empty($enabled);
    }

    /**
     * @return bool
     */
    public function isMessageSystemEnabled()
    {
        return !empty((int)$this->getMeta(Setting::MESSAGE_SYSTEM));
    }

    /**
     * @param int $enable
     */
    public function setMessageSystem($enable)
    {
        $this->setMeta(Setting::MESSAGE_SYSTEM, (int)$enable);
    }

    /**
     * @param int $pageId
     */
    public function setRedirectAfterListingCreated($pageId)
    {
        $this->setMeta(Setting::REDIRECT_AFTER_LISTING_CREATED, (int)$pageId);
    }

    /**
     * @return int
     */
    public function getRedirectAfterListingCreatedPageId()
    {
        return (int)$this->getMeta(Setting::REDIRECT_AFTER_LISTING_CREATED);
    }

    /**
     * @return Page|false
     */
    public function getRedirectAfterListingCreatedPage()
    {
        $pageId = $this->getRedirectAfterListingCreatedPageId();

        if (empty($pageId)) {
            return false;
        }

        return Page::getById($pageId);
    }

    /**
     * @param int $delete
     */
    public function setDeleteImagesWithCar($delete)
    {
        $this->setMeta(Setting::DELETE_IMAGES_WITH_CAR, (int)$delete);
    }

    /**
     * @return bool
     */
    public function deleteImagesWithCar()
    {
        return !empty((int)$this->getMeta(Setting::DELETE_IMAGES_WITH_CAR));
    }

    /**
     * @param string $mode
     */
    public function setUserRoleMode($mode)
    {
        $this->setMeta(Setting::USER_ROLE_MODE, $mode);
    }

    /**
     * @return string
     */
    public function getUserRoleMode()
    {
        $mode = (string)$this->getMeta(Setting::USER_ROLE_MODE);

        if (empty($mode)) {
            return 'enabled';
        }

        return $mode;
    }

    /**
     * @param int $enabled
     */
    public function setCardMultilineFeatures($enabled)
    {
        $this->setMeta(Setting::CARD_MULTILINE_FEATURES, (int)$enabled);
    }

    public function isCardMultilineFeaturesEnabled()
    {
        return !empty($this->getMeta(Setting::CARD_MULTILINE_FEATURES));
    }

    /**
     * @param array $pages
     */
    public function setCustomArchivePages($pages)
    {
        $this->setMeta(Setting::CUSTOM_ARCHIVE_PAGES, $pages);
    }

    /**
     * @return array
     */
    public function getCustomArchivePageIds()
    {
        $ids = $this->getMeta(Setting::CUSTOM_ARCHIVE_PAGES);

        if (!is_array($ids)) {
            return [];
        }

        return Collection::make($ids)->map(static function ($id) {
            return (int)$id;
        })->all();
    }

    /**
     * @return Collection
     */
    public function getCustomArchivePages()
    {
        return Collection::make($this->getCustomArchivePageIds())->map(static function ($pageId) {
            return Page::getById($pageId);
        })->filter(static function ($page) {
            return $page !== false;
        });
    }

    /**
     * @param int $enable
     */
    public function setEnablePrettyUrls($enable)
    {
        $this->setMeta(Setting::ENABLE_PRETTY_URLS, (int)$enable);
    }

    /**
     * @return bool
     */
    public function prettyUrlsEnabled()
    {
        return !empty($this->getMeta(Setting::ENABLE_PRETTY_URLS));
    }

    /**
     * @param int $enable
     */
    public function setEnableWhatsApp($enable)
    {
        $this->setMeta(Setting::ENABLE_WHATS_APP, (int)$enable);
    }

    /**
     * @return bool
     */
    public function isWhatsAppEnabled()
    {
        return !empty((int)$this->getMeta(Setting::ENABLE_WHATS_APP));
    }

    /**
     * @param string $type
     */
    public function setDescriptionType($type)
    {
        $this->setMeta(Setting::DESCRIPTION_TYPE, (string)$type);
    }

    /**
     * @return string
     */
    public function getDescriptionType()
    {
        $type = $this->getMeta(Setting::DESCRIPTION_TYPE);

        if (empty($type)) {
            return 'rich';
        }

        return $type;
    }

    /**
     * @param string $policyLabel
     */
    public function setPolicyLabel($policyLabel)
    {
        $this->setMeta(Setting::POLICY_LABEL, $policyLabel);
    }

    /**
     * @return string
     */
    public function getPolicyLabel()
    {
        return (string)$this->getMeta(Setting::POLICY_LABEL);
    }

    /**
     * @param string $tip
     */
    public function setGalleryFieldTip($tip)
    {
        $this->setMeta(Setting::GALLERY_FIELD_TIP, $tip);
    }

    /**
     * @return string
     */
    public function getGalleryFieldTip()
    {
        return (string)$this->getMeta(Setting::GALLERY_FIELD_TIP);
    }

    /**
     * @param string $tip
     */
    public function setAttachmentsFieldTip($tip)
    {
        $this->setMeta(Setting::ATTACHMENTS_FIELD_TIP, $tip);
    }

    /**
     * @return string
     */
    public function getAttachmentsFieldTip()
    {
        return (string)$this->getMeta(Setting::ATTACHMENTS_FIELD_TIP);
    }

    /**
     * @param $isRequired
     */
    public function setRequiredDescription($isRequired)
    {
        $this->setMeta(Setting::REQUIRED_DESCRIPTION, (int)$isRequired);
    }

    /**
     * @return bool
     */
    public function isDescriptionRequired()
    {
        return !empty((int)$this->getMeta(Setting::REQUIRED_DESCRIPTION));
    }

    /**
     * @param $type
     */
    public function setContactOwnerType($type)
    {
        $this->setMeta(Setting::CONTACT_OWNER_TYPE, $type);
    }

    /**
     * @return string|int
     */
    public function getContactOwnerType()
    {
        $type = $this->getMeta(Setting::CONTACT_OWNER_TYPE);

        if ($type !== 'messages') {
            return (int)$type;
        }

        return $type;
    }

    /**
     * @param int $hide
     */
    public function setHideHelp($hide)
    {
        $this->setMeta(Setting::HIDE_HELP, (int)$hide);
    }

    /**
     * @return bool
     */
    public function hideHelp()
    {
        return !empty((int)$this->getMeta(Setting::HIDE_HELP));
    }

    public function setCustomCtaButtonText($text)
    {
        $this->setMeta(Setting::CUSTOM_CTA_BUTTON_TEXT, $text);
    }

    /**
     * @return string
     */
    public function getCustomCtaButtonText()
    {
        return (string)$this->getMeta(Setting::CUSTOM_CTA_BUTTON_TEXT);
    }
}