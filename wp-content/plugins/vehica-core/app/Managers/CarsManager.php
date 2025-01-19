<?php /** @noinspection HtmlUnknownTarget */

namespace Vehica\Managers;

if (!defined('ABSPATH')) {
    exit;
}

use Vehica\Attribute\SimpleTextAttribute;
use Vehica\Core\Collection;
use Vehica\Core\Manager;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;
use WP_Post;

/**
 * Class CarsManager
 *
 * @package Vehica\Managers
 */
class CarsManager extends Manager
{

    public function boot()
    {
        add_action('wp_ajax_sample-permalink', static function () {
            $id = (int)$_POST['post_id'];
            $newSlug = !empty($_POST['new_slug']) ? $_POST['new_slug'] : '';

            if (empty($id) || empty($newSlug)) {
                return;
            }

            $car = Car::getById($id);
            $post = $car->getPost();
            if (!$post instanceof WP_Post || $post->post_type !== Car::POST_TYPE) {
                return;
            }

            $car->setSlug($newSlug);
        }, 1);

        add_filter('vehica/postTypes/register', static function ($postTypables) {
            $carConfig = vehicaApp('car_config');

            if ($carConfig) {
                $postTypables[] = $carConfig;
            }

            return $postTypables;
        });

        add_filter('use_block_editor_for_post_type', static function ($isEnabled, $postType) {
            if ($postType === Car::POST_TYPE) {
                return false;
            }

            return $isEnabled;
        }, 10, 2);

        add_action('add_meta_boxes', static function () {
            remove_meta_box('slugdiv', Car::POST_TYPE, 'normal');
        });

        add_filter('post_updated_messages', static function ($messages) {
            global $post;
            if (!$post instanceof WP_Post || $post->post_type !== Car::POST_TYPE) {
                return $messages;
            }

            $car = new Car($post);

            $messages[Car::POST_TYPE] = [
                0 => '',
                1 => sprintf(
                    '%1$s <a target="_blank" href="%2$s">%3$s</a>',
                    esc_html__('Listing updated.', 'vehica-core'),
                    $car->getUrl(),
                    esc_html__('View car', 'vehica-core')
                ),
                2 => esc_html__('Listing updated', 'vehica-core'),
                3 => esc_html__('Listing deleted', 'vehica-core'),
                4 => esc_html__('Listing updated', 'vehica-core'),
                5 => '',
                6 => sprintf(
                    '%1$s <a target="_blank" href="%2$s">%3$s</a>',
                    esc_html__('Listing published.', 'vehica-core'),
                    $car->getUrl(),
                    esc_html__('View listing', 'vehica-core')
                ),
                7 => esc_html__('Listing saved', 'vehica-core'),
                8 => sprintf(
                    '%1$s <a target="_blank" href="%2$s">%3$s</a>',
                    esc_html__('Listing submitted.', 'vehica-core'),
                    $car->getUrl(),
                    esc_html__('Preview listing', 'vehica-core')
                ),
                9 => sprintf(
                    '%1$s <a target="_blank" href="%2$s">%3$s</a>',
                    esc_html__('Listing scheduled.', 'vehica-core'),
                    $car->getUrl(),
                    esc_html__('Preview listing', 'vehica-core')
                ),
                10 => sprintf(
                    '%1$s <a target="_blank" href="%2$s">%3$s</a>',
                    esc_html__('Listing draft updated.', 'vehica-core'),
                    $car->getUrl(),
                    esc_html__('Preview car', 'vehica-core')
                ),
            ];

            return $messages;
        });

        add_action('save_post_' . Car::POST_TYPE, [$this, 'saveIsFeatured']);

        add_action('vehica/layouts/single/car', [$this, 'updateViews']);

        add_action('vehica/checkExpire', [$this, 'checkExpired']);

        if (!wp_next_scheduled('vehica/checkExpire')) {
            wp_schedule_event(time(), 'hourly', 'vehica/checkExpire');
        }

        add_action('init', [$this, 'checkTempOwnerKey']);

        add_filter('vehica/car/name', static function ($name, Car $car) {
            if (vehicaApp('auto_title_fields')->isEmpty()) {
                return $name;
            }

            $values = Collection::make();
            foreach (vehicaApp('auto_title_fields') as $field) {
                /* @var SimpleTextAttribute $field */
                $values = $values->merge($field->getSimpleTextValues($car));
            }

            return $values->implode(' ');
        }, 10, 2);

        add_filter('wpseo_title', static function ($title) {
            if (!is_singular(Car::POST_TYPE) || vehicaApp('auto_title_fields')->isEmpty()) {
                return $title;
            }

            $car = Car::getCurrent();
            if (!$car) {
                return $title;
            }

            return $car->getName();
        });

        add_filter('post_row_actions', static function ($actions, $post) {
            if ($post->post_type !== Car::POST_TYPE || !current_user_can('manage_options')) {
                return $actions;
            }

            $duplicateUrl = admin_url('admin-post.php?action=vehica_admin_duplicate_post&postId=' . $post->ID);
            $action = '<a href="' . esc_url($duplicateUrl) . '">' . esc_html__('Duplicate', 'vehica-core') . '</a>';

            $actions['vehica_duplicate'] = $action;

            return $actions;
        }, 10, 2);
    }

    public function checkTempOwnerKey()
    {
        if (empty($_GET['action']) || !is_user_logged_in()) {
            return;
        }

        $user = User::getCurrent();
        if (!$user) {
            return;
        }

        if (!$user->isTempOwnerKeySet()) {
            return;
        }

        Car::getByTempOwnerKey(User::getTempOwnerKey())->each(static function ($car) use ($user) {
            /* @var Car $car */
            $car->setUser($user->getId());
            $car->removeTempOwnerKey();
        });
    }

    public function updateViews()
    {
        global $vehicaCar;
        if (!$vehicaCar instanceof Car) {
            return;
        }

        $vehicaCar->increaseViews();
    }

    public function saveIsFeatured($id)
    {
        if (!isset($_POST['vehica_save_car']) || !wp_verify_nonce($_POST['vehica_save_car'], 'vehica_save_car')) {
            return;
        }

        if (!empty($_POST['vehica_featured'])) {
            $isFeatured = 1;
        } else {
            $isFeatured = 0;
        }

        update_post_meta($id, 'vehica_featured', $isFeatured);
    }

    public function checkExpired()
    {
        Car::getPublish()->each(static function ($car) {
            /* @var Car $car */
            if ($car->isExpired()) {
                $car->clearExpireDate();
                $car->setDraft();
            }

            if ($car->isFeaturedExpired()) {
                $car->removeFeatured();
                $car->clearFeaturedExpireDate();
            }
        });
    }

}