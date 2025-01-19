<?php

namespace Vehica\Widgets\Partials\Users;


use Elementor\Controls_Manager;
use Vehica\Core\Collection;
use Vehica\Model\User\User;
use Vehica\Widgets\Controls\SelectRemoteControl;
use Vehica\Widgets\Partials\WidgetPartial;

/**
 * Trait QueryUsersPartialWidget
 * @package Vehica\Widgets\Partials\Users
 */
trait QueryUsersPartialWidget
{
    use WidgetPartial;

    /**
     * @var Collection|null
     */
    protected $users;

    protected function addQueryUsersLimitControl()
    {
        $this->add_control(
            QueryUsers::LIMIT,
            [
                'label' => esc_html__('Total Number of Users', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
                'label_block' => true
            ]
        );
    }

    protected function addQueryUsersOffsetControl()
    {
        $this->add_control(
            QueryUsers::OFFSET,
            [
                'label' => esc_html__('Offset', 'vehica-core'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0
            ]
        );
    }

    protected function addQueryUsersIncludeControl()
    {
        $this->add_control(
            QueryUsers::INCLUDED,
            [
                'label' => esc_html__('Include', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => User::getApiEndpoint(),
                'multiple' => true
            ]
        );
    }

    protected function addQueryUsersExcludeControl()
    {
        $this->add_control(
            QueryUsers::EXCLUDED,
            [
                'label' => esc_html__('Exclude', 'vehica-core'),
                'type' => SelectRemoteControl::TYPE,
                'source' => User::getApiEndpoint(),
                'multiple' => true
            ]
        );
    }

    protected function addQueryUsersRoleControl()
    {
        $this->add_control(
            QueryUsers::USER_ROLE,
            [
                'label' => esc_html__('Role', 'vehica-core'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => vehicaApp('user_roles')
            ]
        );
    }

    protected function addQueryUsersByIdsControl()
    {
        $this->add_control(
            'query_users_by_ids',
            [
                'label' => esc_html__('Query by user IDs', 'listivo-core'),
                'placeholder' => '1,3,10,24',
                'description' => esc_html__('Other query controls will be ignored.', 'vehica-core'),
                'type' => Controls_Manager::TEXT,
            ]
        );
    }

    protected function addQueryUsersSortByControl()
    {
        $this->add_control(
            QueryUsers::SORT_BY,
            [
                'label' => esc_html__('Sort By', 'vehica-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    QueryUsers::SORT_BY_NAME => esc_html__('Name', 'vehica-core'),
                    QueryUsers::SORT_BY_ID => esc_html__('ID', 'vehica-core'),
                ],
                'default' => QueryUsers::SORT_BY_NAME
            ]
        );
    }

    /**
     * @param array $exclude
     */
    protected function addQueryUsersControls($exclude = [])
    {
        if (!in_array(QueryUsers::LIMIT, $exclude, true)) {
            $this->addQueryUsersLimitControl();
        }

        if (!in_array(QueryUsers::INCLUDED, $exclude, true)) {
            $this->addQueryUsersIncludeControl();
        }

        if (!in_array(QueryUsers::EXCLUDED, $exclude, true)) {
            $this->addQueryUsersExcludeControl();
        }

        if (!in_array(QueryUsers::USER_ROLE, $exclude, true)) {
            $this->addQueryUsersRoleControl();
        }

        if (!in_array(QueryUsers::SORT_BY, $exclude, true)) {
            $this->addQueryUsersSortByControl();
        }

        $this->addQueryUsersByIdsControl();
    }


    /**
     * @return bool
     */
    public function hasUsers()
    {
        return $this->getUsers()->isNotEmpty();
    }

    /**
     * @return array
     */
    protected function getUserRoles()
    {
        $roles = $this->get_settings_for_display(QueryUsers::USER_ROLE);

        if (!is_array($roles)) {
            return [];
        }

        return $roles;
    }

    /**
     * @return array
     */
    protected function getExcludedUsers()
    {
        $excludedUsers = $this->get_settings_for_display(QueryUsers::EXCLUDED);

        if (!is_array($excludedUsers)) {
            return [];
        }

        return $excludedUsers;
    }

    /**
     * @return array
     */
    protected function getIncludedUsers()
    {
        $includedUsers = $this->get_settings_for_display(QueryUsers::INCLUDED);

        if (!is_array($includedUsers)) {
            return [];
        }

        return $includedUsers;
    }

    /**
     * @return int
     */
    protected function getUsersLimit()
    {
        $limit = (int)$this->get_settings_for_display(QueryUsers::LIMIT);

        if ($limit === 0) {
            return 6;
        }

        return $limit;
    }

    /**
     * @return string
     */
    protected function getSortUsersBy()
    {
        $sortBy = (string)$this->get_settings_for_display(QueryUsers::SORT_BY);

        if (empty($sortBy)) {
            return QueryUsers::SORT_BY_ID;
        }

        return $sortBy;
    }

    /**
     * @return bool
     */
    protected function sortUsersByName()
    {
        return $this->getSortUsersBy() === QueryUsers::SORT_BY_NAME;
    }

    /**
     * @return bool
     */
    protected function sortUsersById()
    {
        return $this->getSortUsersBy() === QueryUsers::SORT_BY_ID;
    }

    protected function prepareUsers()
    {
        $args = [
            'include' => $this->getIncludedUsers(),
            'exclude' => $this->getExcludedUsers(),
            'number' => $this->getUsersLimit(),
        ];

        $metaQuery = [
            'relation' => 'OR'
        ];

        global $wpdb;
        foreach ($this->getUserRoles() as $role) {
            $metaQuery[] = [
                'key' => $wpdb->get_blog_prefix(get_current_blog_id()) . 'capabilities',
                'value' => $role,
                'compare' => 'like'
            ];
        }

        if (count($metaQuery) > 1) {
            $args['meta_query'] = $metaQuery;
        }

        if ($this->sortUsersByName()) {
            $args['orderby'] = 'nicename';
            $args['order'] = 'ASC';
        } elseif ($this->sortUsersById()) {
            $args['orderby'] = 'ID';
            $args['order'] = 'DESC';
        }

        if (!empty($this->get_settings_for_display('query_users_by_ids'))) {
            $args['include'] = explode(',', $this->get_settings_for_display('query_users_by_ids'));
            $args['orderby'] = 'include';
        }

        $args = apply_filters('vehica/widget/users/queryArgs', $args);

        $this->users = Collection::make(get_users($args))->map(static function ($user) {
            return new User($user);
        });
    }

    /**
     * @return Collection
     */
    public function getUsers()
    {
        if (!$this->users) {
            $this->prepareUsers();
        }

        return $this->users;
    }

}