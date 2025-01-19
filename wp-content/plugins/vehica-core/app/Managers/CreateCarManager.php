<?php /** @noinspection DuplicatedCode */


namespace Vehica\Managers;


use Vehica\Action\ApplyPackageAction;
use Vehica\Core\Manager;
use Vehica\Core\Notification;
use Vehica\Model\Post\Car;
use Vehica\Model\Post\Page;
use Vehica\Model\User\User;
use Vehica\Panel\PanelField\PanelField;
use Vehica\Widgets\General\PanelGeneralWidget;
use WP_Post;

/**
 * Class CreateCarManager
 *
 * @package Vehica\Managers
 */
class CreateCarManager extends Manager
{

    public function boot()
    {
        add_action('admin_post_vehica_create_car', [$this, 'create']);
        add_action('admin_post_nopriv_vehica_create_car', [$this, 'create']);

        add_action('admin_post_vehica_delete_car', [$this, 'delete']);

        add_action('admin_post_vehica_update_car', [$this, 'update']);

        add_action('admin_post_vehica_publish_car', [$this, 'publish']);

        add_action('admin_post_vehica_approve_car', [$this, 'approve']);
        add_action('admin_post_vehica_decline_car', [$this, 'decline']);

        add_action('admin_post_vehica_assign_car_and_buy_package', [$this, 'assignCarBuyPackage']);

        add_action('save_post_' . Car::POST_TYPE, [$this, 'setTitle'], 10, 2);

        add_filter('vehica/panel/redirectAfterListingCreated', [$this, 'redirectAfterListingCreated']);

        add_action('pending_to_publish', [$this, 'statusChange']);
    }

    /**
     * @param WP_Post $post
     */
    public function statusChange($post)
    {
        if (isset($_GET['carId']) || $post->post_type !== Car::POST_TYPE || !current_user_can('manage_options')) {
            return;
        }

        $car = Car::getByPost($post);

        if (!$car instanceof Car) {
            return;
        }

        if (!$this->isPaid($car) && vehicaApp('settings_config')->isPaymentEnabled()) {
            $package = $car->getPendingPackage();
            if (!$package) {
                return;
            }

            $applyPackage = new ApplyPackageAction();
            if (!$applyPackage->apply($package, $car)) {
                $car->setDraft();
                return;
            }

            $car->removePendingPackage();
        }

        do_action('vehica/notification/' . Notification::CAR_APPROVED, $car);
    }

    /**
     * @param string $redirect
     * @return string
     */
    public function redirectAfterListingCreated($redirect)
    {
        $page = vehicaApp('settings_config')->getRedirectAfterListingCreatedPage();

        if (!$page instanceof Page) {
            return $redirect;
        }

        return $page->getUrl();
    }

    /**
     * @param int $postId
     * @param WP_Post $post
     * @noinspection PhpUnusedParameterInspection
     */
    public function setTitle($postId, WP_Post $post)
    {
        remove_action('save_post_' . Car::POST_TYPE, [$this, 'setTitle']);

        wp_update_post([
            'ID' => $post->ID,
            'post_title' => apply_filters('vehica/car/name', $post->post_title, Car::make($post))
        ]);

        add_action('save_post_' . Car::POST_TYPE, [$this, 'setTitle'], 10, 2);
    }

    public function publish()
    {
        if (empty($_GET['carId'])) {
            wp_redirect(site_url());
            exit;
        }

        if (!vehicaApp('settings_config')->isPaymentEnabled()) {
            wp_redirect(PanelGeneralWidget::getCarListPageUrl());
            exit;
        }

        $carId = (int)$_GET['carId'];
        $car = Car::getById($carId);
        if (!$car instanceof Car || !$car->isDraft()) {
            wp_redirect(PanelGeneralWidget::getCarListPageUrl());
            exit;
        }

        if (!$car->isPublished()) {
            $car->setPublish();
        }

        wp_redirect(PanelGeneralWidget::getCarListPageUrl());
        exit;
    }

