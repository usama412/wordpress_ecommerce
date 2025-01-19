<?php /** @noinspection ContractViolationInspection */

namespace Vehica\Model\Post;

if (!defined('ABSPATH')) {
    exit;
}

use DateTime;
use Exception;
use Vehica\Attribute\Attribute;
use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Components\CardLabel;
use Vehica\Core\Collection;
use Vehica\Core\Field\GalleryAttribute;
use Vehica\Core\Model\Field\FieldsUser;
use Vehica\Core\Notification;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Field\GalleryField;
use Vehica\Model\Post\Field\LocationField;
use Vehica\Model\Post\Field\Price\PriceField;
use Vehica\Model\Post\Field\Taxonomy\Taxonomy;
use Vehica\Model\Post\Field\Field;
use Vehica\Model\Term\Term;
use Vehica\Panel\Package;
use Vehica\Widgets\General\PanelGeneralWidget;
use WP_Error;
use WP_Query;

/**
 * Class Car
 *
 * @package Vehica\Model
 */
class Car extends Post implements FieldsUser
{
    const KEY = 'vehica_cars';
    const POST_TYPE = 'vehica_car';
    const VIEWS = 'vehica_views';
    const PHONE_CLICKS = 'vehica_phone_clicks';
    const FAVORITE_NUMBER = 'vehica_favorite_number';
    const FEATURED = 'vehica_featured';
    const EXPIRE = 'vehica_expire';
    const FEATURED_EXPIRE = 'vehica_featured_expire';
    const PENDING_PACKAGE = 'vehica_pending_package';
    const APPROVED = 'vehica_approved';

    /**
     * @return string
     */
    public function getName()
    {
        return apply_filters('vehica/car/name', parent::getName(), $this);
    }

    /**
     * @return bool|GalleryAttribute
     */
    public function getGallery()
    {
        return $this->getAttributes()->find(static function ($attribute) {
            return $attribute instanceof GalleryAttribute;
        });
    }

    /**
     * @param string $size
     *
     * @return string|false
     */
    public function getImageUrl($size = 'large')
    {
        $image = parent::getImageUrl($size);
        if ($image) {
            return $image;
        }

        $galleryField = vehicaApp('gallery_fields')->first();
        if (!$galleryField instanceof GalleryField) {
            return false;
        }

        return $galleryField->getAttribute($this)->getImageUrl($size);
    }

    /**
     * @return Collection
     */
    public function getAttributes()
    {
        $carJsonFields = vehicaApp()->has('car_json_fields') ? vehicaApp('car_json_fields') : true;

        return vehicaApp('usable_car_fields')->map(function ($field) use ($carJsonFields) {
            /* @var Field $field */
            if (is_array($carJsonFields) && !in_array($field->getId(), $carJsonFields, true)) {
                return false;
            }

            return $field->getAttribute($this);
        })->filter(static function ($attribute) {
            return $attribute !== false;
        });
    }

    /**
     * @param Attribute $attribute
     *
     * @return Collection
     */
    public function getAttributeValues(Attribute $attribute)
    {
        return $attribute->getAttributeValues($this);
    }

    /**
     * @return array
     */
    protected function getJsonData()
    {
        return [
            'description' => wpautop($this->getDescription()),
            'attributes' => $this->getAttributes()->values(),
            'user' => $this->getUser(),
            'featured' => $this->isFeatured(),
        ];
    }

    /**
     * @param Taxonomy $taxonomy
     *
     * @return Collection|Term[]
     */
    public function getTerms(Taxonomy $taxonomy)
    {
        if (!$taxonomy->isVisible()) {
            return Collection::make();
        }

        $key = $taxonomy->getKey() . '_' . $this->getId();
        if (vehicaApp()->has($key)) {
            return vehicaApp($key);
        }

        $terms = Collection::make();
        $wpTerms = wp_get_post_terms($this->getId(), $taxonomy->getKey());

        if (!is_array($wpTerms)) {
            return $terms;
        }

        foreach ($wpTerms as $term) {
            $terms[] = new Term($term);
        }

        return $terms;
    }

    /**
     * @return array
     */
    public function getAllTerms()
    {
        $taxonomies = [];

        foreach (vehicaApp('taxonomies') as $taxonomy) {
            /* @var $taxonomy Taxonomy */
            $taxonomies[$taxonomy->getId()] = $this->getTerms($taxonomy);
        }

        return $taxonomies;
    }

