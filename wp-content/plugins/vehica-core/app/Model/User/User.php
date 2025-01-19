<?php /** @noinspection ContractViolationInspection */

/** @noinspection TypoSafeNamingInspection */

namespace Vehica\Model\User;

use Cocur\Slugify\Slugify;
use DateInterval;
use DateTime;
use Exception;
use Vehica\Chat\Conversation;
use Vehica\Core\Collection;
use Vehica\Core\Model\Model;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use Vehica\Panel\Package;
use Vehica\Panel\PaymentPackage;
use Vehica\Widgets\General\LoginGeneralWidget;
use WP_Comment;
use WP_Query;
use WP_User;
use WP_User_Query;

/**
 * Class User
 *
 * @package Vehica\Model\User
 */
class User extends Model
{
    const FACEBOOK_PROFILE = 'vehica_facebook_profile';
    const LINKEDIN_PROFILE = 'vehica_linkedin_profile';
    const INSTAGRAM_PROFILE = 'vehica_instagram_profile';
    const TWITTER_PROFILE = 'vehica_twitter_profile';
    const TIKTOK_PROFILE = 'vehica_tiktok_profile';
    const TELEGRAM_PROFILE = 'vehica_telegram_profile';
    const JOB_TITLE = 'vehica_job_title';
    const IMAGE = 'vehica_image';
    const PHONE = 'vehica_phone';
    const DISPLAY_ADDRESS = 'vehica_display_address';
    const ADDRESS = 'vehica_address';
    const LOCATION = 'vehica_location';
    const LOCATION_LAT = 'vehica_location_lat';
    const LOCATION_LNG = 'vehica_location_lng';
    const LOCATION_SET = 1;
    const LOCATION_NOT_SET = 0;
    const RESET_PASSWORD_TOKEN = 'vehica_reset_password_token';
    const RESET_PASSWORD_EXPIRES = 'vehica_reset_password_expires';
    const RESET_PASSWORD_SELECTOR = 'vehica_reset_password_selector';
    const CONFIRMED = 'vehica_confirmed';
    const CONFIRMATION_TOKEN = 'vehica_confirmation_token';
    const CONFIRMATION_SELECTOR = 'vehica_confirmation_selector';
    const CONFIRMATION_EXPIRES = 'vehica_confirmation_expires';
    const FAVORITE = 'vehica_favorite';
    const DISPLAY_NAME = 'vehica_display_name';
    const FRONTEND_USER_ROLE = 'vehica_frontend_user_role';
    const PACKAGES = 'vehica_packages';
    const DESCRIPTION = 'vehica_description';
    const CAR_IN_PROGRESS = 'vehica_car_in_progress';
    const HIDE_PHONE = 'vehica_hide_phone';
    const REGISTER_SOURCE = 'vehica_register_source';
    const REGISTER_SOURCE_REGULAR = 'regular';
    const REGISTER_SOURCE_SOCIAL = 'social';
    const SOCIAL_IMAGE = 'vehica_social_image';
    const WHATS_APP = 'vehica_whats_app';

    /**
     * @var WP_User
     */
    public $model;

    /**
     * @var Collection
     */
    private $packages;

    /**
     * @param int $id
     *
     * @return static|false
     */
    public static function getById($id)
    {
        return vehicaApp('user_' . $id);
    }

    /**
     * @param WP_User $user
     *
     * @return static
     */
    public static function getByUser(WP_User $user)
    {
        return new static($user);
    }