    public function approve()
    {
        if (empty($_GET['carId']) || !current_user_can('manage_options')) {
            wp_redirect(site_url());
            exit;
        }

        $carId = (int)$_GET['carId'];
        $car = Car::getById($carId);

        if (!$car instanceof Car || !$car->isPending()) {
            wp_redirect(PanelGeneralWidget::getCarListPageUrl());
            exit;
        }

        if (!$this->isPaid($car) && vehicaApp('settings_config')->isPaymentEnabled()) {
            $package = $car->getPendingPackage();
            if (!$package) {
                wp_redirect(PanelGeneralWidget::getCarListPageUrl());
                exit;
            }

            $applyPackage = new ApplyPackageAction();
            if (!$applyPackage->apply($package, $car)) {
                $car->setDraft();

                wp_redirect(PanelGeneralWidget::getCarListPageUrl());
                exit;
            }

            $car->removePendingPackage();
        }

        $car->setApproved();

        if (!$car->isPublished()) {
            $car->setPublish();
        }

        do_action('vehica/notification/' . Notification::CAR_APPROVED, $car);

        wp_redirect(PanelGeneralWidget::getCarListPageUrl());
        exit;
    }

    /**
     * @param Car $car
     * @return bool
     */
    private function isPaid(Car $car)
    {
        return $car->hasExpireDate() && !$car->isExpired();
    }

    public function decline()
    {
        if (empty($_GET['carId']) || !current_user_can('manage_options')) {
            wp_redirect(site_url());
            exit;
        }

        $carId = (int)$_GET['carId'];
        $car = Car::getById($carId);

        if (!$car instanceof Car || !$car->isPending()) {
            wp_redirect(PanelGeneralWidget::getCarListPageUrl());
            exit;
        }

        $car->setDraft();

        do_action('vehica/notification/' . Notification::CAR_DECLINED, $car);

        wp_redirect(PanelGeneralWidget::getCarListPageUrl());
        exit;
    }

    public function create()
    {
        if (!is_user_logged_in() && $this->isReCaptchaEnabled() && !$this->verifyReCaptcha('submit')) {
            wp_die();
        }

        if (!is_user_logged_in() && !vehicaApp('settings_config')->isSubmitWithoutLoginEnabled()) {
            wp_die();
        }

        if (is_user_logged_in() && !vehicaApp('current_user')->canCreateCars()) {
            wp_die();
        }

        if (!isset($_POST['nonce'], $_POST['car']) || !wp_verify_nonce($_POST['nonce'], 'vehica_create_car')) {
            wp_die();
        }

        $car = Car::create();

        $car->setMeta('vehica_source', 'panel');

        if (is_user_logged_in()) {
            $car->setUser(get_current_user_id());
        } else {
            $this->assignTempOwner($car);
        }

        if (isset($_POST['car']['id'])) {
            unset($_POST['car']['id']);
        }

        foreach (vehicaApp('panel_fields') as $panelField) {
            /* @var PanelField $panelField */
            $panelField->update($car, $_POST['car']);
        }

        if (is_user_logged_in() && current_user_can('manage_options')) {
            if (!empty($_POST['car']['featured']) && $_POST['car']['featured'] === 'true') {
                $car->setFeatured();
            } else {
                $car->removeFeatured();
            }
        }

        if (vehicaApp('settings_config')->isPaymentEnabled()) {
            $this->managePaymentStatus($car);
        } elseif (
            !current_user_can('manage_options')
            && !$car->isApproved()
            && vehicaApp('settings_config')->isModerationEnabled()
        ) {
            $car->setPending();
        } elseif (!$car->isPublished()) {
            $car->setPublish();
        }

        do_action('vehica/car/created', $car);
    }

    /**
     * @param Car $car
     */
    private function assignTempOwner(Car $car)
    {
        $car->setTempOwnerKey(User::getTempOwnerKey());
    }

