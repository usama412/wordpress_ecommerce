<?php

namespace Vehica\Widgets\General;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\User\User;
use Vehica\Panel\PanelField\AttachmentsPanelField;
use Vehica\Panel\PanelField\DescriptionPanelField;
use Vehica\Panel\PanelField\EmbedPanelField;
use Vehica\Panel\PanelField\GalleryPanelField;
use Vehica\Panel\PanelField\LocationPanelField;
use Vehica\Panel\PanelField\NamePanelField;
use Vehica\Panel\PanelField\PanelField;
use Vehica\Panel\PanelField\PricePanelField;
use Vehica\Panel\PanelField\TaxonomyPanelField;

/**
 * Class PanelGeneralWidget
 *
 * @package Vehica\Widgets\General
 */
class PanelGeneralWidget extends GeneralWidget
{
    const NAME = 'vehica_panel_general_widget';
    const TEMPLATE = 'general/panel/panel';
    const ACTION_TYPE = 'action';
    const ACTION_TYPE_RESET_PASSWORD = 'reset_password';
    const ACTION_TYPE_CHANGE_PASSWORD = 'change_password';
    const ACTION_TYPE_SET_PASSWORD = 'set_password';
    const ACTION_TYPE_CREATE_CAR = 'create';
    const ACTION_TYPE_EDIT_CAR = 'edit';
    const ACTION_TYPE_BUY_PACKAGE = 'buy_package';
    const ACTION_TYPE_CAR_LIST = 'list';
    const ACTION_TYPE_FAVORITE = 'favorite';
    const ACTION_TYPE_MESSAGES = 'messages';
    const ACTION_TYPE_ACCOUNT = 'account';
    const ACTION_TYPE_ACCOUNT_CHANGE_PASSWORD = 'account_change_password';
    const ACTION_TYPE_ACCOUNT_SOCIAL = 'account_social';
    const FIELDS = 'vehica_fields';