    /**
     * @param string $email
     *
     * @return static|false
     */
    public static function getUserByEmail($email)
    {
        $user = get_user_by('email', $email);

        if (!$user instanceof WP_User) {
            return false;
        }

        return new static($user);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->model->ID;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return bool|int
     */
    public function setMeta($key, $value)
    {
        return update_user_meta($this->getId(), $key, $value);
    }

    /**
     * @param string $key
     * @param bool $isSingle
     *
     * @return mixed
     */
    public function getMeta($key, $isSingle = true)
    {
        return get_user_meta($this->getId(), $key, $isSingle);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->model->display_name;
    }

    /**
     * @param string $displayName
     *
     * @return bool
     */
    public function setDisplayName($displayName)
    {
        $id = wp_update_user([
            'ID' => $this->getId(),
            'display_name' => $displayName
        ]);

        return !is_wp_error($id);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return Slugify::create()->slugify($this->getName());
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return get_author_posts_url($this->getId());
    }

    /**
     * @return string
     */
    public static function getApiEndpoint()
    {
        return get_rest_url() . 'wp/v2/users';
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->model->user_email;
    }

    /**
     * @return string
     */
    public function getEditNonce()
    {
        return vehicaApp('prefix') . 'nonce_user_' . $this->getId();
    }

    /**
     * @return Collection
     */
    public function getFields()
    {
        return Collection::make();
    }

    /**
     * @param string $facebookProfile
     */
    public function setFacebookProfile($facebookProfile)
    {
        $this->setMeta(self::FACEBOOK_PROFILE, $facebookProfile);
    }

    /**
     * @return bool
     */
    public function hasFacebookProfile()
    {
        $facebookProfile = trim($this->getMeta(self::FACEBOOK_PROFILE));

        return !empty($facebookProfile);
    }

    /**
     * @return string
     */
    public function getFacebookProfile()
    {
        return trim($this->getMeta(self::FACEBOOK_PROFILE));
    }

    public function setLinkedinProfile($linkedinProfile)
    {
        $this->setMeta(self::LINKEDIN_PROFILE, $linkedinProfile);
    }

    /**
     * @return bool
     */
    public function hasLinkedinProfile()
    {
        $linkedinProfile = trim($this->getMeta(self::LINKEDIN_PROFILE));

        return !empty($linkedinProfile);
    }

    /**
     * @return string
     */
    public function getLinkedinProfile()
    {
        return trim($this->getMeta(self::LINKEDIN_PROFILE));
    }

    public function setInstagramProfile($instagramProfile)
    {
        $this->setMeta(self::INSTAGRAM_PROFILE, $instagramProfile);
    }

    /**
     * @return bool
     */
    public function hasInstagramProfile()
    {
        $instagramProfile = trim($this->getMeta(self::INSTAGRAM_PROFILE));

        return !empty($instagramProfile);
    }

    /**
     * @return string
     */
    public function getInstagramProfile()
    {
        return trim($this->getMeta(self::INSTAGRAM_PROFILE));
    }

    /**
     * @param string $twitterProfile
     */
    public function setTwitterProfile($twitterProfile)
    {
        $this->setMeta(self::TWITTER_PROFILE, $twitterProfile);
    }

    /**
     * @return bool
     */
    public function hasTwitterProfile()
    {
        $twitterProfile = trim($this->getMeta(self::TWITTER_PROFILE));

        return !empty($twitterProfile);
    }

    /**
     * @return string
     */
    public function getTwitterProfile()
    {
        return trim($this->getMeta(self::TWITTER_PROFILE));
    }

    /**
     * @param string $tiktokProfile
     */
    public function setTiktokProfile($tiktokProfile)
    {
        $this->setMeta(self::TIKTOK_PROFILE, $tiktokProfile);
    }

    /**
     * @return bool
     */
    public function hasTiktokProfile()
    {
        $tiktokProfile = trim($this->getMeta(self::TIKTOK_PROFILE));

        return !empty($tiktokProfile);
    }

    /**
     * @return string
     */
    public function getTiktokProfile()
    {
        return trim($this->getMeta(self::TIKTOK_PROFILE));
    }

    /**
     * @param string $telegramProfile
     */
    public function setTelegramProfile($telegramProfile)
    {
        $this->setMeta(self::TELEGRAM_PROFILE, $telegramProfile);
    }

    /**
     * @return bool
     */
    public function hasTelegramProfile()
    {
        $telegramProfile = trim($this->getMeta(self::TELEGRAM_PROFILE));

        return !empty($telegramProfile);
    }

    /**
     * @return string
     */
    public function getTelegramProfile()
    {
        return trim($this->getMeta(self::TELEGRAM_PROFILE));
    }

    /**
     * @return bool
     */
    public function hasSocialProfiles()
    {
        return $this->hasFacebookProfile()
            || $this->hasLinkedinProfile()
            || $this->hasInstagramProfile()
            || $this->hasTwitterProfile()
            || $this->hasTiktokProfile()
            || $this->hasTelegramProfile();
    }

    /**
     * @param array $data
     */
    public function updateAccountDetails($data)
    {
        if (vehicaApp('settings_config')->getPanelPhoneNumber() !== 'disable') {
            $phone = isset($data[self::PHONE]) ? $data[self::PHONE] : '';
            $this->setPhone($phone);
        }

        $displayAddress = isset($data[self::DISPLAY_ADDRESS]) ? $data[self::DISPLAY_ADDRESS] : '';
        $this->setDisplayAddress($displayAddress);

        $displayName = isset($data[self::DISPLAY_NAME]) ? $data[self::DISPLAY_NAME] : '';
        $this->setDisplayName($displayName);

        if (vehicaApp('show_user_roles')) {
            $userRole = isset($data[self::FRONTEND_USER_ROLE]) ? $data[self::FRONTEND_USER_ROLE] : vehicaApp('private_user_role');
        } else {
            $userRole = vehicaApp('initial_user_role');
        }

        $this->setFrontendUserRole($userRole);

        $description = isset($data[self::DESCRIPTION]) ? $data[self::DESCRIPTION] : '';
        $this->setDescription($description);

        $address = isset($data[self::ADDRESS]) ? $data[self::ADDRESS] : '';
        $this->setAddress($address);

        if (vehicaApp('settings_config')->isWhatsAppEnabled()) {
            $this->setWhatsApp($data[self::WHATS_APP]);
        }

        if (!empty($data[self::LOCATION]) && is_array($data[self::LOCATION]) && isset($data[self::LOCATION]['lat'], $data[self::LOCATION]['lng'])) {
            $this->setLocationLat($data[self::LOCATION]['lat']);
            $this->setLocationLng($data[self::LOCATION]['lng']);
        } else {
            $this->setLocationLat(0);
            $this->setLocationLng(0);
        }

        if (vehicaApp('settings_config')->isPanelHidePhoneAllowed()) {
            $hidePhone = isset($data['vehica_hide_phone']) ? !empty((int)$data['vehica_hide_phone']) : 0;
            $this->setHidePhone($hidePhone);
        }
    }

    /**
     * @param string $userRole
     */
    private function setFrontendUserRole($userRole)
    {
        if ($this->hasUserRole(vehicaApp('private_user_role'))) {
            $this->removeUserRole(vehicaApp('private_user_role'));
        }

        if ($this->hasUserRole(vehicaApp('business_user_role'))) {
            $this->removeUserRole(vehicaApp('business_user_role'));
        }

        $this->addUserRole($userRole);
    }

    /**
     * @param string $userRole
     */
    public function removeUserRole($userRole)
    {
        $this->model->remove_role($userRole);
    }

    /**
     * @param string $userRole
     */
    public function addUserRole($userRole)
    {
        $this->model->add_role($userRole);
    }

    /**
     * @param string $userRole
     *
     * @return bool
     */
    public function hasUserRole($userRole)
    {
        return in_array($userRole, $this->model->roles, true);
    }

    /**
     * @param array $data
     */
    public function updateStaticFields($data)
    {
        $this->updateSocialProfiles($data);

        if (isset($data[self::IMAGE])) {
            $this->setImage($data[self::IMAGE]);
        }

        if (isset($data[self::PHONE])) {
            $this->setPhone($data[self::PHONE]);
        }

        if (isset($data[self::JOB_TITLE])) {
            $this->setJobTitle($data[self::JOB_TITLE]);
        }

        if (isset($data[self::ADDRESS])) {
            $this->setAddress($data[self::ADDRESS]);
        }

        if (isset($data[self::DISPLAY_ADDRESS])) {
            $this->setDisplayAddress($data[self::DISPLAY_ADDRESS]);
        }

        if (vehicaApp('settings_config')->isUserConfirmationEnabled()) {
            if (!empty($data[self::CONFIRMED])) {
                $this->setConfirmed();
            } else {
                $this->setNotConfirmed();
            }
        }

        if (isset($data[self::LOCATION_LAT], $data[self::LOCATION_LNG])) {
            $this->setLocation(self::LOCATION_SET);
            $this->setLocationLat($data[self::LOCATION_LAT]);
            $this->setLocationLng($data[self::LOCATION_LNG]);
        } else {
            $this->setLocation(self::LOCATION_NOT_SET);
        }
    }

    /**
     * @param array $data
     */
    public function updateSocialProfiles($data)
    {
        if (isset($data[self::FACEBOOK_PROFILE])) {
            $this->setFacebookProfile($data[self::FACEBOOK_PROFILE]);
        }

        if (isset($data[self::LINKEDIN_PROFILE])) {
            $this->setLinkedinProfile($data[self::LINKEDIN_PROFILE]);
        }

        if (isset($data[self::INSTAGRAM_PROFILE])) {
            $this->setInstagramProfile($data[self::INSTAGRAM_PROFILE]);
        }

        if (isset($data[self::TWITTER_PROFILE])) {
            $this->setTwitterProfile($data[self::TWITTER_PROFILE]);
        }

        if (isset($data[self::TIKTOK_PROFILE])) {
            $this->setTiktokProfile($data[self::TIKTOK_PROFILE]);
        }

        if (isset($data[self::TELEGRAM_PROFILE])) {
            $this->setTelegramProfile($data[self::TELEGRAM_PROFILE]);
        }
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        $image = (int)$this->getMeta(self::IMAGE);

        return !empty($image);
    }

    /**
     * @return int
     */
    public function getImageId()
    {
        return (int)$this->getMeta(self::IMAGE);
    }

    /**
     * @param string $size
     *
     * @return bool
     */
    public function hasImageUrl($size = 'large')
    {
        return !empty($this->getImageUrl($size));
    }

    /**
     * @param string $size
     *
     * @return string
     */
    public function getImageUrl($size = 'large')
    {
        if ($this->hasSocialImage()) {
            return $this->getSocialImage();
        }

        $url = wp_get_attachment_image_url($this->getImageId(), $size);

        if (!$url) {
            return wp_get_attachment_image_url($this->getImageId(), 'full');
        }

        return $url;
    }

    /**
     * @param int $imageId
     */
    public function setImage($imageId)
    {
        $imageId = (int)$imageId;
        $this->setMeta(self::IMAGE, $imageId);
    }

    /**
     * @param string $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->setMeta(self::JOB_TITLE, $jobTitle);
    }

    /**
     * @return string
     */
    public function getJobTitle()
    {
        return (string)$this->getMeta(self::JOB_TITLE);
    }

    /**
     * @return bool
     */
    public function hasJobTitle()
    {
        return $this->getJobTitle() !== '';
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->setMeta(self::PHONE, $phone);
    }

    /**
     * @return bool
     */
    public function hasPhone()
    {
        $phone = $this->getPhone();

        return !empty($phone);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->getMeta(self::PHONE);
    }

    /**
     * @return string
     */
    public function getPhoneUrl()
    {
        return apply_filters('vehica/phone', $this->getPhone());
    }

    public function getWhatsAppPhoneUrl()
    {
        return str_replace('+', '', $this->getPhoneUrl());
    }

    /**
     * @param string $displayAddress
     */
    public function setDisplayAddress($displayAddress)
    {
        $this->setMeta(self::DISPLAY_ADDRESS, $displayAddress);
    }

    /**
     * @return string
     */
    public function getDisplayAddress()
    {
        return (string)$this->getMeta(self::DISPLAY_ADDRESS);
    }

    /**
     * @return bool
     */
    public function hasDisplayAddress()
    {
        return $this->getDisplayAddress() !== '';
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->setMeta(self::ADDRESS, $address);
    }

    /**
     * @return bool
     */
    public function hasAddress()
    {
        $address = $this->getAddress();

        return !empty($address);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->getMeta(self::ADDRESS);
    }

    /**
     * @param int $location
     */
    public function setLocation($location)
    {
        $location = (int)$location;
        $this->setMeta(self::LOCATION, $location);
    }

    /**
     * @return bool
     */
    public function hasLocation()
    {
        $location = $this->getMeta(self::LOCATION);

        return !empty($location);
    }

    /**
     * @param float $lat
     */
    public function setLocationLat($lat)
    {
        $lat = (float)$lat;
        $this->setMeta(self::LOCATION_LAT, $lat);
    }

    /**
     * @param float $lng
     */
    public function setLocationLng($lng)
    {
        $lng = (float)$lng;
        $this->setMeta(self::LOCATION_LNG, $lng);
    }

    /**
     * @return false|float[]
     */
    public function getLocation()
    {
        $location = [
            'lat' => $this->getLocationLat(),
            'lng' => $this->getLocationLng()
        ];

        if (empty($location['lat']) && empty($location['lng'])) {
            return false;
        }

        return $location;
    }

    /**
     * @return float
     */
    public function getLocationLat()
    {
        return (float)$this->getMeta(self::LOCATION_LAT);
    }

    /**
     * @return float
     */
    public function getLocationLng()
    {
        return (float)$this->getMeta(self::LOCATION_LNG);
    }

    /**
     * @return bool
     */
    public function hasDescription()
    {
        $description = $this->getDescription();

        return !empty($description);
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return get_the_author_meta('description', $this->getId());
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        update_user_meta($this->getId(), 'description', $description);
    }

    /**
     * @return string
     */
    public function getWebsiteUrl()
    {
        if (empty($this->model->user_url)) {
            return '#';
        }

        return $this->model->user_url;
    }

    /**
     * @return static|false
     */
    public static function first()
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach (get_users() as $user) {
            return new static($user);
        }

        return false;
    }

