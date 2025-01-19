<?php

namespace Vehica\Model\Post\Config;


/**
 * Class Setting
 *
 * @package Vehica\Model\Post\Config
 */
class Setting
{
    const THOUSANDS_SEPARATOR = 'vehica_thousands_separator';
    const DECIMAL_SEPARATOR = 'vehica_decimal_separator';
    const PRIMARY_COLOR = 'vehica_primary_color';
    const PRIMARY_COLOR_DEFAULT = '#ff4605';
    const HEADING_FONT = 'vehica_heading_font';
    const HEADING_FONT_DEFAULT = 'Oswald';
    const TEXT_FONT = 'vehica_text_font';
    const TEXT_FONT_DEFAULT = 'Roboto';
    const HOMEPAGE = 'vehica_homepage';
    const BLOG_PAGE = 'vehica_blog_page';
    const CALCULATOR_PAGE = 'vehica_calculator_page';
    const PHONE = 'vehica_phone';
    const EMAIL = 'vehica_email';
    const ADDRESS = 'vehica_address';
    const LOGO = 'vehica_logo';
    const LOGO_INVERSE = 'vehica_logo_inverse';
    const FACEBOOK_API = 'vehica_facebook_api';
    const FACEBOOK_PROFILE = 'vehica_facebook_profile';
    const TWITTER_PROFILE = 'vehica_twitter_profile';
    const YOUTUBE_PROFILE = 'vehica_youtube_profile';
    const INSTAGRAM_PROFILE = 'vehica_instagram_profile';
    const LINKEDIN_PROFILE = 'vehica_linkedin_profile';
    const TIKTOK_PROFILE = 'vehica_tiktok_profile';
    const TELEGRAM_PROFILE = 'vehica_telegram_profile';
    const GOOGLE_MAPS_API_KEY = 'vehica_google_maps_api_key';
    const GOOGLE_MAPS_LANGUAGE = 'vehica_google_maps_language';
    const GOOGLE_MAPS_SNAZZY_CODE = 'vehica_google_maps_snazzy_code';
    const GOOGLE_MAPS_SNAZZY_LOCATION = 'vehica_google_maps_snazzy_location';
    const GOOGLE_MAPS_INITIAL_POSITION_LAT = 'vehica_google_maps_initial_position_lat';
    const GOOGLE_MAPS_INITIAL_POSITION_LNG = 'vehica_google_maps_initial_position_lng';
    const GOOGLE_MAPS_INITIAL_ZOOM = 'vehica_google_maps_initial_zoom';
    const GOOGLE_MAPS_INITIAL_POSITION_DEFAULT_LAT = 40.848531;
    const GOOGLE_MAPS_INITIAL_POSITION_DEFAULT_LNG = -73.912534;
    const MAIN_MENU = 'vehica_main_menu';
    const STICKY_MENU = 'vehica_sticky_menu';
    const FOOTER_MENU = 'vehica_footer_menu';
    const FOOTER_ABOUT_US = 'vehica_footer_about_us';
    const COPYRIGHTS_TEXT = 'vehica_copyrights_text';
    const ERROR_PAGE = 'vehica_error_page';
    const HIDE_IMPORTER = 'vehica_hide_importer';
    const CAR_BREADCRUMBS = 'vehica_car_breadcrumbs';
    const LISTINGS_TABLE_TAXONOMIES = 'vehica_listings_table_taxonomies';
    const AUTO_CAR_TITLE = 'vehica_auto_car_title';
    const CARD_FEATURES = 'vehica_card_features';
    const CARD_IMAGE_SIZE = 'vehica_card_image_size';
    const ROW_IMAGE_SIZE = 'vehica_row_image_size';
    const ROW_PRIMARY_FEATURES = 'vehica_row_primary_features';
    const ROW_SECONDARY_FEATURES = 'vehica_row_secondary_features';
    const ROW_LOCATION = 'vehica_row_location';
    const ROW_HIDE_CALCULATE = 'vehica_row_hide_calculate';
    const CARD_PRICE_FIELD = 'vehica_card_price_field';
    const CARD_GALLERY_FIELD = 'vehica_card_gallery_field';
    const CARD_HIDE_PHOTO_COUNT = 'vehica_card_hide_photo_count';
    const HIDE_FAVORITE = 'vehica_hide_favorite';
    const CARD_LABEL = 'vehica_card_label';
    const CARD_LABEL_TYPE = 'vehica_card_label_type';
    const CARD_MULTILINE_FEATURES = 'vehica_card_multiline_features';
    const ENABLE_USER_REGISTER = 'vehica_enable_user_register';
    const PANEL_PAGE = 'vehica_panel_page';
    const LOGIN_PAGE = 'vehica_login_page';
    const SUBMIT_WITHOUT_LOGIN = 'vehica_submit_without_login';
    const REGISTER_PAGE = 'vehica_register_page';
    const USER_CONFIRMATION = 'vehica_user_confirmation';
    const MODERATION = 'vehica_moderation';
    const PRIVATE_USER_ROLE = 'vehica_private_user_role';
    const BUSINESS_USER_ROLE = 'vehica_business_user_role';
    const MONETIZATION_TEST_MODE = 'vehica_monetization_test_mode';
    const ENABLE_STRIPE = 'vehica_enable_stripe';
    const STRIPE_KEY = 'vehica_stripe_key';
    const STRIPE_SECRET_KEY = 'vehica_stripe_secret_key';
    const STRIPE_COLLECT_ZIP_CODE = 'vehica_stripe_collect_zip_code';
    const ENABLE_PAY_PAL = 'vehica_enable_pay_pal';
    const PAY_PAL_CLIENT_ID = 'vehica_pay_pal_client_id';
    const PAY_PAL_SECRET = 'vehica_pay_pal_secret';
    const MAX_IMAGE_NUMBER = 'vehica_max_image_number';
    const MAX_IMAGE_FILE_SIZE = 'vehica_max_image_file_size';
    const MAX_ATTACHMENT_NUMBER = 'vehica_max_attachment_number';
    const MAX_ATTACHMENT_FILE_SIZE = 'vehica_max_attachment_file_size';
    const ENABLE_PAYMENT = 'vehica_enable_payment';
    const PAYMENT_CURRENCY = 'vehica_payment_currency';
    const ENABLE_FREE_LISTING = 'vehica_enable_free_listing';
    const FREE_LISTING_EXPIRE = 'vehica_free_listing_expire';
    const FREE_LISTING_FEATURED_EXPIRE = 'vehica_free_listing_featured_expire';
    const MONETIZATION_SYSTEM = 'vehica_monetization_system';
    const DISPLAY_MENU_BUTTON = 'vehica_display_menu_button';
    const DISPLAY_MENU_ACCOUNT = 'vehica_display_menu_account';
    const SHOW_CONTACT_FOR_PRICE = 'vehica_show_contact_for_price';
    const APPROVE_LISTING_AFTER_EDIT = 'vehica_approve_listing_after_edit';
    const PANEL_CARD_FEATURES = 'vehica_panel_card_features';
    const PANEL_PHONE_NUMBER = 'vehica_panel_phone_number';
    const PANEL_ALLOW_HIDE_PHONE = 'vehica_panel_allow_hide_phone';
    const ENABLE_RECAPTCHA = 'vehica_enable_recaptcha';
    const RECAPTCHA_SITE = 'vehica_recaptcha_site';
    const RECAPTCHA_SECRET = 'vehica_recaptcha_secret';
    const ENABLE_ADD_PACKAGE_REGISTER = 'vehica_enable_add_package_register';
    const REGISTER_PACKAGE_NUMBER = 'vehica_register_package_number';
    const REGISTER_PACKAGE_EXPIRE = 'vehica_register_package_expire';
    const REGISTER_PACKAGE_FEATURED_EXPIRE = 'vehica_register_package_featured_expire';
    const SENDER_NAME = 'vehica_sender_name';
    const SENDER_MAIL = 'vehica_sender_mail';
    const USER_ADDRESS_TYPE = 'vehica_user_address_type';
    const ENABLE_GOOGLE_AUTH = 'vehica_enable_google_auth';
    const GOOGLE_AUTH_CLIENT_ID = 'vehica_google_auth_client_id';
    const GOOGLE_AUTH_CLIENT_SECRET = 'vehica_google_auth_client_secret';
    const ENABLE_FACEBOOK_AUTH = 'vehica_enable_facebook_auth';
    const FACEBOOK_APP_ID = 'vehica_facebook_app_id';
    const FACEBOOK_APP_SECRET = 'vehica_facebook_app_secret';
    const EXCLUDE_FROM_SEARCH = 'vehica_exclude_from_search';
    const COMPARE = 'vehica_compare';
    const COMPARE_PAGE = 'vehica_compare_page';
    const ENABLE_CUSTOM_TEMPLATES = 'vehica_enable_custom_templates';
    const CTA_PAGE = 'vehica_cta_page';
    const MESSAGE_SYSTEM = 'vehica_message_system';
    const REDIRECT_AFTER_LISTING_CREATED = 'vehica_redirect_after_listing_created';
    const DELETE_IMAGES_WITH_CAR = 'vehica_delete_images_with_car';
    const USER_ROLE_MODE = 'vehica_user_role_mode';
    const CUSTOM_ARCHIVE_PAGES = 'vehica_custom_archive_pages';
    const ENABLE_PRETTY_URLS = 'vehica_enable_pretty_urls';
    const ENABLE_WHATS_APP = 'vehica_enable_whats_app';
    const DESCRIPTION_TYPE = 'vehica_description_type';
    const POLICY_LABEL = 'vehica_policy_label';
    const ATTACHMENTS_FIELD_TIP = 'vehica_attachments_field_tip';
    const GALLERY_FIELD_TIP = 'vehica_gallery_field_tip';
    const REQUIRED_DESCRIPTION = 'vehica_required_description';
    const CONTACT_OWNER_TYPE = 'vehica_contact_owner_type';
    const HIDE_HELP = 'vehica_hide_help';
    const CUSTOM_CTA_BUTTON_TEXT = 'vehica_custom_cta_button_text';

