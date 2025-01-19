<?php


namespace Vehica\Components\Panel;


use Vehica\Core\Collection;
use Vehica\Core\Post\PostStatus;
use Vehica\Model\Post\Car;
use Vehica\Model\User\User;
use Vehica\Widgets\General\PanelGeneralWidget;
use WP_Query;

/**
 * Class CarList
 * @package Vehica\Components\Panel
 */
class CarList
{
    /**
     * @var array
     */
    protected $params;

    /**
     * CarList constructor.
     *
     * @param array $params
     */
    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * @param array $params
     *
     * @return CarList
     */
    public static function make($params = [])
    {
        return new self($params);
    }

    /**
     * @return bool
     */
    public function hasPaginationPrev()
    {
        return $this->getCurrentPage() > 1;
    }

    /**
     * @return string
     */
    public function getPaginationPrev()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_page' => $this->getCurrentPage() - 1,
                'vehica_status' => $this->getStatus(),
                'vehica_keyword' => $this->getKeyword()
            ]);
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        if (!isset($this->params['vehica_keyword'])) {
            return '';
        }

        return (string)trim($this->params['vehica_keyword']);
    }

    /**
     * @return string
     */
    public function getPaginationNext()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_page' => $this->getCurrentPage() + 1,
                'vehica_status' => $this->getStatus(),
                'vehica_keyword' => $this->getKeyword(),
            ]);
    }

    /**
     * @param string $status
     *
     * @return int
     */
    private function getCarsNumber($status)
    {
        $params = [
            'post_type' => Car::POST_TYPE,
            'posts_per_page' => apply_filters('vehica/panel/list/limit', 10),
            'post_status' => $status,
        ];

        $user = User::getCurrent();
        if ($user && !$user->isAdmin()) {
            $params['author'] = $user->getId();
        }

        return (new WP_Query($params))->found_posts;
    }

    /**
     * @return int
     */
    public function getPaginationCarsNumber()
    {
        $params = [
            'post_type' => Car::POST_TYPE,
            'posts_per_page' => apply_filters('vehica/panel/list/limit', 10),
            'post_status' => $this->getStatus(),
            's' => $this->getKeyword()
        ];

        $user = User::getCurrent();
        if ($user && !$user->isAdmin()) {
            $params['author'] = $user->getId();
        }

        return (new WP_Query($params))->found_posts;
    }

    /**
     * @return int
     */
    public function getAllCarsNumber()
    {
        return $this->getCarsNumber(PostStatus::ANY);
    }

    /**
     * @return int
     */
    public function getActiveCarsNumber()
    {
        return $this->getCarsNumber(PostStatus::PUBLISH);
    }

    /**
     * @return int
     */
    public function getPendingCarsNumber()
    {
        return $this->getCarsNumber(PostStatus::PENDING);
    }

    /**
     * @return int
     */
    public function getDraftCarsNumber()
    {
        return $this->getCarsNumber(PostStatus::DRAFT);
    }

    /**
     * @return string
     */
    public function getPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_status' => $this->getStatus(),
                'vehica_keyword' => $this->getKeyword(),
            ]) . '&vehica_page=';
    }

    /**
     * @return bool
     */
    public function isPendingStatus()
    {
        return $this->getStatus() === PostStatus::PENDING;
    }

    /**
     * @return bool
     */
    public function isDraftStatus()
    {
        return $this->getStatus() === PostStatus::DRAFT;
    }

    /**
     * @return bool
     */
    public function isPublishStatus()
    {
        return $this->getStatus() === PostStatus::PUBLISH;
    }

    /**
     * @return bool
     */
    public function isAnyStatus()
    {
        return $this->getStatus() === PostStatus::ANY;
    }

    public function getStatus()
    {
        if (!isset($this->params['vehica_status'])) {
            return PostStatus::ANY;
        }

        $status = $this->params['vehica_status'];

        if (!in_array($status, [
            PostStatus::PUBLISH,
            PostStatus::PENDING,
            PostStatus::DRAFT,
        ], true)) {
            return PostStatus::ANY;
        }

        return $status;
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        $status = $this->getStatus();

        if ($status === PostStatus::PUBLISH) {
            return vehicaApp('active_string');
        }

        if ($status === PostStatus::PENDING) {
            return vehicaApp('pending_string');
        }

        if ($status === PostStatus::DRAFT) {
            return vehicaApp('draft_string');
        }

        return vehicaApp('active_string');
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return isset($this->params['vehica_page']) ? (int)$this->params['vehica_page'] : 1;
    }

    /**
     * @return string
     */
    public function getActiveCarListPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_status' => PostStatus::PUBLISH
            ]);
    }

    /**
     * @return string
     */
    public function getPendingCarListPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_status' => PostStatus::PENDING
            ]);
    }

    /**
     * @return string
     */
    public function getDraftCarListPageUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_status' => PostStatus::DRAFT
            ]);
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return vehicaApp('panel_page_url') . '?' . http_build_query([
                PanelGeneralWidget::ACTION_TYPE => PanelGeneralWidget::ACTION_TYPE_CAR_LIST,
                'vehica_status' => $this->getStatus(),
            ]);
    }

    /**
     * @return Collection
     */
    public function getCars()
    {
        $params = [
            'post_type' => Car::POST_TYPE,
            'posts_per_page' => apply_filters('vehica/panel/list/limit', 10),
            'paged' => $this->getCurrentPage(),
            'post_status' => $this->getStatus()
        ];

        $user = User::getCurrent();
        if ($user && !$user->isAdmin()) {
            $params['author'] = $user->getId();
        }

        if (isset($this->params['vehica_keyword'])) {
            $params['s'] = $this->params['vehica_keyword'];
        }

        $query = new WP_Query($params);

        return Collection::make($query->posts)->map(static function ($car) {
            return new Car($car);
        });
    }

    /**
     * @param int $page
     *
     * @return bool
     */
    public function isCurrentPage($page)
    {
        return $page === $this->getCurrentPage();
    }

    public function getUrl()
    {

    }

}