    /**
     * @param Car $car
     */
    private function managePaymentStatus(Car $car)
    {
        $user = User::getById($car->getUserId());
        if (!$user) {
            $car->setDraft();
            return;
        }

        if (empty($_POST['packageKey']) && !$car->hasPendingPackage()) {
            $car->setDraft();

            $user->setCarInProgress($car->getId());
            return;
        }

        if ($car->hasPendingPackage()) {
            $package = $car->getPendingPackage();
        } else {
            $package = false;
        }

        if (!$package && !empty($_POST['packageKey'])) {
            $packageKey = $_POST['packageKey'];
            $package = $user->getPackage($packageKey);
        } else {
            $packageKey = '';
        }

        if (!$package || $package->isEmpty()) {
            $car->setDraft();
            return;
        }

        if (!current_user_can('manage_options') && vehicaApp('settings_config')->isModerationEnabled()) {
            if ($packageKey === 'free') {
                $car->setPendingPackage('free');
            } else {
                $car->setPendingPackage($package->getKey());
            }

            $car->setPending();
            return;
        }

        $applyPackage = new ApplyPackageAction();
        if (!$applyPackage->apply($package, $car)) {
            $car->setDraft();
            return;
        }

        $car->removePendingPackage();

        $car->setPublish();
    }

    public function update()
    {
        if (!isset($_POST['nonce'], $_POST['car']) || !wp_verify_nonce($_POST['nonce'], 'vehica_update_car')) {
            wp_die();
        }

        if (!vehicaApp('current_user')->canCreateCars()) {
            wp_die();
        }

        $car = Car::getById($_POST['car']['id']);

        foreach (vehicaApp('panel_fields') as $panelField) {
            /* @var PanelField $panelField */
            $panelField->update($car, $_POST['car']);
        }

        if (is_user_logged_in() && current_user_can('manage_options')) {
            if ($_POST['car']['featured'] === 'true') {
                $car->setFeatured();
            } else {
                $car->removeFeatured();
            }
        }

        if (!$car->hasExpireDate() && vehicaApp('settings_config')->isPaymentEnabled()) {
            $this->managePaymentStatus($car);
        } elseif (!current_user_can('manage_options') && vehicaApp('settings_config')->isModerationEnabled()) {
            if ($car->isPublished() && !vehicaApp('settings_config')->listingAfterEditMustBeApproved()) {
                if (!$car->isPublished()) {
                    $car->setPublish();
                }
            } else {
                $car->setPending();
            }
        } elseif (!$car->isPublished()) {
            $car->setPublish();
        }

        do_action('vehica/car/updated', $car);
    }

    public function delete()
    {
        if (empty($_POST['carId'])) {
            echo json_encode(['success' => false]);

            return;
        }

        $carId = (int)$_POST['carId'];

        if (empty($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'vehica_delete_car_' . $carId)) {
            echo json_encode(['success' => false]);

            return;
        }

        $user = vehicaApp('current_user');
        if (!$user instanceof User) {
            echo json_encode(['success' => false]);

            return;
        }

        $car = Car::getById($carId);
        if (!$car instanceof Car) {
            echo json_encode(['success' => false]);

            return;
        }

        if (!$user->isAdmin() && $car->getUserId() !== $user->getId()) {
            echo json_encode(['success' => false]);

            return;
        }

        if ($car->delete() !== true) {
            echo json_encode(['success' => false]);

            return;
        }

        echo json_encode(['success' => true]);
    }

    public function assignCarBuyPackage()
    {
        if (!isset($_GET['carId'])) {
            return;
        }

        $carId = (int)$_GET['carId'];
        $car = Car::getById($carId);
        if (!$car || !$car->isDraft() || $car->getUserId() !== get_current_user_id()) {
            return;
        }

        vehicaApp('current_user')->setCarInProgress($carId);

        wp_redirect(PanelGeneralWidget::getBuyPackageUrl());
        exit;
    }

}