    /**
     * @return string[]
     */
    public static function getUserPanelSettings()
    {
        return [
            self::ENABLE_GOOGLE_AUTH,
            self::GOOGLE_AUTH_CLIENT_ID,
            self::GOOGLE_AUTH_CLIENT_SECRET,
            self::ENABLE_FACEBOOK_AUTH,
            self::FACEBOOK_APP_ID,
            self::FACEBOOK_APP_SECRET,
            self::APPROVE_LISTING_AFTER_EDIT,
            self::PANEL_CARD_FEATURES,
            self::PANEL_PHONE_NUMBER,
            self::PANEL_ALLOW_HIDE_PHONE,
            self::RECAPTCHA_SITE,
            self::RECAPTCHA_SECRET,
            self::ENABLE_RECAPTCHA,
            self::MODERATION,
            self::ENABLE_USER_REGISTER,
            self::SUBMIT_WITHOUT_LOGIN,
            self::MAX_IMAGE_FILE_SIZE,
            self::MAX_IMAGE_NUMBER,
            self::USER_ADDRESS_TYPE,
            self::REDIRECT_AFTER_LISTING_CREATED,
            self::USER_ROLE_MODE,
            self::ENABLE_WHATS_APP,
            self::DESCRIPTION_TYPE,
            self::POLICY_LABEL,
            self::MAX_ATTACHMENT_NUMBER,
            self::MAX_ATTACHMENT_FILE_SIZE,
            self::ATTACHMENTS_FIELD_TIP,
            self::GALLERY_FIELD_TIP,
            self::REQUIRED_DESCRIPTION,
            self::MESSAGE_SYSTEM,
            self::CONTACT_OWNER_TYPE,
        ];
    }