    /**
     * @param string $selector
     * @param string $validator
     *
     * @return int|false
     */
    public static function verifyConfirmation($selector, $validator)
    {
        if (empty($selector) || empty($validator)) {
            return false;
        }

        $user = self::getUserByMeta(self::CONFIRMATION_SELECTOR, $selector);

        if (!$user) {
            return false;
        }

        $expires = $user->getMeta(self::CONFIRMATION_EXPIRES);
        try {
            $now = new DateTime('now');
        } catch (Exception $e) {
            return false;
        }

        $now->format('Y-m-d\TH:i:s');
        if ($now->format('Y-m-d\TH:i:s') > $expires) {
            $user->clearConfirmationTokenData();

            return false;
        }

        $calc = hash('sha256', $validator);
        $token = $user->getMeta(self::CONFIRMATION_TOKEN);

        if (empty($calc) || empty($token)) {
            return false;
        }

        if (!hash_equals($calc, $token)) {
            return false;
        }

        $user->clearConfirmationTokenData();

        return $user->getId();
    }

    public function clearConfirmationTokenData()
    {
        $this->setMeta(self::CONFIRMATION_TOKEN, '0');
        $this->setMeta(self::CONFIRMATION_SELECTOR, '0');
        $this->setMeta(self::CONFIRMATION_EXPIRES, '0');
    }