    /**
     * @return string
     */
    public static function getApiEndpoint()
    {
        return get_rest_url(null, 'wp/v2/cars');
    }

    /**
     * @param string $extraUrl
     * @return string
     */
    public function getFrontendEditUrl($extraUrl = '')
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_EDIT_CAR,
                'id' => $this->getId(),
            ]) . $extraUrl;
    }

    /**
     * @return string
     */
    public function getFrontendPublishUrl()
    {
        return admin_url('admin-post.php?action=vehica_publish_car&carId=' . $this->getId());
    }

    /**
     * @param array $modelData
     *
     * @return Car|false|WP_Error
     */
    public static function create($modelData = [])
    {
        $modelData['post_type'] = self::POST_TYPE;

        return parent::create($modelData);
    }

    /**
     * @return bool
     */
    public function isFeatured()
    {
        return !empty($this->getMeta(self::FEATURED));
    }

    public function setFeatured()
    {
        $this->setMeta(self::FEATURED, 1);
    }

    public function removeFeatured()
    {
        $this->setMeta(self::FEATURED, 0);
    }

    /**
     * @return bool
     */
    public function hasFeaturedExpireDate()
    {
        return !empty($this->getFeaturedExpireDate());
    }

    /**
     * @return bool
     */
    public function isFeaturedExpired()
    {
        $expireDate = $this->getFeaturedExpireDate();

        if (empty($expireDate)) {
            return false;
        }

        return date("Y-m-d H:i:s") > $expireDate;
    }

    public function clearFeaturedExpireDate()
    {
        $this->setMeta(self::FEATURED_EXPIRE, '0');
    }

    public function increaseViews()
    {
        $this->setMeta(self::VIEWS, $this->getViews() + 1);
    }

    public function setViews($views)
    {
        $this->setMeta(self::VIEWS, $views);
    }

    /**
     * @return int
     */
    public function getViews()
    {
        return (int)$this->getMeta(self::VIEWS);
    }

    /**
     * @return int
     */
    public function getPhoneClickNumber()
    {
        return (int)$this->getMeta(self::PHONE_CLICKS);
    }

    public function setPhoneClicks($number)
    {
        $this->setMeta(self::PHONE_CLICKS, $number);
    }

    public function increasePhoneClickNumber()
    {
        $this->setMeta(self::PHONE_CLICKS, $this->getPhoneClickNumber() + 1);
    }

    /**
     * @return int
     */
    public function getFavoriteNumber()
    {
        $number = (int)$this->getMeta(self::FAVORITE_NUMBER);

        if ($number < 0) {
            return 0;
        }

        return $number;
    }

    public function setFavoriteNumber($number)
    {
        $this->setMeta(self::FAVORITE_NUMBER, $number);
    }

    public function increaseFavoriteNumber()
    {
        $this->setMeta(self::FAVORITE_NUMBER, $this->getFavoriteNumber() + 1);
    }

    public function decreaseFavoriteNumber()
    {
        $this->setMeta(self::FAVORITE_NUMBER, $this->getFavoriteNumber() - 1);
    }

    public function setUserCookie()
    {
        if (isset($_COOKIE['vehica_listings']) && is_array($_COOKIE['vehica_listings'])) {
            $listingIds = $_COOKIE['vehica_listings'];
        } else {
            $listingIds = [];
        }

        $listingIds[] = $this->getId();

        /** @noinspection SecureCookiesTransferInspection */
        /** @noinspection SummerTimeUnsafeTimeManipulationInspection */
        setcookie('vehica_listings', json_encode($listingIds), time() + 86400, '/');
    }

    /**
     * @return string
     */
    public function getExpireDate()
    {
        return (string)$this->getMeta(self::EXPIRE);
    }

    /**
     * @param string $date
     */
    public function setExpireDate($date)
    {
        $this->setMeta(self::EXPIRE, $date);
    }

    /**
     * @return bool
     */
    public function hasExpireDate()
    {
        return !empty($this->getExpireDate());
    }

    private function getExpireText($date)
    {
        try {
            $nowDate = new DateTime();
            $finalDate = new DateTime($date);
        } catch (Exception $e) {
            return '';
        }

        $difference = $nowDate->diff($finalDate);

        if (!$difference) {
            return '';
        }

        if ($difference->days === 1) {
            $daysString = mb_strtolower(vehicaApp('day_string'), 'UTF-8');
        } else {
            $daysString = mb_strtolower(vehicaApp('days_string'), 'UTF-8');
        }

        if ($difference->h === 1) {
            $hoursString = mb_strtolower(vehicaApp('hour_string'), 'UTF-8');
        } else {
            $hoursString = mb_strtolower(vehicaApp('hours_string'), 'UTF-8');
        }

        if ($difference->i === 1) {
            $minutesString = mb_strtolower(vehicaApp('minute_string'), 'UTF-8');
        } else {
            $minutesString = mb_strtolower(vehicaApp('minutes_string'), 'UTF-8');
        }

        if (empty($difference->days) && !empty($difference->h)) {
            return $difference->h . ' ' . $hoursString . ', ' . $difference->i . ' ' . $minutesString;
        }

        if (empty($difference->days)) {
            return $difference->i . ' ' . $minutesString;
        }

        return $difference->days . ' ' . $daysString . ', ' . $difference->h . ' ' . $hoursString;
    }

    /**
     * @return string
     */
    public function getFeaturedExpireDateText()
    {
        return $this->getExpireText($this->getFeaturedExpireDate());
    }

    /**
     * @return string
     */
    public function getExpireDateText()
    {
        return $this->getExpireText($this->getExpireDate());
    }

    /**
     * @param string $date
     */
    public function setFeaturedExpireDate($date)
    {
        $this->setMeta(self::FEATURED_EXPIRE, $date);
    }

    /**
     * @return string
     */
    public function getFeaturedExpireDate()
    {
        return (string)$this->getMeta(self::FEATURED_EXPIRE);
    }

    /**
     * @return Collection|static[]
     */
    public static function getPublish()
    {
        return static::getAll(PostStatus::PUBLISH);
    }

    /**
     * @param string $status
     * @param array $query
     *
     * @return Collection|static[]
     */
    public static function getAll($status = PostStatus::ANY, $query = [])
    {
        return Collection::make((new WP_Query(
            [
                'post_status' => $status,
                'posts_per_page' => -1,
                'post_type' => self::POST_TYPE
            ] + $query)
        )->posts)->map(static function ($post) {
            return new static($post);
        });
    }

    /**
     * @param string $tempOwnerKey
     *
     * @return Collection|static[]
     */
    public static function getByTempOwnerKey($tempOwnerKey)
    {
        return static::getAll(PostStatus::ANY, [
            'meta_key' => 'vehica_temp_owner_key',
            'meta_value' => $tempOwnerKey
        ]);
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $expireDate = $this->getExpireDate();

        if (empty($expireDate)) {
            return false;
        }

        return date("Y-m-d H:i:s") > $expireDate;
    }

    public function clearExpireDate()
    {
        $this->setMeta(self::EXPIRE, '0');
    }

    /**
     * @param string $tempOwnerKey
     */
    public function setTempOwnerKey($tempOwnerKey)
    {
        $this->setMeta('vehica_temp_owner_key', $tempOwnerKey);
    }

    public function removeTempOwnerKey()
    {
        $this->setMeta('vehica_temp_owner_key', '0');
    }

    /**
     * @return string
     */
    public function getApproveUrl()
    {
        return admin_url('admin-post.php?action=vehica_approve_car&carId=' . $this->getId());
    }

    /**
     * @return string
     */
    public function getDeclineUrl()
    {
        return admin_url('admin-post.php?action=vehica_decline_car&carId=' . $this->getId());
    }

    /**
     * @param string $packageKey
     */
    public function setPendingPackage($packageKey)
    {
        $this->setMeta(self::PENDING_PACKAGE, $packageKey);
    }

    /**
     * @return Package|false
     */
    public function getPendingPackage()
    {
        $pendingPackageKey = $this->getMeta(self::PENDING_PACKAGE);
        if (empty($pendingPackageKey)) {
            return false;
        }

        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        return $user->getPackage($pendingPackageKey);
    }

    /**
     * @return void
     */
    public function removePendingPackage()
    {
        $this->setMeta(self::PENDING_PACKAGE, '0');
    }

    /**
     * @return bool
     */
    public function hasPendingPackage()
    {
        return $this->getPendingPackage() !== false;
    }

    public function setApproved()
    {
        $this->setMeta(self::APPROVED, 1);
    }

    public function clearApproval()
    {
        $this->setMeta(self::APPROVED, 0);
    }

    /**
     * @return bool
     */
    public function isApproved()
    {
        return !empty($this->getMeta(self::APPROVED));
    }

    /**
     * @return bool
     */
    public function isCurrentUserOwner()
    {
        return get_current_user_id() === $this->getUserId();
    }

    /**
     * @return int
     */
    public function getFeaturedImageId()
    {
        /* @var GalleryField $galleryField */
        $galleryField = vehicaApp('card_gallery_field');

        if (!$galleryField) {
            return 0;
        }

        foreach ($galleryField->getValue($this) as $imageId) {
            if (!empty($imageId)) {
                return $imageId;
            }
        }

        return 0;
    }

    public function setPending()
    {
        parent::setPending();

        do_action('vehica/notification/' . Notification::CAR_PENDING, self::getById($this->getId()));

        do_action('vehica/notification/' . Notification::NEW_CAR_PENDING, self::getById($this->getId()));
    }

    /**
     * @param Collection $simpleTextAttributes
     * @return Collection
     */
    public function getSimpleTextValues(Collection $simpleTextAttributes)
    {
        $values = Collection::make();

        foreach ($simpleTextAttributes as $simpleTextAttribute) {
            /* @var SimpleTextAttribute $simpleTextAttribute */
            $values = $values->merge($simpleTextAttribute->getSimpleTextValues($this));
        }

        return $values;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        $labels = [];

        foreach (vehicaApp('card_label_elements') as $element) {
            if ($element === 'featured') {
                if ($this->isFeatured()) {
                    $labels[] = new CardLabel(vehicaApp('featured_string'));
                }

                continue;
            }

            $taxonomy = vehicaApp('taxonomy_' . $element);
            if (!$taxonomy instanceof Taxonomy) {
                continue;
            }

            foreach ($this->getTerms($taxonomy) as $term) {
                /* @var Term $term */
                if (!$term->useAsLabel()) {
                    continue;
                }

                $labels[] = new CardLabel(
                    $term->getName(),
                    $term->getLabelColor(),
                    $term->getLabelBackgroundColor()
                );
            }
        }

        return $labels;
    }

    /**
     * @return false|CardLabel
     */
    public function getLabel()
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($this->getLabels() as $label) {
            return $label;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getPostTypeKey()
    {
        return self::POST_TYPE;
    }

    /**
     * @return false|int
     */
    public function duplicate()
    {
        $id = parent::duplicate();
        if (!$id) {
            return false;
        }

        vehicaApp('taxonomies')->each(function ($taxonomy) use ($id) {
            /* @var Taxonomy $taxonomy */
            $terms = $this->getTerms($taxonomy)->map(static function ($term) {
                /* @var Term $term */
                return $term->getId();
            })->all();

            wp_add_object_terms($id, $terms, $taxonomy->getKey());
        });

        return $id;
    }

    /**
     * @param Term $term
     * @return bool
     */
    public function hasTerm(Term $term)
    {
        return has_term($term->getId(), $term->getTaxonomyKey(), $this->model);
    }

    /**
     * @return string
     */
    public function getCalculateFinancingUrl()
    {
        if (!vehicaApp('calculator_page_url')) {
            return '';
        }

        return vehicaApp('calculator_page_url') . '?id=' . $this->getId();
    }

    /**
     * @param LocationField $locationField
     * @param SimpleTextAttribute|null $simpleTextAttribute
     * @return array
     */
    public function getMarkerData(LocationField $locationField, $simpleTextAttribute = null)
    {
        $data = [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
            'image' => $this->getImageUrl('vehica_335_186'),
            'location' => $locationField->getValue($this),
            'label' => $this->getLabel(),
            'value' => '',
        ];

        if ($simpleTextAttribute) {
            $data['value'] = $simpleTextAttribute->getSimpleTextValues($this)->implode(', ');
        }

        if ($simpleTextAttribute instanceof PriceField && empty($data['value'])) {
            $data['value'] = vehicaApp('contact_for_price_string');
        }

        return $data;
    }

    public function setPublish()
    {
        $time = current_time('mysql');

        wp_update_post([
            'ID' => $this->getId(),
            'post_status' => PostStatus::PUBLISH,
            'post_date' => $time,
            'post_date_gmt' => get_gmt_from_date($time)
        ]);
    }

}