    /**
     * @return array
     */
    public static function getMapsSettings()
    {
        return [
            self::GOOGLE_MAPS_API_KEY,
            self::GOOGLE_MAPS_LANGUAGE,
            self::GOOGLE_MAPS_SNAZZY_CODE,
            self::GOOGLE_MAPS_SNAZZY_LOCATION,
            self::GOOGLE_MAPS_INITIAL_POSITION_LAT,
            self::GOOGLE_MAPS_INITIAL_POSITION_LNG,
            self::GOOGLE_MAPS_INITIAL_ZOOM,
        ];
    }

    /**
     * @return string[]
     */
    public static function getNotificationSettings()
    {
        return [
            self::USER_CONFIRMATION,
            self::SENDER_NAME,
            self::SENDER_MAIL,
        ];
    }

    /**
     * @return string[]
     */
    public static function getAdvancedSettings()
    {
        return [
            self::PANEL_PAGE,
            self::LOGIN_PAGE,
            self::REGISTER_PAGE,
            self::ERROR_PAGE,
            self::BLOG_PAGE,
            self::COMPARE_PAGE,
            self::CALCULATOR_PAGE,
            self::BUSINESS_USER_ROLE,
            self::PRIVATE_USER_ROLE,
            self::CUSTOM_ARCHIVE_PAGES,
        ];
    }