    /**
     * @return bool|string
     */
    public function getConfirmationUrl()
    {
        try {
            $selector = bin2hex(random_bytes(8));
            $token = random_bytes(32);
            $expires = new DateTime('NOW');
            $expires->add(new DateInterval('P1D'));
        } catch (Exception $e) {
            return false;
        }

        $this->setMeta(self::CONFIRMATION_SELECTOR, $selector);
        $this->setMeta(self::CONFIRMATION_TOKEN, hash('sha256', bin2hex($token)));
        $this->setMeta(self::CONFIRMATION_EXPIRES, $expires->format('Y-m-d\TH:i:s'));

        return admin_url('admin-post.php?') . http_build_query([
                'action' => 'vehica_user_confirmation',
                'selector' => $selector,
                'validator' => bin2hex($token)
            ]);
    }

    /**
     * @return bool
     */
    public function isConfirmed()
    {
        if ($this->hasUserRole('administrator')) {
            return true;
        }

        $confirmation = $this->getMeta(self::CONFIRMED);

        return !empty($confirmation);
    }

    public function setConfirmed()
    {
        $this->setMeta(self::CONFIRMED, '1');
    }

    public function setNotConfirmed()
    {
        $this->setMeta(self::CONFIRMED, '0');
    }

    /**
     * @return string
     */
    public function getResetPasswordLink()
    {
        try {
            $selector = bin2hex(random_bytes(8));
            $token = bin2hex(random_bytes(32));
            $expires = new DateTime('NOW');
            $expires->add(new DateInterval('PT01H'));
        } catch (Exception $e) {
            return false;
        }

        $this->setMeta(self::RESET_PASSWORD_SELECTOR, $selector);
        $this->setMeta(self::RESET_PASSWORD_TOKEN, hash('sha256', $token));
        $this->setMeta(self::RESET_PASSWORD_EXPIRES, $expires->format('Y-m-d\TH:i:s'));

        return vehicaApp('settings_config')->getLoginPageUrl() . '?' . http_build_query([
                LoginGeneralWidget::ACTION_TYPE => LoginGeneralWidget::ACTION_TYPE_SET_PASSWORD,
                'selector' => $selector,
                'validator' => $token
            ]);
    }