    /**
     * @return string
     */
    public function get_title()
    {
        return esc_html__('Panel', 'vehica-core');
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            self::NAME . '_content',
            [
                'label' => esc_html__('Panel', 'vehica-core'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->addMaxVisibleTerms();

        $this->addGalleryTipControl();

        $this->addPolicyControls();

        $this->end_controls_section();
    }

    private function addPolicyControls()
    {
        $this->add_control(
            'show_policy',
            [
                'label' => esc_html__('Submit Listing - I accept Private Policy / Terms of Use', 'vehica-core'),
                'type' => Controls_Manager::SWITCHER,
                'return_value' => '1',
                'label_block' => true,
                'default' => '0'
            ]
        );

        $this->add_control(
            'policy',
            [
                'label' => esc_html__('Submit Listing - I accept Private Policy / Terms of Use', 'vehica-core'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'I accept the <a href="#">privacy policy</a>',
                'label_block' => true,
                'condition' => [
                    'show_policy' => '1',
                ]
            ]
        );
    }

    /**
     * @return bool
     */
    public function showPolicy()
    {
        $show = (int)$this->get_settings_for_display('show_policy');
        return !empty($show);
    }

    /**
     * @return string
     */
    public function getPolicy()
    {
        return (string)$this->get_settings_for_display('policy');
    }

    /**
     * @return int
     */
    public function getMaxVisibleTerms()
    {
        $max = (int)$this->get_settings_for_display('taxonomy_max_visible_terms');

        if (empty($max)) {
            return 5;
        }

        return $max;
    }

    private function addMaxVisibleTerms()
    {
        $this->add_control(
            'taxonomy_max_visible_terms',
            [
                'label' => esc_html__('Number of Visible Terms for Multiple choice Taxonomy Field before Load More', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'label_block' => true,
            ]
        );
    }

    private function addGalleryTipControl()
    {
        $this->add_control(
            'vehica_gallery_tip',
            [
                'label' => esc_html__('Gallery Tip', 'vehica-core'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => sprintf(
                    '%s <a href="#" target="_blank">%s</a>',
                    esc_html__('Attractive photos increase the popularity of the advertisement up to 5 times!',
                        'vehica-core'),
                    esc_html__('How do you take good pictures?', 'vehica-core')
                )
            ]
        );
    }

    /**
     * @return string
     */
    public function getGalleryTip()
    {
        $tip = vehicaApp('settings_config')->getGalleryFieldTip();
        if (!empty($tip)) {
            return $tip;
        }

        return (string)$this->get_settings_for_display('vehica_gallery_tip');
    }

    /**
     * @return bool
     */
    public function hasGalleryTip()
    {
        return $this->getGalleryTip() !== '' || !empty(vehicaApp('settings_config')->getGalleryFieldTip());
    }

    /**
     * @return string
     */
    public function getAttachmentsTip()
    {
        return vehicaApp('settings_config')->getAttachmentsFieldTip();
    }

    /**
     * @return bool
     */
    public function hasAttachmentsTip()
    {
        return $this->getAttachmentsTip() !== '';
    }

    /**
     * @return string
     */
    public function getActionType()
    {
        if (!isset($_GET[self::ACTION_TYPE])) {
            return '';
        }

        return $_GET[self::ACTION_TYPE];
    }

    /**
     * @return string
     */
    public function getLogoutUrl()
    {
        return admin_url('admin-post.php?action=vehica_logout');
    }

    /**
     * @return string
     */
    public static function getMessagesPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_MESSAGES,
            ]);
    }

    /**
     * @return string
     */
    public static function getCarListPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_CAR_LIST,
            ]);
    }

    /**
     * @return string
     */
    public static function getFavoritePageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_FAVORITE,
            ]);
    }

    /**
     * @return string
     */
    public static function getAccountPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_ACCOUNT,
            ]);
    }

    /**
     * @return string
     */
    public static function getAccountChangePasswordPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_ACCOUNT_CHANGE_PASSWORD,
            ]);
    }

    /**
     * @return string
     */
    public static function getAccountSocialPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_ACCOUNT_SOCIAL,
            ]);
    }

    /**
     * @return string
     */
    public static function getCreateCarPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_CREATE_CAR,
            ]);
    }

    /**
     * @return string
     */
    public static function getBuyPackageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_BUY_PACKAGE,
            ]);
    }

    /**
     * @return bool
     */
    public function isLoginPage()
    {
        if (
            $this->isResetPasswordForm()
            || $this->isSetPasswordForm()
            || $this->isCreateCarPage()
        ) {
            return false;
        }

        return !is_user_logged_in();
    }

    /**
     * @return bool
     */
    public function isDashboard()
    {
        if ($this->isResetPasswordForm()) {
            return false;
        }

        return is_user_logged_in();
    }

    /**
     * @return bool
     */
    public function isResetPasswordForm()
    {
        return $this->getActionType() === self::ACTION_TYPE_RESET_PASSWORD && !is_user_logged_in();
    }

    /**
     * @return bool
     */
    public function isChangePasswordForm()
    {
        return $this->getActionType() === self::ACTION_TYPE_CHANGE_PASSWORD;
    }

    /**
     * @return bool
     */
    public function isCreateCarPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_CREATE_CAR;
    }

    /**
     * @return bool
     */
    public function isMessagesPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_MESSAGES;
    }

    /**
     * @return bool
     */
    public function isEditCarPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_EDIT_CAR && isset($_GET['id']);
    }

    /**
     * @return bool
     */
    public function isBuyPackagePage()
    {
        return $this->getActionType() === self::ACTION_TYPE_BUY_PACKAGE && is_user_logged_in();
    }

    /**
     * @return bool
     */
    public function isCarListPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_CAR_LIST;
    }

    /**
     * @return bool
     */
    public function isFavoritePage()
    {
        return $this->getActionType() === self::ACTION_TYPE_FAVORITE;
    }

    /**
     * @return string
     */
    public function getUserDisplayName()
    {
        $user = User::getCurrent();

        if (!$user) {
            return '';
        }

        return $user->getName();
    }

    /**
     * @return string
     */
    public function getUserUrl()
    {
        $user = User::getCurrent();

        if (!$user) {
            return '';
        }

        return $user->getUrl();
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function getUserImage($size = 'large')
    {
        $user = User::getCurrent();

        if (!$user) {
            return '';
        }

        return $user->getImageUrl($size);
    }

    /**
     * @return bool
     */
    public function isAccountPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_ACCOUNT;
    }

    /**
     * @return bool
     */
    public function isAccountChangePasswordPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_ACCOUNT_CHANGE_PASSWORD;
    }

    /**
     * @return bool
     */
    public function isAccountSocialPage()
    {
        return $this->getActionType() === self::ACTION_TYPE_ACCOUNT_SOCIAL;
    }

    /**
     * @return bool
     */
    public function isAnyAccountPage()
    {
        return $this->isAccountPage()
            || $this->isAccountChangePasswordPage()
            || $this->isAccountSocialPage();
    }

    /**
     * @return bool
     */
    public function isSetPasswordForm()
    {
        if ($this->getActionType() !== self::ACTION_TYPE_SET_PASSWORD) {
            return false;
        }

        if (!isset($_GET['selector'], $_GET['validator'])) {
            return false;
        }

        $selector = (string)$_GET['selector'];
        $validator = (string)$_GET['validator'];

        if (empty($selector) || empty($validator)) {
            return false;
        }

        if (!User::verifyResetPasswordToken($selector, $validator)) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getResetPasswordLink()
    {
        if (!vehicaApp('panel_page')) {
            return site_url();
        }

        return vehicaApp('panel_page_url') . '?' . http_build_query([
                self::ACTION_TYPE => self::ACTION_TYPE_RESET_PASSWORD
            ]);
    }

    /**
     * @return string
     */
    public function getSelector()
    {
        if (!isset($_GET['selector'])) {
            return '';
        }

        return (string)$_GET['selector'];
    }

    /**
     * @return string
     */
    public function getValidator()
    {
        if (!isset($_GET['validator'])) {
            return '';
        }

        return (string)$_GET['validator'];
    }

    /**
     * @return Car|false
     */
    public function getCar()
    {
        if (!isset($_GET['id'])) {
            return false;
        }

        $carId = (int)$_GET['id'];

        return Car::getById($carId);
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        $fields = $this->get_settings_for_display(self::FIELDS);

        if (!is_array($fields) || empty($fields)) {
            return Collection::make();
        }

        return Collection::make($fields)->map(static function ($panelField) {
            if ($panelField['key'] === 'name') {
                return [
                    'name' => vehicaApp('name'),
                    'type' => 'name'
                ];
            }

            $field = Field::getByKey($panelField['key']);
            if (!$field instanceof Field) {
                return false;
            }

            $fieldData = [
                'name' => $field->getName(),
                'id' => $field->getId(),
                'key' => $field->getKey(),
                'type' => $field->getType(),
                'required' => $field->isRequired(),
            ];

            if ($field instanceof Taxonomy && $field->allowMultiple()) {
                $fieldData['control'] = $panelField['taxonomy_multiple_control'];
            } elseif ($field instanceof Taxonomy && !$field->allowMultiple()) {
                $fieldData['control'] = $panelField['taxonomy_single_control'];
            }

            return $fieldData;
        });
    }

    /**
     * @return Collection
     */
    public function getPanelFields()
    {
        return apply_filters('vehica/panel/carForm/fields', vehicaApp('panel_fields'));
    }

    /**
     * @return Collection
     */
    public function getSingleValueFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            /* @var PanelField $panelField */
            return $panelField->isSingleValue();
        });
    }

    /**
     * @return DescriptionPanelField|false
     */
    public function getDescriptionField()
    {
        return $this->getPanelFields()->find(static function ($panelField) {
            return $panelField instanceof DescriptionPanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getEmbedFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof EmbedPanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getGalleryFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof GalleryPanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getAttachmentsFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof AttachmentsPanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getLocationFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof LocationPanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getMultiValueFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof TaxonomyPanelField && !$panelField->isSingleValue();
        });
    }

    /**
     * @return Collection
     */
    public function getPriceFields()
    {
        return $this->getPanelFields()->filter(static function ($panelField) {
            return $panelField instanceof PricePanelField;
        });
    }

    /**
     * @return array|string[]
     */
    public function get_style_depends()
    {
        return ['sweetalert2', 'dropzone'];
    }

    /**
     * @return array[]
     */
    public function getUserRoles()
    {
        return [
            [
                'name' => vehicaApp('private_role_string'),
                'key' => vehicaApp('private_user_role')
            ],
            [
                'name' => vehicaApp('business_role_string'),
                'key' => vehicaApp('business_user_role')
            ]
        ];
    }

    /**
     * @param User $user
     *
     * @return array|false
     */
    public function getUserImageData(User $user)
    {
        return [
            'id' => $user->getImageId(),
            'url' => $user->getImageUrl()
        ];
    }

    /**
     * @return array
     */
    public function getUserImageDropZoneConfig()
    {
        return [
            'url' => admin_url('admin-post.php?action=vehica_save_account_image'),
            'thumbnailWidth' => 200,
            'addRemoveLinks' => false,
            'dictDefaultMessage' => '<i class="far fa-images"></i> ' . vehicaApp('add_images_string'),
            'parallelUploads' => 1,
            'acceptedFiles' => 'image/*',
            'maxFiles' => 1,
        ];
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        if ($this->isAnyAccountPage()) {
            return vehicaApp('account_string');
        }

        if ($this->isCarListPage()) {
            return vehicaApp('vehicles_string');
        }

        if ($this->isCreateCarPage()) {
            return vehicaApp('submit_vehicle_string');
        }

        if ($this->isEditCarPage()) {
            return vehicaApp('edit_vehicle_string');
        }

        if ($this->isFavoritePage()) {
            return vehicaApp('favorite_string');
        }

        if ($this->isMessagesPage()) {
            return vehicaApp('messages_string');
        }

        return vehicaApp('account_string');
    }

    /**
     * @return Car|false
     */
    public function getEditCar()
    {
        $id = (int)$_GET['id'];

        if (empty($id)) {
            return false;
        }

        $car = Car::getById($id);

        if (!$car instanceof Car) {
            return false;
        }

        /** @noinspection NotOptimalIfConditionsInspection */
        if ($car->getUserId() !== get_current_user_id() && !current_user_can('manage_options')) {
            return false;
        }

        return $car;
    }

    /**
     * @return string
     */
    public function getCreateCarRedirectUrl()
    {
        if (!is_user_logged_in()) {
            return vehicaApp('settings_config')->getLoginPageUrl();
        }

        return apply_filters('vehica/panel/redirectAfterListingCreated', self::getCarListPageUrl());
    }

    /**
     * @return bool
     */
    public function showThankYouModal()
    {
        if (!is_user_logged_in()) {
            return true;
        }

        if (
            vehicaApp('woocommerce_mode')
            && vehicaApp('settings_config')->isPaymentEnabled()
        ) {
            return false;
        }

        return true;
    }

    /**
     * @return NamePanelField|false
     */
    public function getNameField()
    {
        return vehicaApp('panel_fields')->find(static function ($field) {
            return $field instanceof NamePanelField;
        });
    }

    /**
     * @return Collection
     */
    public function getFavoriteCars()
    {
        $user = User::getCurrent();

        if (!$user) {
            return Collection::make();
        }

        return Collection::make($user->getFavoriteIds())->map(static function ($carId) {
            return Car::getById($carId);
        })->filter(static function ($car) {
            return $car instanceof Car && $car->isPublished();
        });
    }

    /**
     * @return bool
     */
    public function showSelectPackages()
    {
        if (!is_user_logged_in()) {
            return false;
        }

        if (!vehicaApp('settings_config')->isPaymentEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function requireSelectPackage()
    {
        if (!is_user_logged_in()) {
            return false;
        }

        if (!vehicaApp('settings_config')->isPaymentEnabled()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function showSubmitCarButton()
    {
        if (!vehicaApp('current_user')) {
            return false;
        }

        return vehicaApp('current_user')->canCreateCars();
    }

    /**
     * @return bool
     */
    public function showCarListButton()
    {
        if (!vehicaApp('current_user')) {
            return false;
        }

        return vehicaApp('current_user')->canCreateCars() && (vehicaApp('current_user')->hasCars() || current_user_can('manage_options'));
    }

    /**
     * @return bool
     */
    public function showPhoneNumberField()
    {
        $phoneSetting = vehicaApp('settings_config')->getPanelPhoneNumber();

        return in_array($phoneSetting, [
            'optional_show',
            'optional_hide',
            'required',
        ], true);
    }

    /**
     * @return bool
     */
    public function showWhatsAppCheckbox()
    {
        return $this->showPhoneNumberField() && vehicaApp('settings_config')->isWhatsAppEnabled();
    }

    /**
     * @return bool
     */
    public function isPhoneNumberFieldRequired()
    {
        return vehicaApp('settings_config')->getPanelPhoneNumber() === 'required';
    }

    /**
     * @return bool
     */
    public function showFeaturedCheckbox()
    {
        return is_user_logged_in() && current_user_can('manage_options');
    }

}