    /**
     * @return string[]
     */
    public static function getFrontendPanelSettings()
    {
        return [
            self::ENABLE_STRIPE,
            self::STRIPE_KEY,
            self::STRIPE_SECRET_KEY,
            self::ENABLE_PAY_PAL,
            self::PAY_PAL_CLIENT_ID,
            self::PAY_PAL_SECRET,
            self::MONETIZATION_TEST_MODE,
            self::PAYMENT_CURRENCY,
            self::ENABLE_FREE_LISTING,
            self::FREE_LISTING_EXPIRE,
            self::FREE_LISTING_FEATURED_EXPIRE,
            self::MONETIZATION_SYSTEM,
            self::ENABLE_ADD_PACKAGE_REGISTER,
            self::REGISTER_PACKAGE_NUMBER,
            self::REGISTER_PACKAGE_EXPIRE,
            self::REGISTER_PACKAGE_FEATURED_EXPIRE,
            self::STRIPE_COLLECT_ZIP_CODE,
        ];
    }

    /**
     * @return array
     */
    public static function getSettings()
    {
        return [
            self::CARD_GALLERY_FIELD,
            self::CARD_PRICE_FIELD,
            self::COMPARE,
            self::CARD_FEATURES,
            self::CARD_HIDE_PHOTO_COUNT,
            self::ROW_PRIMARY_FEATURES,
            self::ROW_SECONDARY_FEATURES,
            self::ROW_HIDE_CALCULATE,
            self::ROW_LOCATION,
            self::HOMEPAGE,
            self::PHONE,
            self::EMAIL,
            self::ADDRESS,
            self::LOGO,
            self::LOGO_INVERSE,
            self::FACEBOOK_API,
            self::FACEBOOK_PROFILE,
            self::YOUTUBE_PROFILE,
            self::INSTAGRAM_PROFILE,
            self::LINKEDIN_PROFILE,
            self::TWITTER_PROFILE,
            self::TIKTOK_PROFILE,
            self::TELEGRAM_PROFILE,
            self::MAIN_MENU,
            self::FOOTER_MENU,
            self::FOOTER_ABOUT_US,
            self::COPYRIGHTS_TEXT,
            self::THOUSANDS_SEPARATOR,
            self::DECIMAL_SEPARATOR,
            self::STICKY_MENU,
            self::HIDE_IMPORTER,
            self::CAR_BREADCRUMBS,
            self::HIDE_FAVORITE,
            self::ROW_IMAGE_SIZE,
            self::CARD_IMAGE_SIZE,
            self::CARD_LABEL,
            self::CARD_LABEL_TYPE,
            self::CARD_MULTILINE_FEATURES,
            self::DISPLAY_MENU_ACCOUNT,
            self::DISPLAY_MENU_BUTTON,
            self::SHOW_CONTACT_FOR_PRICE,
            self::AUTO_CAR_TITLE,
            self::PRIMARY_COLOR,
            self::HEADING_FONT,
            self::TEXT_FONT,
            self::MODERATION,
            self::ENABLE_USER_REGISTER,
            self::SUBMIT_WITHOUT_LOGIN,
            self::MESSAGE_SYSTEM,
            self::ENABLE_CUSTOM_TEMPLATES,
            self::CTA_PAGE,
            self::EXCLUDE_FROM_SEARCH,
            self::DELETE_IMAGES_WITH_CAR,
            self::ENABLE_PRETTY_URLS,
            self::CONTACT_OWNER_TYPE,
            self::HIDE_HELP,
            self::CUSTOM_CTA_BUTTON_TEXT,
            self::LISTINGS_TABLE_TAXONOMIES,
        ];
    }
}