    /**
     * @param string $selector
     * @param string $validator
     *
     * @return bool|int
     */
    public static function verifyResetPasswordToken($selector, $validator)
    {
        $user = self::getUserByMeta(self::RESET_PASSWORD_SELECTOR, $selector);
        if (!$user) {
            return false;
        }

        $expires = $user->getMeta(self::RESET_PASSWORD_EXPIRES);
        try {
            $now = new DateTime('now');
        } catch (Exception $e) {
            return false;
        }

        $now->format('Y-m-d\TH:i:s');
        if ($now->format('Y-m-d\TH:i:s') > $expires) {
            $user->clearResetPasswordTokenData();

            return false;
        }

        $calc = hash('sha256', $validator);
        $token = $user->getMeta(self::RESET_PASSWORD_TOKEN);

        if (empty($calc) || empty($token)) {
            return false;
        }

        if (!hash_equals($calc, $token)) {
            return false;
        }

        return $user->getId();
    }

    public function clearResetPasswordTokenData()
    {
        $this->setMeta(self::RESET_PASSWORD_TOKEN, '0');
        $this->setMeta(self::RESET_PASSWORD_EXPIRES, '0');
        $this->setMeta(self::RESET_PASSWORD_SELECTOR, '0');
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function setPassword($password)
    {
        if (empty($password)) {
            return false;
        }

        wp_set_password($password, $this->getId());

        return true;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function checkPassword($password)
    {
        return wp_check_password($password, $this->model->user_pass, $this->getId()) !== false;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return Collection
     */
    public static function getUsersByMeta($key, $value)
    {
        $query = new WP_User_Query([
            'meta_key' => $key,
            'meta_value' => $value
        ]);

        return Collection::make($query->get_results())->map(static function ($user) {
            return new static($user);
        });
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return static|false
     */
    public static function getUserByMeta($key, $value)
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach (self::getUsersByMeta($key, $value) as $user) {
            return $user;
        }

        return false;
    }

    /**
     * @return static|false
     */
    public static function getCurrent()
    {
        return vehicaApp('current_user');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return in_array('administrator', $this->model->roles, true);
    }

    /**
     * @param Car|int $carId
     */
    public function addFavorite($carId)
    {
        if ($carId instanceof Car) {
            $carId = $carId->getId();
        } else {
            $carId = (int)$carId;
        }
        $car = Car::getById($carId);
        if (!$car instanceof Car) {
            return;
        }

        $carIds = $this->getFavoriteIds();

        if (($key = array_search($carId, $carIds, true)) !== false) {
            unset($carIds[$key]);
            $car->decreaseFavoriteNumber();
        } else {
            $carIds[] = $carId;
            $car->increaseFavoriteNumber();
        }

        $this->setMeta(self::FAVORITE, $carIds);
    }

    /**
     * @return int
     */
    public function getFavoriteNumber()
    {
        $favoriteIds = $this->getFavoriteIds();

        if (empty($favoriteIds)) {
            return 0;
        }

        return Car::getAll(PostStatus::PUBLISH, [
            'post__in' => $this->getFavoriteIds(),
            'fields' => 'ids'
        ])->count();
    }

    /**
     * @return array
     */
    public function getFavoriteIds()
    {
        $ids = $this->getMeta(self::FAVORITE);

        if (!is_array($ids) || empty($ids)) {
            return [];
        }

        return Collection::make($ids)->map(static function ($id) {
            return (int)$id;
        })->all();
    }

    /**
     * @param Car|int $car
     *
     * @return bool
     */
    public function isFavorite($car)
    {
        if ($car instanceof Car) {
            $car = $car->getId();
        }

        return in_array($car, $this->getFavoriteIds(), true);
    }

    /**
     * @return bool
     */
    public function isPrivateRole()
    {
        return in_array(vehicaApp('private_user_role'), $this->model->roles, true);
    }

    /**
     * @return bool
     */
    public function isBusinessRole()
    {
        return in_array(vehicaApp('business_user_role'), $this->model->roles, true);
    }

    /**
     * @return string
     */
    public function getUserRolesString()
    {
        return implode(', ', $this->model->roles);
    }

    /**
     * @return string
     */
    public function getFrontendUserRole()
    {
        if (in_array(vehicaApp('business_user_role'), $this->model->roles, true)) {
            return vehicaApp('business_user_role');
        }

        return vehicaApp('private_user_role');
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        if ($this->hasJobTitle()) {
            return $this->getJobTitle();
        }

        if ($this->isPrivateRole()) {
            return vehicaApp('private_role_string');
        }

        if ($this->isBusinessRole()) {
            return vehicaApp('business_role_string');
        }

        return $this->getUserRolesString();
    }

    public function login()
    {
        wp_set_auth_cookie($this->getId());

        do_action('wp_login', $this->model->user_login, $this->model);

        do_action('vehica/userLogged');
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return wp_delete_user($this->getId());
    }

    /**
     * @param int $commentId
     * @param string $size
     *
     * @return string|false
     */
    public static function getImageByCommentId($commentId, $size = 'vehica_100_100')
    {
        $comment = get_comment($commentId);
        if (!$comment instanceof WP_Comment) {
            return false;
        }

        $user = self::getById($comment->user_id);
        if (!$user) {
            return false;
        }

        return $user->getImageUrl($size);
    }

    /**
     * @return bool
     */
    public function hasCars()
    {
        return $this->getCarsNumber() > 0;
    }

    /**
     * @return int
     */
    public function getCarsNumber()
    {
        $query = new WP_Query([
            'author' => $this->getId(),
            'post_status' => ['publish', 'pending', 'draft'],
            'post_type' => Car::POST_TYPE
        ]);

        return $query->found_posts;
    }

    /**
     * Customer constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        parent::__construct($model);

        $this->packages = $this->fetchPackages();
    }

    /**
     * @return Collection
     */
    private function fetchPackages()
    {
        $packages = $this->getMeta(self::PACKAGES);

        if (!$packages instanceof Collection) {
            return Collection::make();
        }

        return $packages->filter(static function ($package) {
            /* @var Package $package */
            return !$package->isEmpty();
        })->values();
    }

    /**
     * @return Collection
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * @param PaymentPackage $paymentPackage
     * @return Package
     */
    public function addPaymentPackage(PaymentPackage $paymentPackage)
    {
        $package = new Package(
            $paymentPackage->getNumber(),
            $paymentPackage->getExpire(),
            $paymentPackage->getFeaturedExpire()
        );

        $this->addPackage($package);

        return $package;
    }

    /**
     * @param Package $package
     */
    public function addPackage(Package $package)
    {
        $samePackage = $this->packages->find(static function ($p) use ($package) {
            /* @var Package $p */
            return $package->getKey() === $p->getKey();
        });

        if ($samePackage instanceof Package) {
            $samePackage->addPackage($package);
        } else {
            $this->packages[] = $package;
        }

        $this->updatePackages();
    }

    private function updatePackages()
    {
        $this->setMeta(self::PACKAGES, $this->packages);
    }

    /**
     * @param string $packageKey
     *
     * @return Package|false
     */
    public function getPackage($packageKey)
    {
        if ($packageKey === 'free') {
            return vehicaApp('settings_config')->isFreeListingEnabled() ? Package::getFree() : false;
        }

        return $this->getPackages()->find(static function ($package) use ($packageKey) {
            /* @var Package $package */
            return $package->getKey() === $packageKey;
        });
    }

    /**
     * @param string $packageKey
     *
     * @return bool
     */
    public function decreasePackage($packageKey)
    {
        $package = $this->getPackage($packageKey);

        if (!$package) {
            return false;
        }

        $package->decreaseNumber();

        $this->updatePackages();

        return false;
    }

    /**
     * @return bool
     */
    public function hasPackages()
    {
        return $this->packages->isNotEmpty();
    }

    /**
     * @param array $packages
     */
    public function setPackages($packages)
    {
        $this->packages = Collection::make();

        Collection::make($packages)->map(static function ($package) {
            return Package::create($package);
        })->filter(static function ($package) {
            /* @var Package $package */
            return !$package->isEmpty();
        })->each(function ($package) {
            $this->addPackage($package);
        });

        $this->updatePackages();
    }

    /**
     * @return bool
     */
    public function isTempOwnerKeySet()
    {
        return !empty($_COOKIE['vehica_temp_owner_key']);
    }

    /**
     * @return string
     */
    public static function getTempOwnerKey()
    {
        if (!empty($_COOKIE['vehica_temp_owner_key'])) {
            return (string)$_COOKIE['vehica_temp_owner_key'];
        }

        try {
            $tempOwnerKey = bin2hex(random_bytes(20));
        } catch (Exception $e) {
            $tempOwnerKey = md5(uniqid(mt_rand(), true));
        }

        /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
        setcookie('vehica_temp_owner_key', $tempOwnerKey, time() + 86400, '/', ini_get('session.cookie_domain'),
            ini_get('session.cookie_secure'), ini_get('session.cookie_httponly'));

        return $tempOwnerKey;
    }

    public static function getLogoutUrl()
    {
        return admin_url('admin-post.php?action=vehica_instant_logout');
    }

    /**
     * @param int $carId
     */
    public function setCarInProgress($carId)
    {
        $this->setMeta(self::CAR_IN_PROGRESS, $carId);
    }

    /**
     * @return bool
     */
    public function hasCarInProgress()
    {
        return $this->getCarInProgress() !== false;
    }

    /**
     * @return Car|false
     */
    public function getCarInProgress()
    {
        $carId = (int)$this->getMeta(self::CAR_IN_PROGRESS);
        return Car::getById($carId);
    }

    public function removeCarInProgress()
    {
        $this->setMeta(self::CAR_IN_PROGRESS, '0');
    }

    /**
     * @return bool
     */
    public function canCreateCars()
    {
        return apply_filters('vehica/user/canCreateCars', true, $this);
    }

    /**
     * @return bool
     */
    public function hidePhone()
    {
        if (!vehicaApp('settings_config')->isPanelHidePhoneAllowed()) {
            return false;
        }

        return !empty((int)$this->getMeta(self::HIDE_PHONE));
    }

    /**
     * @param int $hide
     */
    public function setHidePhone($hide)
    {
        $this->setMeta(self::HIDE_PHONE, (int)$hide);
    }

    /**
     * @return string
     */
    public function getUserAddress()
    {
        if (vehicaApp('settings_config')->isUserAddressTextInput()) {
            return $this->getDisplayAddress();
        }

        return $this->getAddress();
    }

    /**
     * @return Collection
     */
    public function getConversations()
    {
        return Conversation::getUserIds($this->getId())->map(static function ($userId) {
            return Conversation::make($userId);
        })->values();
    }

    /**
     * @return int
     */
    public function getNotSeenConversationNumber()
    {
        return $this->getConversations()->filter(static function ($conversation) {
            /* @var Conversation $conversation */
            return !$conversation->seen();
        })->count();
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        wp_update_user([
            'ID' => $this->getId(),
            'role' => $role,
        ]);
    }

    /**
     * @param $source
     */
    public function setRegisterSource($source)
    {
        $this->setMeta(self::REGISTER_SOURCE, $source);
    }

    /**
     * @return string
     */
    public function getRegisterSource()
    {
        $source = $this->getMeta(self::REGISTER_SOURCE);

        if (empty($source)) {
            return self::REGISTER_SOURCE_REGULAR;
        }

        return $source;
    }

    /**
     * @return bool
     */
    public function isRegularRegisterSource()
    {
        return !$this->isSocialRegisterSource();
    }

    /**
     * @return bool
     */
    public function isSocialRegisterSource()
    {
        return $this->getRegisterSource() === self::REGISTER_SOURCE_SOCIAL;
    }

    /**
     * @param string $url
     */
    public function setSocialImage($url)
    {
        $this->setMeta(self::SOCIAL_IMAGE, $url);
    }

    /**
     * @return string
     */
    public function getSocialImage()
    {
        return (string)$this->getMeta(self::SOCIAL_IMAGE);
    }

    /**
     * @return bool
     */
    public function hasSocialImage()
    {
        return $this->getSocialImage() !== '';
    }

    /**
     * @param int $active
     */
    public function setWhatsApp($active)
    {
        $this->setMeta(self::WHATS_APP, (int)$active);
    }

    /**
     * @return bool
     */
    public function isWhatsAppActive()
    {
        return !empty((int)$this->getMeta(self::WHATS_APP));
    }

}