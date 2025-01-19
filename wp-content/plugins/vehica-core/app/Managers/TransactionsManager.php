<?php


namespace Vehica\Managers;


use Vehica\Core\Manager;
use Vehica\Model\Post\Transaction;

/**
 * Class TransactionsManager
 *
 * @package Vehica\Managers
 */
class TransactionsManager extends Manager
{

    public function boot()
    {
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType()
    {
        register_post_type(
            Transaction::POST_TYPE,
            [
                'label' => esc_html__('Transactions', 'vehica-core'),
                'public' => false,
                'exclude_from_search' => true,
                'publicly_queryable' => false,
                'show_in_rest' => false,
            ]
        );
    }

}